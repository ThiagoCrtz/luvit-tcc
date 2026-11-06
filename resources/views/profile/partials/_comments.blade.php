<div class="space-y-6">
    @forelse ($comments as $comment)
        @if($comment->movie)
            <article class="comment-card p-5 rounded-2xl glass flex gap-4 transition-colors hover:bg-white/10">
                <div class="flex-shrink-0">
                    <a href="/filme/{{ $comment->movie->tmdb_id }}">
                        <img src="{{ $comment->movie->poster }}"
                             alt="Pôster de {{ $comment->movie->title }}"
                             class="w-20 h-28 object-cover rounded-lg hover:scale-105 transition-transform duration-300">
                    </a>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-400 mb-2">
                        Comentou em <a href="/filme/{{ $comment->movie->tmdb_id }}" class="font-semibold text-rose-400 hover:underline">{{ $comment->movie->title }}</a>
                        <span class="text-gray-500 text-xs">· {{ $comment->created_at->diffForHumans() }}</span>
                    </p>
                    <p class="text-gray-300 leading-relaxed">{{ $comment->review }}</p>
                    
                    @if($comment->rating)
                        <div class="mt-3 flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    @endif
                </div>
            </article>
        @elseif($comment->game)
            <article class="comment-card p-5 rounded-2xl glass flex gap-4 transition-colors hover:bg-white/10">
                <div class="flex-shrink-0">
                    <a href="/games/{{ $comment->game->rawg_id }}">
                        <img src="{{ $comment->game->background_image }}"
                             alt="{{ $comment->game->name }}"
                             class="w-20 h-28 object-cover rounded-lg hover:scale-105 transition-transform duration-300">
                    </a>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-400 mb-2">
                        Comentou em <a href="/games/{{ $comment->game->rawg_id }}" class="font-semibold text-rose-400 hover:underline">{{ $comment->game->name }}</a>
                        <span class="text-gray-500 text-xs">· {{ $comment->created_at->diffForHumans() }}</span>
                    </p>
                    <p class="text-gray-300 leading-relaxed">{{ $comment->review }}</p>
                    
                    @if($comment->rating)
                        <div class="mt-3 flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    @endif
                </div>
            </article>
        @endif
    @empty
        <p class="text-center text-gray-400 py-8">Nenhum comentário ainda.</p>
    @endforelse
</div>