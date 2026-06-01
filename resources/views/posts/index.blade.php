<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mon Blog
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('posts.create') }}"
                        class="bg-gray-900 text-white px-5 py-2 rounded-full text-sm hover:bg-gray-700 transition">
                        + Nouveau post
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    {{-- Hero --}}
    <div class="bg-white border-b border-gray-100 py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <p class="text-sm uppercase tracking-widest text-gray-400 mb-3">Bienvenue sur mon blog</p>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">AKOUETE ABLANVI JUSTIN</h1>
            <p class="text-lg text-gray-500 mb-8">Élève Ingénieur 3ème année · Passionné de technologie, d'innovation et d'ingénierie.</p>

            {{-- Barre de recherche --}}
            <form method="GET" action="{{ route('home') }}" class="flex gap-2 max-w-lg mx-auto">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Rechercher un article..."
                    class="flex-1 border border-gray-200 rounded-full px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                <button type="submit"
                    class="bg-gray-900 text-white px-6 py-3 rounded-full text-sm hover:bg-gray-700 transition">
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-8 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($search)
                <p class="text-sm text-gray-500 mb-6">
                    Résultats pour <span class="font-semibold text-gray-900">"{{ $search }}"</span> —
                    <a href="{{ route('home') }}" class="text-blue-500 hover:underline">Effacer</a>
                </p>
            @endif

            <div class="space-y-8">
                @forelse($posts as $post)
                    <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                        @if($post->photo)
                            <a href="{{ route('posts.show', $post) }}">
                                <img src="{{ Storage::url($post->photo) }}"
                                    class="w-full h-56 object-cover">
                            </a>
                        @endif
                        <div class="p-8">
                            <div class="flex items-center gap-2 text-xs text-gray-400 uppercase tracking-wider mb-3">
                                <span>{{ $post->created_at->format('d M Y') }}</span>
                                <span>·</span>
                                <span>{{ ceil(str_word_count($post->content) / 200) }} min de lecture</span>
                                <span>·</span>
                                <span>👁️ {{ $post->views }}</span>
                                <span>·</span>
                                <span>❤️ {{ $post->likes }}</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-3 leading-tight">
                                <a href="{{ route('posts.show', $post) }}"
                                    class="hover:text-gray-600 transition">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-gray-500 leading-relaxed mb-6">
                                {{ Str::limit(strip_tags($post->content), 160) }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-600 font-medium">{{ $post->user->name }}</span>
                                </div>
                                <a href="{{ route('posts.show', $post) }}"
                                    class="text-sm text-gray-900 font-medium hover:underline">
                                    Lire la suite →
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-20">
                        <p class="text-gray-400 text-lg">
                            @if($search)
                                Aucun résultat pour "{{ $search }}".
                            @else
                                Aucun post pour l'instant.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>