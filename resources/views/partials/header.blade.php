<nav class="fixed top-0 inset-x-0 z-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mt-3 glass-nav rounded-2xl px-4 py-3 flex flex-wrap items-center justify-between shadow-2xl">
      <a href="/" class="flex items-center gap-3 group">
        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-500 via-pink-500 to-purple-600 grid place-items-center text-white font-black ring-2 ring-white/20 group-hover:ring-rose-400/50 transition-all duration-300">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
        </div>
        <span class="text-xl md:text-2xl font-extrabold tracking-tight">
          <span class="bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Luvit</span>
          <span class="text-rose-400 animate-pulse">•</span>
        </span>
      </a>

      <ul class="hidden lg:flex items-center gap-8 text-sm font-medium">
        <li>
          <a class="nav-link relative text-white/80 hover:text-white transition-all duration-300 px-3 py-2 rounded-lg hover:bg-white/5" href="/">
            <span class="relative z-10">Início</span>
          </a>
        </li>
        <li>
          <a class="nav-link relative text-white/80 hover:text-white transition-all duration-300 px-3 py-2 rounded-lg hover:bg-white/5" href="/lists">
            <span class="relative z-10">Listas</span>
          </a>
        </li>
        <li>
          <a class="nav-link relative text-white/80 hover:text-white transition-all duration-300 px-3 py-2 rounded-lg hover:bg-white/5" 
            href="{{ route('filmes.home') }}">
            <span class="relative z-10">Filmes</span>
          </a>
        </li>
        <li>
          <a class="nav-link relative text-white/80 hover:text-white transition-all duration-300 px-3 py-2 rounded-lg hover:bg-white/5" href="/games">
            <span class="relative z-10">Jogos</span>
          </a>
        </li>
      </ul>

      <div id="navbar-search" class="relative hidden md:block w-full md:w-80 order-last md:order-none mt-3 md:mt-0">
        <div class="relative search-wrapper">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg id="search-icon" class="h-5 w-5 text-gray-400 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <svg id="search-spinner" class="hidden h-5 w-5 text-rose-400 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
          <input 
            id="search-navbar" 
            type="text" 
            placeholder="Buscar filmes, jogos, usuários..." 
            autocomplete="off"
            class="search-input w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500/50 focus:bg-white/15 transition-all duration-300"
          />
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="button" id="mobile-menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-navigation" class="lg:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors duration-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
          @auth
          <div class="hidden sm:flex items-center gap-3">
            <span class="text-sm text-white/90 font-medium">Olá, {{ Auth::user()->name }}</span>

            <a href="{{ route('profile.show', Auth::user()->arroba) }}"
                class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-500 to-purple-600 grid place-items-center text-white text-sm font-bold overflow-hidden">
                @if (Auth::user()->profile_picture)
                <img src="{{ Auth::user()->profile_picture_url }}" 
                    alt="Avatar de {{ Auth::user()->name }}"
                    class="w-full h-full object-cover">
                @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </a>
          </div>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button class="enhancedd-btn bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-semibold px-4 py-2 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-rose-500/25 hover:-translate-y-0.5">
              Sair
            </button>
          </form>
        @else
          <a href="/login" class="enhancedd-btn bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-semibold px-4 py-2 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-rose-500/25 hover:-translate-y-0.5">
            Entrar
          </a>
          <a href="/register" class="enhancedd-btn bg-white/10 hover:bg-white/20 border border-white/20 hover:border-white/30 text-white font-semibold px-4 py-2 rounded-xl transition-all duration-300 backdrop-blur-sm hover:-translate-y-0.5">
            Criar conta
          </a>
        @endauth
      </div>
    </div>
    <div id="mobile-navigation" class="hidden lg:hidden mt-2 glass-nav rounded-2xl p-4">
      <div class="grid grid-cols-2 gap-3 text-sm text-white">
        <a href="/" class="p-2">Início</a>
        <a href="/lists" class="p-2">Listas</a>
        <a href="/filmes" class="p-2">Filmes</a>
        <a href="/games" class="p-2">Jogos</a>
        @auth
          <a href="{{ route('profile.show', auth()->user()->arroba) }}" class="p-2">Meu perfil</a>
        @endauth
      </div>
    </div>
  </div>
</nav>

<style>
.glass-nav {
  background: rgba(15, 20, 25, 0.85);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.search-input::placeholder {
  background: linear-gradient(135deg, #9ca3af, #6b7280);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.search-wrapper.loading .search-input {
  background: rgba(255, 255, 255, 0.15);
}

#search-results {
  animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.search-result-item {
  animation: fadeIn 0.15s ease-out backwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-10px); }
  to { opacity: 1; transform: translateX(0); }
}
</style>

