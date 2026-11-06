@extends('layouts.app')

@section('title', 'Seguindo - ' . $user->name)

@section('content')
<div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('profile.show', $user->arroba) }}" class="text-gray-400 hover:text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar ao perfil
        </a>
        <h1 class="text-3xl font-bold text-white">{{ $user->name }} está seguindo</h1>
        <p class="text-gray-400 mt-2">{{ $following->total() }} {{ $following->total() == 1 ? 'pessoa' : 'pessoas' }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($following as $followedUser)
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.show', $followedUser->arroba) }}">
                        @if($followedUser->profile_picture)
                            <img src="{{ $followedUser->profile_picture_url }}" 
                                 class="w-12 h-12 rounded-full object-cover"
                                 alt="{{ $followedUser->name }}">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($followedUser->name, 0, 1)) }}
                            </div>
                        @endif
                    </a>
                    <div>
                        <a href="{{ route('profile.show', $followedUser->arroba) }}" class="text-white font-semibold hover:text-gray-400">
                            {{ $followedUser->name }}
                        </a>
                        <p class="text-sm text-gray-400">{{ '@' . $followedUser->arroba }}</p>
                    </div>
                </div>

                @auth
                    @if(auth()->id() !== $followedUser->id)
                        <button 
                            class="mini-follow-btn px-4 py-2 cursor-pointer rounded-lg text-sm font-medium transition-colors {{ auth()->user()->isFollowing($followedUser->id) ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-rose-600 hover:bg-rose-700 text-white' }}"
                            data-arroba="{{ $followedUser->arroba }}"
                            data-following="{{ auth()->user()->isFollowing($followedUser->id) ? 'true' : 'false' }}">
                            {{ auth()->user()->isFollowing($followedUser->id) ? 'Seguindo' : 'Seguir' }}
                        </button>
                    @endif
                @endauth
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-400">Ainda não segue ninguém.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $following->links() }}
    </div>
</div>
@endsection

@section('scripts')
	<script>
    	
      	
document.addEventListener("DOMContentLoaded", function () {
    const followButtons = document.querySelectorAll(
        "#followBtn, .mini-follow-btn"
    );

    followButtons.forEach((followBtn) => {
        followBtn.addEventListener("click", async function () {
            const arroba = this.dataset.arroba;

            this.disabled = true; 

            try {
                const response = await fetch(`/profile/${arroba}/follow`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                    },
                });

                const data = await response.json();

                if (data.success) {
                    const isFollowing = data.isFollowing;

                    this.dataset.following = isFollowing ? "true" : "false";

                    if (this.classList.contains("mini-follow-btn")) {
                        this.textContent = isFollowing ? "Seguindo" : "Seguir";

                        if (isFollowing) {
                            this.classList.remove(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                            this.classList.add(
                                "bg-gray-700",
                                "hover:bg-gray-600"
                            );
                        } else {
                            this.classList.remove(
                                "bg-gray-700",
                                "hover:bg-gray-600"
                            );
                            this.classList.add(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                        }
                    } else {
                        const followText = this.querySelector(".follow-text");
                        if (followText) {
                            followText.textContent = isFollowing
                                ? "Seguindo"
                                : "Seguir";
                        }

                        if (isFollowing) {
                            this.classList.remove(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                            this.classList.add(
                                "bg-gray-700",
                                "hover:bg-gray-600",
                                "border-gray-600"
                            );
                        } else {
                            this.classList.remove(
                                "bg-gray-700",
                                "hover:bg-gray-600",
                                "border-gray-600"
                            );
                            this.classList.add(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                        }

                        const followersCount =
                            document.getElementById("followersCount");
                        if (followersCount) {
                            followersCount.textContent = data.followersCount;
                        }
                    }
                }
            } catch (error) {
                console.error("Erro:", error);
                alert("Ocorreu um erro. Tente novamente.");
            } finally {
                this.disabled = false;
            }
        });
    });
});

      
	</script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection