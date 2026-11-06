<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
    @php
        $items = $movies ?? $games ?? [];
        $isGame = isset($games);
    @endphp

    @forelse ($items as $item)
        <div class="movie-poster-card group">
            @if($isGame)
                <a href="/games/{{ $item['id'] ?? $item['rawg_id'] }}" class="block relative">
                    <img src="{{ $item['background_image'] }}"
                         alt="{{ $item['name'] }}"
                         class="rounded-lg shadow-lg poster-shadow transition-all duration-300 group-hover:brightness-75 aspect-[2/3] object-cover bg-gray-800">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-16 h-16 bg-rose-600/80 rounded-full grid place-items-center backdrop-blur-sm transform scale-75 group-hover:scale-100 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/80 to-transparent rounded-b-lg">
                        <h3 class="text-white font-semibold text-sm truncate">{{ $item['name'] }}</h3>
                        <p class="text-xs text-gray-300">{{ $item['released'] ? substr($item['released'], 0, 4) : 'N/A' }}</p>
                    </div>
                </a>
            @else
                <a href="/filme/{{ $item['id'] ?? $item['tmdb_id'] }}" class="block relative">
                    <img src="{{ $item['poster_path'] ?? $item['poster'] ?? asset('images/placeholder.svg') }}"
                         alt="Pôster de {{ $item['title'] }}"
                         class="rounded-lg shadow-lg poster-shadow transition-all duration-300 group-hover:brightness-75 aspect-[2/3] object-cover bg-gray-800">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-16 h-16 bg-rose-600/80 rounded-full grid place-items-center backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/80 to-transparent rounded-b-lg">
                        <h3 class="text-white font-semibold text-sm truncate">{{ $item['title'] }}</h3>
                        <p class="text-xs text-gray-300">{{ substr($item['release_date'] ?? '', 0, 4) }}</p>
                    </div>
                </a>
            @endif
        </div>
    @empty
        <div class="col-span-full text-center py-12 bg-white/5 rounded-lg flex flex-col items-center justify-center gap-4">
            <svg class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
            <p class="text-gray-400">{{ $emptyMessage }}</p>
        </div>
    @endforelse
</div>