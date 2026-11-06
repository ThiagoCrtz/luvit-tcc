@extends('layouts.app')
@section('title', 'Sua coleção - Luvit')
@section('content')
<div class="collection-home">
<section class="collection-intro">
<div><p class="welcome-line">Olá, {{ $user->name }}</p><h1>Histórias que ficam.<br>Uma coleção sua.</h1><p>Do último filme ao próximo jogo. Guarde descobertas e encontre o que vale compartilhar.</p><div class="collection-actions"><a class="collection-primary" href="{{ route('lists.create') }}">Criar uma lista</a><a class="collection-secondary" href="{{ route('profile.show', $user->arroba) }}">Ver meu perfil</a></div></div>
<div class="discovery-tickets"><a href="{{ route('filmes.home') }}"><span>Para a próxima sessão</span><strong>Filmes</strong><span>Explore o catálogo</span></a><a href="{{ route('games.index') }}"><span>Para a próxima aventura</span><strong>Jogos</strong><span>Encontre seu próximo jogo</span></a></div>
</section>
<section class="collection-section"><div class="section-heading"><h2>Na sua estante</h2><a href="{{ route('lists.index') }}">Todas as listas</a></div><div class="shelf-grid">
@forelse($myLists as $list)
<a class="shelf-item" href="{{ route('lists.show', $list->id) }}"><div class="shelf-posters">@forelse($list->items->take(3) as $item)<img src="{{ $item->poster_path ?: asset('images/placeholder.svg') }}" alt="{{ $item->title }}" loading="lazy">@empty<span>Sua próxima descoberta começa aqui</span>@endforelse</div><div class="shelf-caption"><h3>{{ $list->name }}</h3><span>{{ $list->items_count }} itens</span></div></a>
@empty
<div class="collection-empty"><h3>Faça espaço para seus favoritos.</h3><p>Crie uma lista para organizar filmes e jogos do seu jeito.</p><a href="{{ route('lists.create') }}">Criar minha primeira lista</a></div>
@endforelse
</div></section>
<div class="community-layout"><section class="collection-section"><div class="section-heading"><h2>Entre quem você segue</h2></div>
@forelse($friendsReviews as $review)
<article class="journal-review"><a href="{{ route('profile.show', $review->user->arroba) }}">{{ $review->user->name }}</a><time>{{ $review->created_at->diffForHumans() }}</time><h3>@if($review->movie)<a href="{{ route('filmes.show', $review->movie->tmdb_id) }}">{{ $review->movie->title }}</a>@elseif($review->game)<a href="{{ route('games.show', $review->game->rawg_id) }}">{{ $review->game->name }}</a>@endif</h3>@if($review->rating)<span class="review-score">★ {{ $review->rating }}/5</span>@endif<p>{{ $review->review ?: 'Avaliou este título.' }}</p></article>
@empty
<div class="collection-empty"><h3>As boas indicações vêm de pessoas.</h3><p>Busque um nome na barra de pesquisa e siga perfis para acompanhar suas avaliações.</p></div>
@endforelse
{{ $friendsReviews->links() }}
</section><aside class="collection-section"><div class="section-heading"><h2>Outras estantes</h2></div>@forelse($communityLists as $list)<a class="community-list" href="{{ route('lists.public.show', $list->id) }}"><h3>{{ $list->name }}</h3><p>Por {{ $list->user->name }} <span>{{ $list->items_count }} itens</span></p></a>@empty<p class="muted-copy">As listas da comunidade aparecerão aqui quando outras pessoas criarem suas coleções.</p>@endforelse</aside></div>
</div>
@endsection
