@extends('layouts.app')

@section('title', 'Descubra filmes incríveis - Luvit')

@section('content')

<section class="relative py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-gradient-to-br from-rose-500/20 to-transparent rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-gradient-to-tl from-purple-600/15 to-transparent rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="absolute top-20 left-10 w-2 h-2 bg-rose-400 rounded-full opacity-60 animate-ping"></div>
    <div class="absolute top-40 right-20 w-1 h-1 bg-purple-400 rounded-full opacity-40 animate-pulse"></div>
    <div class="absolute bottom-32 left-1/3 w-1.5 h-1.5 bg-blue-400 rounded-full opacity-50 animate-bounce"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="space-y-6">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight">
                <span class="bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    Descubra filmes
                </span>
                <br>
                <span class="bg-gradient-to-r from-rose-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
                    incríveis!
                </span>
            </h1>
            
            <p class="text-xl md:text-2xl text-white/80 max-w-3xl mx-auto leading-relaxed">
                Explore nossa coleção curada de filmes organizados por gênero. 
                Encontre sua próxima paixão cinematográfica.
            </p>
            
            <div class="flex justify-center gap-8 md:gap-16 mt-12">
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-white">{{ count($moviesByGenre) }}</div>
                    <div class="text-sm text-white/70">Gêneros</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-white">
                        296.470+
                    </div>
                    <div class="text-sm text-white/70">Filmes</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-16 md:pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($moviesByGenre as $genero => $filmes)
            @if(count($filmes) > 0)
                <div class="md:mb-20">
                    <div class="flex items-center justify-between  mb-8">
                        <div class="space-y-2">
                            <h2 class="mt-16 text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight">
                                <span class="bg-gradient-to-r from-white via-rose-200 to-purple-200 bg-clip-text text-transparent drop-shadow-lg">
                                    {{ $genero }}
                                </span>
                            </h2>
                        </div>
                        <a href="#" onclick="focusSearch(); return false;" class="mt-16 group hidden sm:flex items-center gap-2 text-rose-400 hover:text-rose-300 font-medium transition-colors">                            Descobrir mais
                            <svg class="w-5 h-5 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a> 
                    </div>
                    
                    <div class="relative mb-8">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full h-[2px] bg-gradient-to-r from-transparent via-rose-500/40 to-transparent"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-gray-900">
                                <svg class="w-5 h-5 text-rose-500/60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="genre-carousel splide" data-genre="{{ $genero }}" aria-label="Filmes de {{ $genero }}">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($filmes as $filme)
                                    <li class="splide__slide">
                                        <a href="{{ route('filmes.show', $filme['id']) }}" class="group block relative movie-card">
                                            <div class="relative rounded-2xl overflow-hidden glass-card-subtle hover:scale-105 transition-all duration-500 hover:shadow-2xl hover:shadow-black/40" 
                                                 data-tilt data-tilt-max="4" data-tilt-speed="800">
                                                
                                                <div class="aspect-[2/3] overflow-hidden bg-gray-800">
                                                    @if(!empty($filme['poster_path']))
                                                        <img src="https://image.tmdb.org/t/p/w500{{ $filme['poster_path'] }}" 
                                                             alt="{{ $filme['title'] ?? $filme['name'] }}" 
                                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                             loading="lazy"/>
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-800">
                                                            <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                    <div class="absolute bottom-4 left-4 right-4 space-y-3 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                        <h3 class="font-black text-lg leading-tight text-white line-clamp-2 drop-shadow-2xl tracking-tight">
                                                            {{ $filme['title'] ?? $filme['name'] }}
                                                        </h3>
                                                        
                                                        <div class="flex items-center justify-between">
                                                            <div class="space-y-1">
                                                                @if(!empty($filme['vote_average']) && $filme['vote_average'] > 0)
                                                                    <div class="flex items-center gap-1">
                                                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                        </svg>
                                                                        <span class="text-sm font-semibold text-white">
                                                                            {{ number_format($filme['vote_average'], 1) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if(!empty($filme['release_date']))
                                                                    <div class="text-xs text-white/80">
                                                                        {{ \Carbon\Carbon::parse($filme['release_date'])->year }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-xl transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                                                <svg class="w-5 h-5 text-gray-900 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M8 5v10l8-5-8-5z"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        
                                                        @if(!empty($filme['main_genre']))
                                                            <div class="inline-block px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-xs font-medium">
                                                                {{ $filme['main_genre'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="relative mt-8">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full h-[1px] bg-gradient-to-r from-transparent via-purple-500/30 to-transparent"></div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-16">
                <div class="space-y-6">
                    <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Nenhum filme encontrado</h3>
                    <p class="text-white/70 max-w-md mx-auto">
                        Não conseguimos carregar os filmes no momento. Tente recarregar a página.
                    </p>
                    <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-semibold transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Recarregar
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</section>

@if(!empty($moviesByGenre))
<section class="pt-0 pb-16 md:pb-24 relative">   
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-72 h-72 bg-gradient-to-br from-rose-500/10 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-purple-600/8 to-transparent rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="glass-card rounded-3xl p-8 md:p-12 space-y-8">
            <div class="space-y-4">
                @auth
                    <h3 class="text-3xl md:text-4xl font-extrabold text-white">
                        Descobriu o que estava procurando?
                    </h3>
                    <p class="text-xl text-white/80 max-w-2xl mx-auto leading-relaxed">
                        Continue explorando nossa coleção e encontre ainda mais filmes incríveis para adicionar ao seu perfil.
                    </p>
                @else
                    <h3 class="text-3xl md:text-4xl font-extrabold text-white">
                        Encontrou seu próximo filme favorito?
                    </h3>
                    <p class="text-xl text-white/80 max-w-2xl mx-auto leading-relaxed">
                        Crie sua conta gratuita para salvar seus favoritos, fazer listas personalizadas e receber recomendações exclusivas.
                    </p>
                @endauth
            </div>
            
            <div class="flex gap-4 justify-center flex-wrap">
                @auth
                    <button onclick="focusSearch()" class="shine group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-rose-600 text-white font-semibold text-lg transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar Filmes
                    </button>
                    <a href="{{ route('profile.show', ['user' => auth()->user()->arroba]) }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold text-lg transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Meu Perfil
                    </a>
                @else
                    <a href="{{ route('register.form') }}" class="shine group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-500 text-white font-semibold text-lg transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Criar Conta Grátis
                    </a>
                    <a href="{{ route('login.form') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold text-lg transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Fazer Login
                    </a>
                @endauth
            </div>
            </div>
        </div>
    </div>
</section>
@endif

@endsection

@section('scripts')
<style>
.glass-card-subtle {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.movie-card {
    transform-origin: center;
}

.splide__arrow {
    width: 50px !important;
    height: 50px !important;
    border-radius: 50% !important;
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(10px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    opacity: 0 !important;
    transition: all 0.3s ease !important;
}

.splide:hover .splide__arrow {
    opacity: 0.8 !important;
}

.splide__arrow:hover {
    background: rgba(244, 63, 94, 0.8) !important;
    border-color: rgba(244, 63, 94, 0.5) !important;
    transform: scale(1.1) !important;
    opacity: 1 !important;
}

.splide__arrow svg {
    width: 24px !important;
    height: 24px !important;
    color: white !important;
}

.skeleton {
    background: linear-gradient(90deg, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.1) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

@media (max-width: 768px) {
    .splide__arrow {
        width: 40px !important;
        height: 40px !important;
    }
    
    .splide__arrow svg {
        width: 20px !important;
        height: 20px !important;
    }
}

@media (hover: none) {
    [data-tilt] {
        transform: none !important;
    }
}
</style>

<script>
function focusSearch() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
    setTimeout(() => {
        const searchInput = document.getElementById('search-navbar');
        if (searchInput) {
            searchInput.focus();
        }
    }, 300); 
}


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.genre-carousel').forEach(carousel => {
        const splide = new Splide(carousel, {
            type: 'loop',
            perPage: 6,
            perMove: 2,
            gap: '1.5rem',
            arrows: true,
            pagination: false,
            autoplay: false,
            pauseOnHover: true,
            speed: 600,
            lazyLoad: 'nearby',
            breakpoints: {
                1536: { perPage: 5, gap: '1.2rem' },
                1280: { perPage: 4, gap: '1rem' },
                1024: { perPage: 3, gap: '1rem' },
                768: { 
                    perPage: 2.2, 
                    gap: '0.8rem',
                    arrows: false,
                    pagination: true
                },
                640: { 
                    perPage: 1.5, 
                    gap: '0.5rem',
                    arrows: false,
                    pagination: true
                }
            }
        });
        
        splide.mount();
    });

    if (window.innerWidth > 768) {
        VanillaTilt.init(document.querySelectorAll('[data-tilt]'), {
            max: 4,
            speed: 800,
            glare: true,
            'max-glare': 0.1,
            scale: 1.02
        });
    }

    document.querySelectorAll('.movie-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });

    const images = document.querySelectorAll('img[loading="lazy"]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                
                img.classList.add('skeleton');
                
                img.addEventListener('load', () => {
                    img.classList.remove('skeleton');
                });
                
                img.addEventListener('error', () => {
                    img.classList.remove('skeleton');
                    img.style.display = 'none';
                    const placeholder = img.parentElement;
                    if (placeholder) {
                        placeholder.innerHTML = `
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-800">
                                <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"/>
                                </svg>
                            </div>
                        `;
                    }
                });
                
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
        document.documentElement.style.setProperty('--animation-duration', '0.2s');
    }
});
</script>
@endsection