<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieActionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\GameActionController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\FilmesHomeController;

Route::get('/', [FilmesController::class, 'index'])->middleware('guest')->name('home.index');
Route::get('/filme/{id}', [FilmesController::class, 'show'])->name('filmes.show');

//Filmes
    Route::get('/filmes', [FilmesHomeController::class, 'index'])->name('filmes.home');

// Games
Route::get('/games', [GamesController::class, 'index'])->name('games.index');
Route::get('/games/search', [GamesController::class, 'search'])->name('games.search');
Route::get('/games/{id}', [GamesController::class, 'show'])->name('games.show');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Auth
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Perfil (Visualização pública)
Route::get('/profile/{user:arroba}', [ProfileController::class, 'show'])->name('profile.show');

// Listas de Outros Usuários (Novas Rotas Públicas)
// Índice de todas as listas de um usuário específico
Route::get('/@{arroba}/lists', [ListsController::class, 'userLists'])->name('lists.user.index');
// Visualização de uma lista individual (o método viewPublicList não checa o Auth::id)
Route::get('/list/{listId}', [ListsController::class, 'viewPublicList'])->name('lists.public.show')->where('listId', '[0-9]+');


// =========================================================
// ROTAS AUTENTICADAS (Apenas para usuários logados)
// =========================================================

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Profile (Ações e Edição)
    Route::get('/profile/{user:arroba}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user:arroba}', [ProfileController::class, 'update'])->name('profile.update');
    
    // Follow
    Route::post('/profile/{user:arroba}/follow', [FollowController::class, 'toggle'])->name('profile.follow.toggle');
    Route::get('/profile/{user:arroba}/followers', [FollowController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{user:arroba}/following', [FollowController::class, 'following'])->name('profile.following');
    
    // Filmes (Ações)
    Route::prefix('filme/{tmdbId}')->group(function () {
        Route::post('/like', [MovieActionController::class, 'toggleLike'])->name('filme.like.toggle');
        Route::post('/review', [MovieActionController::class, 'review'])->name('filme.review.add');
        Route::post('/review/{reviewId}/reply', [MovieActionController::class, 'reply'])->name('filme.review.reply');
        Route::post('/watchlist', [MovieActionController::class, 'toggleWatchlist'])->name('filme.watchlist.toggle');
    });
    
    // Games (Ações)
    Route::prefix('game/{gameId}')->group(function () {
        Route::post('/like', [GameActionController::class, 'toggleLike'])->name('game.like.toggle');
        Route::post('/review', [GameActionController::class, 'review'])->name('game.review.add');
        Route::post('/review/{reviewId}/reply', [GameActionController::class, 'reply'])->name('game.review.reply');
        Route::post('/watchlist', [GameActionController::class, 'toggleWatchlist'])->name('game.watchlist.toggle');
    });
    
    // Listas (Ações e Visualização Privada/Edição)
    Route::prefix('lists')->group(function () {
        Route::get('/', [ListsController::class, 'index'])->name('lists.index');
        Route::get('/create', [ListsController::class, 'create'])->name('lists.create');
        Route::post('/', [ListsController::class, 'store'])->name('lists.store');
  
        Route::post('/{id}/add', [ListsController::class, 'addItem'])->name('lists.addItem');
        Route::delete('/{listId}/items/{itemId}', [ListsController::class, 'removeItem'])->name('lists.removeItem');
        Route::delete('/{id}', [ListsController::class, 'destroy'])->name('lists.destroy')->where('id', '[0-9]+');
        
        // Rota de visualização privada/edição (usa o método 'show' que checa o Auth::id)
        Route::get('/{id}', [ListsController::class, 'show'])->name('lists.show')->where('id', '[0-9]+');
    });
});
