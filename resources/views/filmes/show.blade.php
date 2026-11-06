@extends('layouts.app')
@vite(['resources/js/pages/filmes/show.js'])
@section('title', ($filme['title'] ?? $filme['name']) . ' - Luvit')

@section('content')
<section class="luv-hero pt-4 pb-12 relative overflow-hidden">
    
    <div class="absolute inset-0 z-0 select-none pointer-events-none">
        @if(!empty($filme['backdrop_path']))
            <div class="absolute inset-0 bg-cover bg-center opacity-20 blur-xl scale-110" 
                 style="background-image: url('https://image.tmdb.org/t/p/w1280/{{ $filme['backdrop_path'] }}');">
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/90 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/50 via-transparent to-gray-900"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative mt-6 z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-stretch">
            
            <div class="lg:col-span-1 flex justify-center lg:justify-start">
                <div class="poster-frame w-full max-w-md relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl h-full border border-white/10 group">
                        <img
                            src="{{ $filme['poster_path'] ? 'https://image.tmdb.org/t/p/w500/'.$filme['poster_path'] : '/images/placeholder-poster.png' }}"
                            alt="{{ $filme['title'] ?? $filme['name'] }}"
                            class="w-full h-full object-cover object-center"
                        />
                        
                        @if(!empty($filme['vote_average']))
                            <div class="absolute top-4 right-4">
                                <div class="flex items-center gap-1 px-3 py-2 rounded-full bg-black/80 backdrop-blur-md border border-yellow-500/30 shadow-lg">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-white font-bold text-sm">
                                        {{ number_format($filme['vote_average'], 1) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-tight drop-shadow-lg">
                        {{ $filme['title'] ?? $filme['name'] }}
                        @if(!empty($filme['release_date']))
                            <span class="text-gray-400 text-3xl font-bold opacity-60 ml-2">
                                ({{ substr($filme['release_date'], 0, 4) }})
                            </span>
                        @endif
                    </h1>
                    @if(!empty($filme['tagline']))
                        <p class="text-gray-400 text-lg italic mt-2 font-medium">"{{ $filme['tagline'] }}"</p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                    @if(!empty($filme['release_date']))
                        <span class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-800/60 backdrop-blur border border-white/10 text-gray-200 text-sm font-medium">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($filme['release_date'])->format('d/m/Y') }}
                        </span>
                    @endif

                    @if(!empty($filme['runtime']))
                        <span class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-800/60 backdrop-blur border border-white/10 text-gray-200 text-sm font-medium">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ intdiv($filme['runtime'], 60) }}h {{ $filme['runtime'] % 60 }}m
                        </span>
                    @endif

                    @if(!empty($filme['genres']))
                        @foreach($filme['genres'] as $genre)
                            <span class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm font-medium cursor-default">
                                {{ $genre['name'] }}
                            </span>
                        @endforeach
                    @endif
                </div>

                <div class="luv-card rounded-2xl p-5 bg-gray-800/40 border border-white/5 backdrop-blur-sm mt-2">
                    <div class="flex flex-col xl:flex-row items-center justify-between gap-6">
                        
                        <div class="flex items-center gap-4">
                            @auth
                                <div class="flex flex-col items-center gap-1">
                                    <button type="button" id="likeBtn"
                                            data-url="{{ route('filme.like.toggle', $filme['id']) }}"
                                            data-liked="{{ $likeExists ? 'true' : 'false' }}"
                                            class="cursor-pointer group relative inline-flex items-center justify-center w-12 h-12 rounded-full border border-emerald-500/30 bg-emerald-500/5 text-emerald-400 hover:text-emerald-300 hover:border-emerald-400 hover:bg-emerald-500/10 transition-all {{ $likeExists ? 'liked ring-2 ring-emerald-500/20' : '' }}"
                                            title="Favoritar">
                                        @if($likeExists)
                                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"/></svg>
                                        @else
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"/></svg>
                                        @endif
                                    </button>
                                    <span id="like-count-display" class="text-[10px] uppercase font-bold tracking-wide text-gray-400">{{ $likeCount }}</span>
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <form id="watchlistForm" action="{{ route('filme.watchlist.toggle', $filme['id']) }}" method="POST">
                                        @csrf
                                        <button type="submit" id="watchlistBtn"
                                                class="group relative inline-flex items-center justify-center w-12 h-12 rounded-full border border-rose-500/30 bg-rose-500/5 text-rose-400 hover:text-rose-300 hover:border-rose-400 hover:bg-rose-500/10 transition-all {{ $watchlistExists ? 'watchlisted ring-2 ring-rose-500/20' : '' }}"
                                                title="Salvar na sua lista">
                                            @if($watchlistExists)
                                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3a2 2 0 0 0-2 2v16l9-4 9 4V5a2 2 0 0 0-2-2H5z"/></svg>
                                            @else
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 3a2 2 0 0 0-2 2v16l9-4 9 4V5a2 2 0 0 0-2-2H5z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <span class="text-[10px] uppercase font-bold tracking-wide text-gray-400">Ver depois</span>
                                </div>

                                <div class="flex flex-col items-center gap-1 relative" id="add-to-list-container">
                                    <button type="button" id="listDropdownBtn" class="group relative inline-flex items-center justify-center w-12 h-12 rounded-full border border-blue-500/30 bg-blue-500/5 text-blue-400 hover:text-blue-300 hover:border-blue-400 hover:bg-blue-500/10 transition-all" title="Adicionar à uma lista">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                    <span class="text-[10px] uppercase font-bold tracking-wide text-gray-400">Listas</span>

                                    <div id="listDropdownMenu" class="hidden absolute z-50 bottom-16 left-1/2 -translate-x-1/2 w-64 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl p-3 max-h-60 overflow-y-auto ring-1 ring-white/10">
                                        @if($userLists->isEmpty())
                                            <div class="p-2 text-gray-400 text-sm text-center">
                                                Sem listas. <a href="{{ route('lists.create') }}" class="text-rose-400 hover:underline">Criar uma!</a>
                                            </div>
                                        @else
                                            <div class="text-gray-400 text-xs font-bold uppercase tracking-wider px-2 pb-2 mb-1 border-b border-gray-700">Adicionar em:</div>
                                            @foreach($userLists as $list)
                                                <button type="button" 
                                                        class="add-to-list-btn w-full text-left px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-all text-sm flex items-center gap-2 group"
                                                        data-list-id="{{ $list->id }}"
                                                        data-list-name="{{ $list->name }}">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 group-hover:scale-125 transition-transform"></span>
                                                    {{ $list->name }}
                                                </button>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endauth

                            @guest
                                <button onclick="window.location.href='{{ route('login.form') }}'" class="group w-12 h-12 rounded-full border border-emerald-500/30 flex items-center justify-center text-emerald-400 hover:bg-emerald-500/10 transition-all"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"/></svg></button>
                                <button onclick="window.location.href='{{ route('login.form') }}'" class="group w-12 h-12 rounded-full border border-rose-500/30 flex items-center justify-center text-rose-400 hover:bg-rose-500/10 transition-all"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 3a2 2 0 0 0-2 2v16l9-4 9 4V5a2 2 0 0 0-2-2H5z"/></svg></button>
                                <button onclick="window.location.href='{{ route('login.form') }}'" class="group w-12 h-12 rounded-full border border-blue-500/30 flex items-center justify-center text-blue-400 hover:bg-blue-500/10 transition-all"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>
                            @endguest
                        </div>

                        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-center sm:justify-end">
                            <a href="#reviews-section" onclick="scrollToReviews()" class="flex-1 sm:flex-none text-center luv-btn px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-rose-900/20 flex items-center justify-center gap-2 hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Avaliar
                            </a>
                            <a target="_blank" rel="noopener"
                               href="https://www.youtube.com/results?search_query={{ urlencode(($filme['title'] ?? $filme['name']).' trailer') }}"
                               class="flex-1 sm:flex-none text-center luv-btn px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-rose-900/20 flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                Trailer
                            </a>
                        </div>
                    </div>
                </div>

                @if(!empty($filme['overview']))
                    <div class="mt-4">
                        <h3 class="text-xl font-bold text-white mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Sinopse
                        </h3>
                        <div id="movie-description" class="text-gray-300 text-lg leading-relaxed text-justify overflow-hidden transition-all duration-500 ease-in-out line-clamp-4">
                            {{ $filme['overview'] }}
                        </div>
                        <button id="toggle-description" class="mt-3 text-gray-400 hover:text-gray-300 font-medium text-sm flex items-center gap-1 transition-colors">
                            <span id="toggle-text">Ler mais</span>
                            <svg id="toggle-icon" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
    <section id="reviews-section" class="py-10 lg:py-14">
        <div class="max-w-3xl mx-auto text-center mb-10">
            <div class="flex items-center justify-center gap-3">
                <h2 class="text-3xl md:text-4xl font-titulo font-bold text-white mb-8">Reviews</h2>
            </div>
            <p class="text-gray-400 mt-2">Compartilhe sua opinião e veja o que a comunidade pensa.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('filme.review.add', $filme['id']) }}" method="POST" class="mb-12 luv-card rounded-2xl p-6">
            @csrf
            <div class="mb-6">
                <label for="review" class="block mb-3 text-sm font-medium text-gray-300">Compartilhe sua opinião</label>
                <textarea id="review" name="review" rows="4" required
                    class="w-full px-4 py-3 text-sm text-gray-200 bg-transparent border rounded-lg placeholder-gray-400 transition-all duration-200 resize-none 
                    @error('review') border-red-500 focus:outline-none @else border-gray-700 focus:outline-none @enderror"
                    placeholder="O que você achou deste filme?">{{ old('review') }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center">
                    <span class="mr-3 text-sm font-medium text-gray-300">Avaliação:</span>
                    <div class="rating">
                        @php $reviewId = uniqid('r'); @endphp
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star-{{ $reviewId }}-{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                            <label for="star-{{ $reviewId }}-{{ $i }}" title="{{ $i }} estrelas" aria-label="{{ $i }} estrelas">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                </div>
            </div>

            <button type="submit" class="luv-btn px-6 py-2.5 text-sm font-semibold rounded-lg mt-4">
                Publicar review
            </button>
        </form>
        <div class="space-y-6 max-w-3xl mx-auto">
            @forelse($reviews as $review)
                <div class="luv-card rounded-xl p-6 border border-white/5 review-card">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if($review->user->profile_picture)
                                <img src="{{ $review->user->profile_picture_url }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <a href="{{ route('profile.show', $review->user->arroba) }}" class="text-white font-semibold hover:text-blue-400">
                                        {{ $review->user->name }}
                                    </a>
                                    <p class="text-gray-400 text-sm">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                                
                                @if($review->rating)
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                @endif
                            </div>
                            
                            <p class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $review->review }}</p>
                            @auth
                                <button onclick="toggleReplyForm({{ $review->id }})" class="mt-3 text-sm text-blue-400 hover:text-blue-300 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Responder
                                </button>
                            @endauth

                            @auth
                                <div id="reply-form-{{ $review->id }}" class="mt-4 hidden">

                                    <form method="POST" action="{{ route('filme.review.reply', ['tmdbId' => $filme['id'], 'reviewId' => $review->id]) }}" class="space-y-3">
                                        @csrf
                                        <textarea 
                                            name="review" 
                                            rows="3" 
                                            class="w-full px-4 py-3 text-sm text-gray-200 focus:outline-none bg-gray-800/50 border border-gray-700 rounded-lg  placeholder-gray-400 transition-all duration-200 resize-none" 
                                            placeholder="Escreva sua resposta..."
                                            required
                                        ></textarea>
                                        <div class="flex gap-2">
                                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-sm font-medium rounded-lg transition-colors">
                                                Enviar resposta
                                            </button>
                                            <button type="button" onclick="toggleReplyForm({{ $review->id }})" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>

                    @if ($review->replies->isNotEmpty())
                        <div class="mt-4 ml-14 pl-4 border-l-2 border-purple-500/30 space-y-3">
                            @foreach ($review->replies as $reply)
                                <div class="bg-gray-800/30 rounded-lg p-4 border border-gray-700/50">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            @if($reply->user->profile_picture)
                                                <img src="{{ $reply->user->profile_picture_url }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white text-xs font-bold">
{{ strtoupper(substr($reply->user->name, 0, 1)) }}
</div>
@endif
</div>
                                      <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <a href="{{ route('profile.show', $reply->user->arroba) }}" class="text-white font-medium text-sm hover:text-blue-400">
                                                {{ $reply->user->name }}
                                            </a>
                                            <span class="text-gray-500 text-xs">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $reply->review }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-gray-400">Nenhuma review ainda. Seja o primeiro a avaliar!</p>
            </div>
        @endforelse
    </div>
