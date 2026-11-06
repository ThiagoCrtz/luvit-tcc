
@extends('layouts.app')
@vite(['resources/js/pages/games/show.js'])
@section('title', ($game['name'] ?? 'Game') . ' - Luvit')
@section('content')
@if(($game['provider'] ?? null) === 'igdb')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-5 text-sm text-white/70">
    Dados do jogo: <a class="underline hover:text-white" href="https://www.igdb.com/" target="_blank" rel="noopener noreferrer">IGDB</a>.
</div>
@else
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-5 text-sm text-white/70">
    Dados do jogo: <a class="underline hover:text-white" href="https://rawg.io/" target="_blank" rel="noopener noreferrer">RAWG</a>.
</div>
@endif
<section class="luv-hero pt-4 pb-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-stretch">
            
            <div class="lg:col-span-1 flex justify-center lg:justify-start">
                <div class="poster-frame w-full max-w-md">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl h-full">
                        @if(!empty($game['background_image']))
                            <img src="{{ $game['background_image'] }}" alt="{{ $game['name'] }}" 
                                 class="w-full h-full object-cover object-center">
                        @else
                            <div class="w-full h-[700px] bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                                </svg>
                            </div>
                        @endif
                        
                        @if(!empty($game['rating']))
                            <div class="absolute top-4 right-4">
                                <div class="flex items-center gap-1 px-3 py-2 rounded-full bg-black/80 backdrop-blur-sm border border-white/20">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-white font-bold text-sm">
                                        {{ number_format($game['rating'], 1) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-2 space-y-6">
                <h1 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-tight">
                    {{ $game['name'] ?? 'Game' }}
                    @if(!empty($game['released']))
                        <span class="text-gray-400 text-2xl font-semibold opacity-70">
                            ({{ \Carbon\Carbon::parse($game['released'])->format('Y') }})
                        </span>
                    @endif
                </h1>
                
                <div class="flex flex-wrap gap-3 justify-start">
                    @if(!empty($game['released']))
                        <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-800/40 text-white/80">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($game['released'])->format('d/m/Y') }}
                        </span>
                    @endif
                    @if(!empty($game['playtime']))
                        <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-800/40 text-white/80">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $game['playtime'] }} horas
                        </span>
                    @endif
                    @if(!empty($game['metacritic']))
                        <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-500/20 border border-yellow-500/30 text-yellow-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Metacritic: {{ $game['metacritic'] }}
                        </span>
                    @endif
                </div>
                
                @if(!empty($game['genres']))
                    <div class="flex flex-wrap gap-2">
                        @foreach($game['genres'] as $genre)
                            <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300">
                                {{ $genre['name'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
                @if(!empty($game['platforms']))
                    <div class="flex flex-wrap gap-2">
                        @foreach($game['platforms'] as $platform)
                            <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 border border-green-500/30 text-green-300">
                                {{ $platform['platform']['name'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
                
                <div class="luv-card rounded-2xl p-5">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @auth
                                <form id="likeForm" action="{{ route('game.like.toggle', $game['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" id="likeBtn" class="cursor-pointer group relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-emerald-400/40 text-emerald-400 hover:text-emerald-300 hover:border-emerald-300 transition-all {{ $likeExists ? 'liked' : '' }}" title="Favoritar">
                                        @if($likeExists)
                                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"/></svg>
                                        @else
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"/></svg>
                                        @endif
                                        <span class="absolute -z-10 inset-0 rounded-full" style="background: radial-gradient(120px 40px at 50% 50%, rgba(16,185,129,.15), transparent 40%);"></span>
                                    </button>
                                </form>

                                <div class="text-center">
                                    <div class="like-count text-gray-300">{{ $likeCount }} Likes</div>
                                </div>

                                <form id="watchlistForm" action="{{ route('game.watchlist.toggle', $game['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" id="watchlistBtn" class="group relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-rose-400/40 text-rose-400 hover:text-rose-300 hover:border-rose-300 transition-all overflow-hidden {{ $watchlistExists ? 'watchlisted' : '' }}" title="Salvar na sua lista">
                                        @if($watchlistExists)
                                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3a2 2 0 0 0-2 2v16l9-4 9 4V5a2 2 0 0 0-2-2H5z"/></svg>
                                        @else
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 3a2 2 0 0 0-2 2v16l9-4 9 4V5a2 2 0 0 0-2-2H5z"/></svg>
                                        @endif
                                        <span class="absolute -z-10 inset-0 rounded-full opacity-0 group-hover:opacity-100 transition duration-300" style="background: radial-gradient(120px 40px at 50% 50%, rgba(239,68,68,.25), transparent 40%);"></span>
                                    </button>
                                </form>

                                <div class="relative" id="add-to-list-container">
                                    <button type="button" id="listDropdownBtn" class="group relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-blue-400/40 text-blue-400 hover:text-blue-300 hover:border-blue-300 transition-all" title="Adicionar à uma lista">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span class="absolute -z-10 inset-0 rounded-full opacity-0 group-hover:opacity-100 transition duration-300" style="background: radial-gradient(120px 40px at 50% 50%, rgba(96,165,250,.25), transparent 40%);"></span>
                                    </button>

                                    <div id="listDropdownMenu" class="hidden absolute z-50 bottom-14 -right-10 w-60 bg-gray-900 border border-gray-700 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto">
                                        @if($userLists->isEmpty())
                                            <div class="p-2 text-gray-400 text-sm">Você não tem listas. <a href="{{ route('lists.create') }}" class="text-gray-300 hover:underline">Crie uma!</a></div>
                                        @else
                                            <div class="text-white text-sm font-medium px-2 pb-1">Adicionar à lista:</div>
                                            @foreach($userLists as $list)
                                                <button type="button" 
                                                        class="add-to-list-btn block w-full text-left px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors text-sm"
                                                        data-list-id="{{ $list->id }}"
                                                        data-list-name="{{ $list->name }}">
                                                    {{ $list->name }}
                                                </button>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            @endauth

                            @guest
                                <button onclick="window.location.href='{{ route('login.form') }}'" 
                                        class="cursor-pointer group relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-emerald-400/40 text-emerald-400 hover:text-emerald-300 hover:border-emerald-300 transition-all"
                                        title="Faça login para curtir">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"/></svg>
                                </button>

                                <div class="text-center">
                                    <div class="like-count text-gray-300">{{ $likeCount }} Likes</div>
                                </div>

                                <button onclick="window.location.href='{{ route('login.form') }}'" 
                                        class="group relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-rose-400/40 text-rose-400 hover:text-rose-300 hover:border-rose-300 transition-all overflow-hidden"
                                        title="Faça login para salvar na lista">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 3a2 2 0 0 0-2 2v16l9-4 9 4V5a2 2 0 0 0-2-2H5z"/></svg>
                                    <span class="absolute -z-10 inset-0 rounded-full opacity-0 group-hover:opacity-100 transition duration-300" style="background: radial-gradient(120px 40px at 50% 50%, rgba(239,68,68,.25), transparent 40%);"></span>
                                </button>
                                
                                <button onclick="window.location.href='{{ route('login.form') }}'" 
                                        class="group relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-blue-400/40 text-blue-400 hover:text-blue-300 hover:border-blue-300 transition-all overflow-hidden"
                                        title="Faça login para adicionar à lista">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span class="absolute -z-10 inset-0 rounded-full opacity-0 group-hover:opacity-100 transition duration-300" style="background: radial-gradient(120px 40px at 50% 50%, rgba(96,165,250,.25), transparent 40%);"></span>
                                </button>
                            @endguest
                        </div> 						

                        <div class="flex flex-wrap gap-3">
                            <a href="#reviews-section" onclick="scrollToReviews()" class="luv-btn px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Avaliar Game
                            </a>
                            
                            @if(!empty($game['name']))
                                <a target="_blank" rel="noopener" href="https://www.youtube.com/results?search_query={{ urlencode($game['name'].' gameplay trailer') }}" class="luv-btn px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    Ver Gameplay
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if(!empty($game['description_raw']))
                    <div class="">
                        <h3 class="text-xl font-bold text-white mt-12">Descrição</h3>
                        <div id="game-description" class="mt-6 text-white/80 text-justify leading-relaxed tracking-wider text-base overflow-hidden transition-all duration-500 ease-in-out line-clamp-5">
                            {{ $game['description_raw'] }}
                        </div>
                        <button id="toggle-description" class="mt-4 text-blue-400 hover:text-blue-300 font-medium text-sm flex items-center gap-1">
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

        <form action="{{ route('game.review.add', $game['id']) }}" method="POST" class="mb-12 luv-card rounded-2xl p-6">
            @csrf
            <div class="mb-6">
                <label for="review" class="block mb-3 text-sm font-medium text-gray-300">Compartilhe sua opinião</label>
                <textarea 
                    name="review" 
                    id="review" 
                    rows="4"
                    class="w-full px-4 py-3 text-sm text-gray-200 bg-transparent border rounded-lg placeholder-gray-400 transition-all duration-200 resize-none 
                    @error('review') border-red-500 focus:outline-none @else border-gray-700 focus:outline-none @enderror" 
                    placeholder="O que você achou deste jogo?"
                >{{ old('review') }}</textarea>
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
            <button type="submit" class="bg-rose-600 hover:bg-rose-500 luv-btn px-6 py-2.5 text-sm font-semibold rounded-lg mt-4">
                Publicar review
            </button>
        </form>
        
        <div class="space-y-6 max-w-3xl mx-auto">
            @forelse ($reviews as $review)
                <div class="luv-card rounded-xl p-6 border border-white/5 relative">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if($review->user->profile_picture)
                                <img src="{{ $review->user->profile_picture_url }}" class="w-8 h-8 rounded-full object-cover">
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
                            
                            <p class="text-gray-200 leading-relaxed">{{ $review->review }}</p>

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

                                    <form method="POST" action="{{ route('game.review.reply', ['gameId' => $game['id'], 'reviewId' => $review->id]) }}" class="space-y-3">
                                        @csrf
                                        <textarea 
                                            name="review" 
                                            rows="3" 
                                            class="w-full px-4 py-3 text-sm text-gray-200 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none placeholder-gray-400 transition-all duration-200 resize-none" 
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
                                                <img src="{{ $reply->user->profile_picture_url }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
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
                                            <p class="text-gray-300 text-sm leading-relaxed">{{ $reply->review }}</p>
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
    const desc = document.getElementById('game-description');
    const icon = document.getElementById('toggle-icon');
    const btnText = document.getElementById('toggle-text');

    if (btn && desc && icon && btnText) {
        requestAnimationFrame(() => requestAnimationFrame(() => {
            const hadClamp = desc.classList.contains('line-clamp-5');
            if (hadClamp) desc.classList.remove('line-clamp-5');
            const fullHeight = desc.scrollHeight;
            if (hadClamp) desc.classList.add('line-clamp-5');
            const clampedHeight = desc.clientHeight;

            if (fullHeight <= clampedHeight) {
                btn.style.display = 'none';
                desc.classList.remove('line-clamp-5');
            } else {
                btn.style.display = '';
                btn.addEventListener('click', () => {
                    const isClamped = desc.classList.contains('line-clamp-5');
                    if (isClamped) {
                        desc.classList.remove('line-clamp-5');
                        btnText.textContent = 'Ler menos';
                        icon.classList.add('rotate-180');
                    } else {
                        desc.classList.add('line-clamp-5');
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
                addBtn.textContent = 'Adicionando...';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/lists/${listId}/add`, { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        media_type: 'game',
                        media_id: {{ $game['id'] }},
                        title: @json($game['name']), 
                        poster_path: '{{ $game['background_image'] ?? '' }}'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        addBtn.textContent = 'Adicionado!';
                    } else {
                        addBtn.textContent = data.error || 'Erro';
                    }
                    setTimeout(() => {
                        listMenu.classList.add('hidden');
                        addBtn.disabled = false;
                        addBtn.textContent = listName; 
                    }, 1500);
                })
                .catch(err => {
                    console.error('Erro ao adicionar à lista:', err);
                    alert('Ocorreu um erro. Tente novamente.');
                    addBtn.disabled = false;
                    addBtn.textContent = listName;
                });
            });
        }
    });
</script>
@endsection
