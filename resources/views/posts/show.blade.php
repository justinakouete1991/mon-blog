<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">Accueil</a>
            <span>›</span>
            <span class="text-gray-900 font-medium truncate max-w-xs">{{ $post->title }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-6">

            {{-- Post --}}
            <article class="bg-white rounded-2xl overflow-hidden shadow-sm mb-10">
                @if($post->photo)
                    <img src="{{ Storage::url($post->photo) }}" class="w-full h-72 object-cover">
                @endif
                <div class="p-10">
                    <div class="flex items-center gap-2 text-xs text-gray-400 uppercase tracking-wider mb-4">
                        <span>{{ $post->created_at->format('d M Y') }}</span>
                        <span>·</span>
                        <span>{{ ceil(str_word_count($post->content) / 200) }} min de lecture</span>
                        <span>·</span>
                        <span>👁️ {{ $post->views }} vues</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>
                    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="ml-auto flex gap-3">
                                    <a href="{{ route('posts.edit', $post) }}"
                                        class="text-sm text-gray-500 hover:text-gray-900 border border-gray-200 px-4 py-1.5 rounded-full transition">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Supprimer ce post ?')"
                                            class="text-sm text-red-400 hover:text-red-600 border border-red-200 px-4 py-1.5 rounded-full transition">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $post->content }}
                    </div>

                    {{-- Likes et Partage --}}
                    <div class="flex items-center justify-between mt-10 pt-8 border-t border-gray-100">
                        @auth
                            <form method="POST" action="{{ route('posts.like', $post) }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-500 px-5 py-2 rounded-full text-sm font-medium transition">
                                    ❤️ {{ $post->likes }} J'aime
                                </button>
                            </form>
                        @else
                            <span class="flex items-center gap-2 text-gray-400 text-sm">
                                ❤️ {{ $post->likes }} J'aime
                            </span>
                        @endauth

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wider">Partager</span>
                            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                                target="_blank"
                                class="bg-green-500 text-white px-4 py-2 rounded-full text-sm hover:bg-green-600 transition">
                                WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank"
                                class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm hover:bg-blue-700 transition">
                                Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Commentaires --}}
            <div class="bg-white rounded-2xl shadow-sm p-8 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-8">
                    Commentaires ({{ $post->comments->count() }})
                </h3>

                @forelse($post->comments as $comment)
                    <div class="mb-8">
                        @include('posts._comment', ['comment' => $comment, 'post' => $post])
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-6">Soyez le premier à commenter !</p>
                @endforelse
            </div>

            {{-- Formulaire commentaire principal --}}
            @auth
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Laisser un commentaire</h3>
                    <form method="POST" action="{{ route('comments.store', $post) }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <textarea name="body" rows="4"
                            placeholder="Partagez votre avis..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-gray-300 resize-none"
                            required></textarea>
                        @error('body')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end mt-3">
                            <button type="submit"
                                class="bg-gray-900 text-white px-6 py-2 rounded-full text-sm hover:bg-gray-700 transition">
                                Publier
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                    <p class="text-gray-500 text-sm">
                        <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:underline">Connectez-vous</a>
                        pour laisser un commentaire.
                    </p>
                </div>
            @endauth

        </div>
    </div>

    {{-- JavaScript pour afficher/cacher les formulaires de réponse --}}
    <script>
        function toggleReply(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>

</x-app-layout>