</section>

</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js" defer></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" defer></script>
<script>
    function scrollToReviews() {
        document.getElementById('reviews-section').scrollIntoView({
            behavior: 'smooth'
        });
    }
    
    function toggleReplyForm(reviewId) {
        const form = document.getElementById(`reply-form-${reviewId}`);
        if (form) {
            form.classList.toggle('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('toggle-description');
        const desc = document.getElementById('movie-description');
        const icon = document.getElementById('toggle-icon');
        const btnText = document.getElementById('toggle-text');

        if (btn && desc && icon && btnText) {
            requestAnimationFrame(() => requestAnimationFrame(() => {
                const hadClamp = desc.classList.contains('line-clamp-4');
                if (hadClamp) desc.classList.remove('line-clamp-4');
                const fullHeight = desc.scrollHeight;
                if (hadClamp) desc.classList.add('line-clamp-4');
                const clampedHeight = desc.clientHeight;

                if (fullHeight <= clampedHeight) {
                    btn.style.display = 'none';
                    desc.classList.remove('line-clamp-4');
                } else {
                    btn.style.display = 'flex';
                    btn.addEventListener('click', () => {
                        const isClamped = desc.classList.contains('line-clamp-4');
                        if (isClamped) {
                            desc.classList.remove('line-clamp-4');
                            btnText.textContent = 'Ler menos';
                            icon.classList.add('rotate-180');
                        } else {
                            desc.classList.add('line-clamp-4');
                            btnText.textContent = 'Ler mais';
                            icon.classList.remove('rotate-180');
                            desc.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    });
                }
            }));
        }

        const listContainer = document.getElementById('add-to-list-container');
        const listBtn = document.getElementById('listDropdownBtn');
        const listMenu = document.getElementById('listDropdownMenu');
        
        if (listBtn && listMenu) {
            listBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                listMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!listMenu.classList.contains('hidden') && !listContainer.contains(e.target)) {
                    listMenu.classList.add('hidden');
                }
            });

            listMenu.addEventListener('click', (e) => {
                const addBtn = e.target.closest('.add-to-list-btn');
                if (!addBtn) return;

                const listId = addBtn.dataset.listId;
                const listName = addBtn.dataset.listName;
                
                addBtn.disabled = true;
                const originalHtml = addBtn.innerHTML;
                addBtn.innerHTML = '<span class="text-xs">Adicionando...</span>';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/lists/${listId}/add`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        media_type: 'movie',
                        media_id: {{ $filme['id'] }},
                        title: @json($filme['title'] ?? $filme['name']),
                        poster_path: '{{ $filme['poster_path'] ? 'https://image.tmdb.org/t/p/w500/'.$filme['poster_path'] : '' }}'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        addBtn.innerHTML = '<span class="text-xs text-emerald-400">Adicionado!</span>';
                    } else {
                        addBtn.innerHTML = '<span class="text-xs text-red-400">Erro</span>';
                    }
                    setTimeout(() => {
                        listMenu.classList.add('hidden');
                        addBtn.disabled = false;
                        addBtn.innerHTML = originalHtml; 
                    }, 1500);
                })
                .catch(err => {
                    console.error('Erro ao adicionar à lista:', err);
                    addBtn.innerHTML = '<span class="text-xs text-red-400">Erro</span>';
                    setTimeout(() => {
                        listMenu.classList.add('hidden');
                        addBtn.disabled = false;
                        addBtn.innerHTML = originalHtml;
                    }, 1500);
                });
            });
        }

    });
</script>
@endsection
