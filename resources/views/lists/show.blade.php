@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-6 mb-18 p-6">
  
  <div class="mb-8">
    <div class="flex items-center justify-between mb-4">
      
      <div>
        <div class="flex items-center gap-3 mb-2">
          <a href="{{ route('lists.index') }}" class="text-gray-400 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </a>
          <h1 class="text-3xl text-white font-bold">{{ $list->name }}</h1>
        </div>
        
        <div class="ml-9 flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
            
            <a href="{{ route('profile.show', $list->user->arroba) }}" class="flex items-center gap-2 group">
                @if ($list->user->profile_picture)
                    <img src="{{ $list->user->profile_picture_url }}" 
                         class="w-6 h-6 rounded-full object-cover border border-white/20"
                         alt="{{ $list->user->name }}">
                @else
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold border border-white/20">
                        {{ strtoupper(substr($list->user->name, 0, 1)) }}
                    </div>
                @endif
                <span class="text-gray-300 text-sm group-hover:text-white transition">
                    Criado por <strong class="font-semibold">{{ $list->user->name }}</strong>
                </span>
            </a>
            
            @if($list->description)
              <span class="hidden md:inline text-gray-500 text-sm">·</span>
              <p class="text-gray-400 text-sm mt-1 md:mt-0 max-w-xl">{{ $list->description }}</p>
            @endif
        </div>
      </div>
      
      @if ($isOwner)
        <button 
            id="openAddModal" 
            class="shine enhanced-btn bg-rose-600 text-slate-200 px-5 py-2.5 rounded-xl font-semibold hover:bg-rose-700 transition flex items-center gap-2 hover:-translate-y-0.5"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar Mídia
        </button>
      @endif
    </div>

    <div class="text-sm text-gray-400 ml-9">
      <span class="font-semibold text-white">{{ $list->items->count() }}</span> itens • 
      Última atualização: {{ $list->created_at->locale('pt_BR')->diffForHumans() }}
    </div>
  </div>

  @if (session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
      {{ session('success') }}
    </div>
  @endif

  @if ($list->items->isEmpty())
    <div class="text-center py-16 bg-gray-900/50 rounded-2xl border border-white/10">
      <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
      </svg>
      <p class="text-gray-400 text-lg mb-4">Esta lista está vazia</p>
      
      @if ($isOwner)
        <button 
          onclick="document.getElementById('openAddModal').click()" 
          class="inline-block bg-rose-600 text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition"
        >
          Adicionar Primeiro Item
        </button>
      @endif
    </div>
  @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
      @foreach ($list->items as $item)
        <div class="movie-card group relative rounded-2xl overflow-hidden glass-card-subtle transition-all duration-500 hover:shadow-2xl hover:shadow-black/40" >
            
            <div class="absolute top-2 right-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              @if ($isOwner)
                  <button 
                      type="button"
                      onclick="openRemoveModal('{{ route('lists.removeItem', [$list->id, $item->id]) }}', '{{ $item->title }}')"
                      class="bg-black/50 text-white hover:bg-rose-600 hover:text-white p-2 rounded-lg transition-colors duration-200 backdrop-blur-sm shadow-lg"
                      title="Remover Item"
                  >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                  </button>
              @endif
            </div>

            <a href="{{ $item->media_type === 'movie' ? '/filme/' . $item->media_id : '/games/' . $item->media_id }}" 
               class="block relative h-full">
                <div class="aspect-[2/3] relative overflow-hidden bg-gray-800">
                <img 
                    src="{{ $item->poster_path ?: '/images/placeholder.svg' }}" 
                    alt="{{ $item->title }}"
                    class="w-full h-full object-cover transition-transform duration-700" 
                    onerror="this.src='/images/placeholder.svg'"
                >
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                    
                    <div class="transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <span class="mb-1 inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $item->media_type === 'game' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30' }}">
                            {{ $item->media_type === 'game' ? 'Jogo' : 'Filme' }}
                        </span>
                        
                        <h3 class="text-white font-bold text-sm leading-tight line-clamp-2">
                            {{ $item->title }}
                        </h3>
                    </div>
                </div>
                </div>
            </a>
        </div>
      @endforeach
    </div>
  @endif
</div>

@if ($isOwner)

<div id="removeModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeRemoveModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-900/50 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Remover Item da Lista?
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">
                                Você está prestes a remover "<strong id="item-title-placeholder" class="text-white"></strong>" da sua lista. 
                                Esta ação não pode ser desfeita.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-white/5">
                <form id="removeForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">
                        Sim, Remover
                    </button>
                </form>
                <button type="button" onclick="closeRemoveModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-600 shadow-sm px-4 py-2 bg-transparent text-base font-medium text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
  <div class="bg-gray-900/95 border border-white/10 p-6 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl text-white font-bold">Adicionar Mídia à Lista</h2>
      <button id="closeAddModal" class="text-gray-400 hover:text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    
    <div class="relative mb-4">
      <input 
        type="text" 
        id="add-search" 
        placeholder="Buscar filmes ou jogos..." 
        class="w-full p-3 pl-10 rounded-lg bg-white/10 text-white border border-white/20 outline-none focus:ring-2 focus:ring-rose-500/50"
      >
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
    </div>
    
    <div id="add-results" class="space-y-2 max-h-96 overflow-y-auto"></div>
  </div>
</div>

<style>
  .glass-card-subtle {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
  }
</style>

<script>

function openRemoveModal(actionUrl, itemTitle) {
    const modal = document.getElementById('removeModal');
    const form = document.getElementById('removeForm');
    const titlePlaceholder = document.getElementById('item-title-placeholder');

    form.action = actionUrl;
    titlePlaceholder.textContent = itemTitle;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
}

function closeRemoveModal() {
    const modal = document.getElementById('removeModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto'; 
}

document.addEventListener('keydown', function(event) {
    if(event.key === "Escape"){
        closeRemoveModal();
    }
});

const addModal = document.getElementById('addModal');
const openBtn = document.getElementById('openAddModal');
const closeBtn = document.getElementById('closeAddModal');
const addSearch = document.getElementById('add-search');
const addResults = document.getElementById('add-results');
let addTimeout;

openBtn.onclick = () => {
  addModal.classList.remove('hidden');
  addSearch.focus();
};

closeBtn.onclick = () => {
  addModal.classList.add('hidden');
  addSearch.value = '';
  addResults.innerHTML = '';
};

addModal.onclick = (e) => {
  if (e.target === addModal) {
    closeBtn.click();
  }
};

addSearch.addEventListener('input', function() {
  clearTimeout(addTimeout);
  const query = this.value.trim(); 
  addResults.innerHTML = '';
  
  if (query.length < 2) return;

  addResults.innerHTML = '<p class="text-gray-400 text-center py-4">Buscando...</p>';

  addTimeout = setTimeout(() => {
    fetch(`/search?query=${encodeURIComponent(query)}&exclude=user`)
      .then(res => res.json())
      .then(data => {
        if (!data || data.length === 0) {
          addResults.innerHTML = '<p class="text-gray-400 text-center py-4">Nenhum resultado encontrado</p>';
          return;
        }

        addResults.innerHTML = data.map(item => `
          <div class="flex items-center gap-3 bg-white/10 p-3 rounded-lg hover:bg-white/20 cursor-pointer transition" 
               data-item="${escapeHtml(JSON.stringify(item))}">
            <img 
              src="${escapeHtml(item.poster_path || '/images/placeholder.svg')}" 
              class="w-12 h-16 rounded-md object-cover"
              onerror="this.src='/images/placeholder.svg'"
            >
            <div class="flex-1">
              <p class="text-white font-semibold">${escapeHtml(item.title)}</p>
              <p class="text-xs text-gray-400 mt-1">
                ${item.release_date ? item.release_date.substring(0, 4) : 'N/A'} • 
                <span class="font-bold ${item.type === 'game' ? 'text-green-400' : 'text-blue-400'}">
                  ${item.type === 'game' ? 'Jogo' : 'Filme'}
                </span>
              </p>
            </div>
          </div>
        `).join('');
        addResults.querySelectorAll('[data-item]').forEach(row => {
          row.addEventListener('click', () => {
            const item = JSON.parse(row.dataset.item);
            addToList(item.id, item.title, item.type, item.poster_path || '');
          });
        });
      })
      .catch(error => {
        console.error('Erro:', error);
        addResults.innerHTML = '<p class="text-rose-400 text-center py-4">Erro ao buscar. Tente novamente.</p>';
      });
  }, 300);
});

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML.replaceAll('"', '&quot;').replaceAll("'", '&#39;');
}

function addToList(id, title, type, poster) {
  fetch(`/lists/{{ $list->id }}/add`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      media_id: id,
      title: title,
      media_type: type,
      poster_path: poster
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert(data.error || 'Erro ao adicionar item');
    }
  })
  .catch(error => {
    console.error('Erro:', error);
    alert('Erro ao adicionar item à lista');
  });
}
</script>
@endif

@endsection