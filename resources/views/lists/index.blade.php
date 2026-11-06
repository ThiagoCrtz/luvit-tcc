@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-6 mb-18 p-6 relative">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl text-white font-bold">{{ isset($user) ? 'Listas de ' . $user->name : 'Minhas Listas' }}</h1>
            <p class="text-gray-400 mt-1">Organize seus filmes e jogos favoritos!</p>
        </div>
        @if(!isset($user) || auth()->id() === $user->id)
        <a 
            href="{{ route('lists.create') }}" 
            class="bg-rose-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-rose-500 transition flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Lista
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($lists->isEmpty())
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-400 text-lg mb-4">Você ainda não criou nenhuma lista</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($lists as $list)
            <div class="bg-gray-900/50 border border-white/10 rounded-2xl overflow-hidden shadow-lg relative group min-h-[260px] flex flex-col hover:border-rose-500/30 transition duration-300">

                @if(auth()->id() === $list->user_id)
                <button 
                    onclick="openDeleteModal('{{ route('lists.destroy', $list->id) }}')"
                    class="absolute top-3 right-3 z-10 text-rose-400 hover:text-rose-300 transition p-2 rounded-lg hover:bg-rose-500/10" 
                    title="Excluir lista"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                @endif

                <a href="{{ route('lists.public.show', $list->id) }}" class="block p-6 pt-4 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-3 pr-8">
                        <h2 class="text-lg font-semibold text-white transition">
                            {{ $list->name }}
                        </h2>
                    </div>

                    @if($list->description)
                        <blockquote class="text-gray-400 text-sm mb-6 pl-4 border-l-2 border-gray-700 italic line-clamp-3">
                            {{ $list->description }}
                        </blockquote>
                    @else
                        <div class="mb-6 text-sm text-transparent select-none">.</div>
                    @endif

                    <div class="mt-auto">
                        @if($list->items->isNotEmpty())
                            <div class="flex -space-x-3 overflow-hidden py-1 pl-1">
                                @foreach ($list->items->take(5) as $item)
                                <img src="{{ $item->poster_path }}" 
                                        alt="{{ $item->title }}" 
                                        class="w-14 h-20 object-cover rounded-lg border-2 border-gray-800 shadow-md transform transition hover:scale-110 hover:z-10">
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center justify-center h-20 w-full bg-white/5 border border-dashed border-gray-700 rounded-lg text-gray-500 text-xs gap-2">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>Lista vazia</span>
                            </div>
                        @endif
                    </div>
                </a>
                
                <div class="flex justify-between text-xs text-gray-500 px-6 py-3 border-t border-white/5 bg-black/20">
                    <span>{{ $list->items->count() }} itens</span>
                    <span>{{ $list->created_at->locale('pt_BR')->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeDeleteModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-900/50 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Excluir Lista
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">
                                Tem certeza que deseja excluir esta lista? Todos os itens salvos nela serão removidos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-white/5">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                        Sim, excluir
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-600 shadow-sm px-4 py-2 bg-transparent text-base font-medium text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.action = actionUrl;
        
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(event) {
        if(event.key === "Escape"){
            closeDeleteModal();
        }
    });
</script>

@endsection