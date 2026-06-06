<div class="flex items-start gap-3">
    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-sm font-bold shrink-0">
        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
    </div>
    <div class="flex-1">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</span>
                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            @auth
                @if(auth()->user()->isAdmin() || auth()->id() == $comment->user_id)
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600">
                            Supprimer
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        <p class="text-gray-700 text-sm leading-relaxed mb-2">{{ $comment->body }}</p>

        {{-- Bouton Répondre --}}
        @auth
            <button onclick="toggleReply('reply-{{ $comment->id }}')"
                class="text-xs text-gray-400 hover:text-gray-700 font-medium mb-3">
                Répondre
            </button>

            {{-- Formulaire de réponse caché --}}
            <div id="reply-{{ $comment->id }}" class="hidden mb-4">
                <form method="POST" action="{{ route('comments.store', $post) }}">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="flex gap-2">
                        <input type="text" name="body"
                            value="{{ '@' . $comment->user->name }} "
                            placeholder="Répondre à {{ $comment->user->name }}..."
                            class="flex-1 border border-gray-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-300"
                            required>
                        <button type="submit"
                            class="bg-gray-900 text-white px-4 py-2 rounded-full text-sm hover:bg-gray-700 transition">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        @endauth

        {{-- Réponses imbriquées récursives --}}
        @if($comment->replies->count() > 0)
            <div class="mt-3 ml-4 pl-4 border-l-2 border-gray-100 space-y-4">
                @foreach($comment->replies as $comment)
                    @include('posts._comment', ['comment' => $comment, 'post' => $post])
                @endforeach
            </div>
        @endif
    </div>
</div>