<script>
(function() {
  const menuToggle = document.getElementById('mobile-menu-toggle');
  const mobileNavigation = document.getElementById('mobile-navigation');
  const searchContainer = document.getElementById('navbar-search');
  menuToggle?.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') !== 'true';
    menuToggle.setAttribute('aria-expanded', String(expanded));
    menuToggle.setAttribute('aria-label', expanded ? 'Fechar menu' : 'Abrir menu');
    mobileNavigation.classList.toggle('hidden', !expanded);
    searchContainer.classList.toggle('hidden', !expanded);
  });
  const searchInput = document.getElementById('search-navbar');
  const searchIcon = document.getElementById('search-icon');
  const searchSpinner = document.getElementById('search-spinner');
  const searchWrapper = searchInput?.closest('.search-wrapper');
  
  if (!searchInput) return;

  let searchTimeout;
  let abortController = null;
  const DEBOUNCE_TIME = 400;

  searchInput.addEventListener('input', function() {
    const query = this.value.trim();

    clearTimeout(searchTimeout);
    if (abortController) {
      abortController.abort();
      abortController = null;
    }

    document.getElementById('search-results')?.remove();

    searchWrapper?.classList.remove('loading');
    searchIcon?.classList.remove('hidden');
    searchSpinner?.classList.add('hidden');

    if (query.length < 2) return;

    searchWrapper?.classList.add('loading');

    searchTimeout = setTimeout(() => {
      performSearch(query);
    }, DEBOUNCE_TIME);
  });

  function performSearch(query) {
    searchIcon?.classList.add('hidden');
    searchSpinner?.classList.remove('hidden');

    abortController = new AbortController();

    fetch(`/search?query=${encodeURIComponent(query)}`, {
      signal: abortController.signal,
      headers: { 'Accept': 'application/json' }
    })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      searchWrapper?.classList.remove('loading');
      searchIcon?.classList.remove('hidden');
      searchSpinner?.classList.add('hidden');

      showResults(data);
    })
    .catch(error => {
      if (error.name === 'AbortError') return; 
      
      console.error('Search error:', error);
      searchWrapper?.classList.remove('loading');
      searchIcon?.classList.remove('hidden');
      searchSpinner?.classList.add('hidden');
      
      showResults([], true);
    });
  }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[character]));
    }

  function showResults(data, isError = false) {
    const resultsDiv = document.createElement('div');
    resultsDiv.id = 'search-results';
    resultsDiv.className = 'absolute top-full left-0 right-0 mt-2 bg-gray-900/95 backdrop-blur-xl border border-white/20 shadow-2xl rounded-xl overflow-hidden z-[60] max-h-96 overflow-y-auto';

    if (isError) {
      resultsDiv.innerHTML = `
        <div class="p-4 text-center text-red-400">
          <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Erro ao buscar. Tente novamente.
        </div>`;
    } else if (!data.length) {
      resultsDiv.innerHTML = `
        <div class="p-4 text-center text-gray-400">
          <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Nenhum resultado encontrado
        </div>`;
    } else {
      resultsDiv.innerHTML = data.map((item, index) => {
        let url = '';
        if (item.type === 'user') {
          url = `/profile/${item.arroba}`;
        } else if (item.type === 'game') {
          url = `/games/${item.id}`;
        } else {
          url = `/filme/${item.id}`;
        }

        let imageHtml = '';
        if (item.type === 'user') {
          if (item.poster_path) {
            imageHtml = `<img src="${escapeHtml(item.poster_path)}" class="w-12 h-12 rounded-full object-cover shadow-lg" alt="${escapeHtml(item.title)}"/>`;
          } else {
            imageHtml = `<div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                           ${escapeHtml(item.title.charAt(0).toUpperCase())}
                         </div>`;
          }
        } else {
          if (item.poster_path) {
            imageHtml = `<img src="${escapeHtml(item.poster_path)}" class="w-12 h-16 rounded-lg object-cover shadow-lg" alt="${escapeHtml(item.title)}" loading="lazy"/>`;
          } else {
            imageHtml = `<div class="w-12 h-16 rounded-lg bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                           <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h18M3 12h18M3 16h18"/>
                           </svg>
                         </div>`;
          }
        }

        let typeBadge = '';
        let subtitle = '';
        if (item.type === 'user') {
          typeBadge = `<span class="font-bold text-purple-400">Usuário</span>`;
          subtitle = `<div class="text-xs text-gray-400 mt-1">@${item.arroba} • ${typeBadge}</div>`;
        } else {
          typeBadge = `<span class="font-bold ${item.type === 'game' ? 'text-green-400' : 'text-blue-400'}">
                         ${item.type === 'game' ? 'Jogo' : 'Filme'}
                       </span>`;
          subtitle = `<div class="flex items-center gap-2 text-xs text-gray-400 mt-1">
                        ${item.release_date || '—'}
                        ${item.genre_ids?.length ? ` • ${item.genre_ids.slice(0,2).join(', ')}` : ''}
                        • ${typeBadge}
                      </div>`;
        }

        return `
          <a href="${url}" 
             class="search-result-item flex items-center gap-4 p-3 hover:bg-white/10 transition-colors duration-200 border-b border-white/5 last:border-b-0"
             style="animation-delay: ${index * 40}ms">
            
            <div class="flex-shrink-0">
              ${imageHtml}
            </div>

            <div class="flex-1 min-w-0">
              <div class="font-semibold text-white truncate">
                ${escapeHtml(item.title)}
              </div>
              ${subtitle}
            </div>

            ${item.vote_average && item.type !== 'user' ? 
              `<div class="flex items-center gap-1 text-xs font-bold text-yellow-400">
                 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                   <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                 </svg>
                 ${Number(item.vote_average).toFixed(1)}
               </div>` : ''
            }
          </a>`;
      }).join('');
    }

    searchWrapper?.appendChild(resultsDiv);

    const closeResults = (e) => {
      if (!searchWrapper?.contains(e.target)) {
        resultsDiv.remove();
        document.removeEventListener('click', closeResults);
      }
    };
    setTimeout(() => document.addEventListener('click', closeResults), 100);
  }

  searchInput.addEventListener('focus', () => {
    if (searchInput.value.trim().length >= 2) {
      performSearch(searchInput.value.trim());
    }
  });

  searchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.getElementById('search-results')?.remove();
      searchInput.blur();
    }
  });
})();
</script>