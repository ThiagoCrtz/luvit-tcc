@extends('layouts.app')
@section('title', 'Luvit — Filmes, jogos e suas coleções')
@section('content')
<div class="public-home">
<section class="public-intro" aria-labelledby="intro-title"><div><h1 id="intro-title">Seu espaço para filmes e jogos.</h1><p>Descubra títulos, compartilhe avaliações e organize o que você quer ver ou jogar.</p><div class="collection-actions"><a class="collection-primary" href="{{ route('filmes.home') }}">Explorar filmes</a><a class="collection-secondary" href="{{ route('games.index') }}">Explorar jogos</a></div></div>
@if(!empty($filmes[0]['id']))
<a class="featured-title" href="{{ route('filmes.show', $filmes[0]['id']) }}">@if(!empty($filmes[0]['backdrop_path']))<img src="https://image.tmdb.org/t/p/w780{{ $filmes[0]['backdrop_path'] }}" alt="{{ $filmes[0]['title'] }}" fetchpriority="high">@endif<div><span>Em destaque</span><h2>{{ $filmes[0]['title'] }}</h2><p>Conhecer o filme</p></div></a>
@endif
</section>
<section class="public-catalogue" aria-labelledby="catalogue-title"><div class="section-heading"><div><h2 id="catalogue-title">Explore o cinema</h2><p>Encontre uma história para a próxima sessão.</p></div><a href="{{ route('filmes.home') }}">Ver catálogo</a></div><div class="public-posters">@forelse(array_slice($filmes ?? [], 0, 6) as $filme)<a href="{{ route('filmes.show', $filme['id']) }}"><img src="{{ !empty($filme['poster_path']) ? 'https://image.tmdb.org/t/p/w342'.$filme['poster_path'] : asset('images/placeholder.svg') }}" alt="Pôster de {{ $filme['title'] }}" loading="lazy"><h3>{{ $filme['title'] }}</h3>@if(!empty($filme['release_date']))<span>{{ substr($filme['release_date'], 0, 4) }}</span>@endif</a>@empty<div class="collection-empty"><h3>O catálogo está indisponível no momento.</h3><p>Tente novamente em alguns instantes.</p></div>@endforelse</div></section>
<section class="public-organize" aria-labelledby="organize-title"><div><h2 id="organize-title">Organize suas descobertas.</h2><p>Reúna filmes e jogos em listas, salve favoritos e acompanhe as avaliações de outras pessoas.</p></div><a class="collection-primary" href="/register">Criar minha conta</a></section>
</div>
@endsection
