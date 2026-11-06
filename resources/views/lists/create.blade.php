@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-6 mb-18 bg-gray-900/80 backdrop-blur-md p-8 rounded-2xl border border-white/10 shadow-2xl">
  <h1 class="text-2xl font-bold text-white mb-6">Criar Nova Lista</h1>

  <form method="POST" action="{{ route('lists.store') }}" id="create-list-form">
    @csrf

    <div class="mb-6">
      <label class="block text-white/80 mb-2 font-medium">Nome da Lista</label>
      <input
        type="text"
        name="name"
        required
        maxlength="255"
        class="w-full rounded-lg bg-white/10 text-white p-3 border border-white/20 focus:ring-2 focus:ring-rose-500/50 outline-none"
        value="{{ old('name') }}"
        placeholder="Minha listinha favorita..."
      >
      @error('name')
        <p class="text-rose-400 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="mb-6">
      <label class="block text-white/80 mb-2 font-medium">
        Descrição (<span class="italic">opcional </span>)
      </label>
      <textarea
        name="description"
        rows="3"
        maxlength="1000"
        class="w-full rounded-lg bg-white/10 text-white p-3 border border-white/20 focus:ring-2 focus:ring-rose-500/50 outline-none resize-none"
        placeholder="Adicione uma descrição para sua lista..."
      >{{ old('description') }}</textarea>
    </div>

    <div class="mb-6">
      <label class="block text-white/80 mb-2 font-medium">Pesquisar conteúdo para adicionar</label>
      <div class="relative">
        <input
          type="text"
          id="media-search"
          placeholder="Buscar filmes ou jogos..."
          class="w-full rounded-lg bg-white/10 text-white p-3 pl-10 border border-white/20 focus:ring-2 focus:ring-rose-500/50 outline-none"
          autocomplete="off"
        >
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>

      <div id="media-results" class="mt-3 space-y-2 max-h-80 overflow-y-auto"></div>
    </div>

    <div class="mb-6" id="selected-section" style="display: none;">
      <h2 class="text-white font-semibold mb-3 flex items-center gap-2">
        <svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
          <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
        </svg>
        Conteúdos selecionados (<span id="selected-count">0</span>)
      </h2>
      <ul id="selected-media" class="space-y-2"></ul>
    </div>

    <div class="flex gap-3">
      <button type="submit" id="submit-list-button"
              class="bg-rose-600 px-5 py-2.5 rounded-xl text-white hover:bg-rose-500 font-semibold transition flex-1 cursor-pointer">
        Criar Lista
      </button>
      <a href="{{ route('lists.index') }}"
         class="bg-white/10 hover:bg-white/20 border border-white/20 px-5 py-2.5 rounded-xl text-white font-semibold transition">
        Cancelar
      </a>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput      = document.getElementById('media-search');
    const resultsDiv       = document.getElementById('media-results');
    const selectedList     = document.getElementById('selected-media');
    const selectedCountEl  = document.getElementById('selected-count');
    const selectedSection  = document.getElementById('selected-section');
    const form             = document.getElementById('create-list-form');
    const submitButton     = document.getElementById('submit-list-button'); 

    const selectedItems = new Map();
    let debounceTimer = null;

    function safeTitleForValue(title) {
        return title ? String(title).trim() : 'Sem título';
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[character]));
    }

    function renderSelected() {
        selectedList.innerHTML = '';
        for (const item of selectedItems.values()) {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-3 bg-white/5 rounded p-3';
            li.innerHTML = `
                <div class="flex items-center gap-3 w-full">
                    <img src="${escapeHtml(item.poster_path || '/images/placeholder.svg')}" class="w-12 h-16 rounded-md object-cover" onerror="this.src='/images/placeholder.svg'">
                    <div class="flex-1 min-w-0">
                        <div class="text-white truncate" title="${escapeHtml(item.title)}">${escapeHtml(item.title)}</div>
                        <div class="text-xs text-gray-400 mt-1">${item.media_type === 'game' ? 'Jogo' : 'Filme'}</div>
                    </div>
                    <button type="button" data-key="${item.key}" class="text-rose-400 hover:text-rose-300 p-2 remove-selected" title="Remover">
                        ✖
                    </button>
                </div>
            `;
            selectedList.appendChild(li);
        }
        selectedCountEl.textContent = selectedItems.size;
        selectedSection.style.display = selectedItems.size ? 'block' : 'none';
    }

    selectedList.addEventListener('click', (e) => {
        const btn = e.target.closest('button.remove-selected');
        if (!btn) return;
        const key = btn.dataset.key;
        if (selectedItems.has(key)) {
            selectedItems.delete(key);
            renderSelected();
        }
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const q = searchInput.value.trim();
        if (q.length < 2) { resultsDiv.innerHTML = ''; return; }

        resultsDiv.innerHTML = '<p class="text-gray-400 text-center py-4"> Buscando...</p>';

        debounceTimer = setTimeout(() => {
            fetch(`/search?query=${encodeURIComponent(q)}&exclude=user`)
            .then(res => res.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if (!data || data.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-gray-400 text-center py-4">Nenhum resultado encontrado</p>';
                    return;
                }
                data.forEach(item => {
                    const key = `${item.type}:${item.id}`;
                    const isSelected = selectedItems.has(key);
                    const row = document.createElement('div');
                    row.className = `flex items-center justify-between p-3 bg-gray-900/70 rounded hover:bg-gray-800 transition ${isSelected ? 'opacity-60' : 'cursor-pointer'}`;
                    row.innerHTML = `
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="${escapeHtml(item.poster_path || '/images/placeholder.svg')}" class="w-12 h-16 rounded-md object-cover" onerror="this.src='/images/placeholder.svg'">
                            <div class="min-w-0">
                                <div class="text-white truncate" title="${escapeHtml(item.title)}">${escapeHtml(item.title)}</div>
                                <div class="text-xs text-gray-400 mt-1">${item.release_date ? item.release_date.substring(0,4) : 'N/A'} • <span class="font-bold ${item.type === 'game' ? 'text-green-400' : 'text-blue-400'}">${item.type === 'game' ? 'Jogo' : 'Filme'}</span></div>
                            </div>
                        </div>
                        <div class="ml-3">
                            <button type="button" data-id="${item.id}" data-type="${item.type}" data-title="${escapeHtml(item.title)}" data-poster="${escapeHtml(item.poster_path || '')}" class="select-btn text-rose-400 hover:text-rose-300 px-3 py-1 rounded cursor-pointer">
                                ${isSelected ? 'Selecionado' : 'Selecionar'}
                            </button>
                        </div>
                    `;
                    resultsDiv.appendChild(row);
                });
            })
            .catch(err => {
                console.error(err);
                resultsDiv.innerHTML = '<p class="text-rose-400 text-center py-4">Erro ao buscar.</p>';
            });
        }, 300);
    });

    resultsDiv.addEventListener('click', (e) => {
        const btn = e.target.closest('button.select-btn');
        if (!btn) return;

        const id = String(btn.dataset.id);
        const type = String(btn.dataset.type);
        const title = String(btn.dataset.title);
        const poster = String(btn.dataset.poster || '');
        const key = `${type}:${id}`;
        if (selectedItems.has(key)) return;

        selectedItems.set(key, {
            key,
            media_type: type,
            media_id: id,
            title: safeTitleForValue(title),
            poster_path: poster
        });
        renderSelected();
        btn.textContent = 'Selecionado';
    });

    form.addEventListener('submit', (e) => {
        //logica para evitar multiplos clicks
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Criando Lista...';
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.classList.remove('hover:bg-rose-500');
        }

        form.querySelectorAll('input[name="media[]"]').forEach(n => n.remove());

        selectedItems.forEach(item => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'media[]';
            input.value = JSON.stringify(item);
            form.appendChild(input);
        });
    });
});
</script>


<style>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-6px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.22s ease-out; }
</style>
@endsection
