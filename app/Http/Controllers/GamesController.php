<?php

namespace App\Http\Controllers;

use App\Services\RawgService;
use App\Services\GameService;
use App\Services\LikeService;
use App\Services\WatchlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\ListModel;
use App\Models\Game;

class GamesController extends Controller
{
    public function __construct(
        private RawgService $rawg,
        private GameService $games,
        private LikeService $likes,
        private WatchlistService $watchlists
    ) {}


    public function index()
    {
        $gamesData = $this->rawg->getPopularGames();
        $games = $gamesData['results'] ?? [];

        // Keep the catalogue useful during a provider outage: media already
        // saved by users remains available from the local database.
        if (empty($games)) {
            $games = Game::query()
                ->latest('updated_at')
                ->take(24)
                ->get()
                ->map(fn (Game $game) => [
                    'id' => $game->rawg_id,
                    'name' => $game->name,
                    'background_image' => $game->background_image,
                    'rating' => $game->rating,
                    'released' => optional($game->released)->format('Y-m-d'),
                ])
                ->all();
        }

        return view('games.index', [
            'games' => $games,
            'catalogueOffline' => empty($gamesData['results'] ?? []),
            'catalogueCount' => $gamesData['count'] ?? Game::count(),
            'catalogueProvider' => $gamesData['provider'] ?? 'rawg',
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate(['query' => 'required|string|min:2|max:150']);

        return response()->json($this->rawg->searchGames($validated['query']));
    }


    public function show($id)
    {
        $gameData = Cache::remember("game_{$id}_pt", 3600, function () use ($id) {
            $game = $this->rawg->getGameDetails($id);
            abort_unless(!empty($game['id']), 404, 'Jogo não encontrado ou catálogo indisponível.');
            $game = $this->translateGameData($game);
            return $game;
        });


        $game = $this->games->ensureFromRawgData($gameData);


        $userId = Auth::id();
        $likeExists = $userId ? $this->likes->exists($game, $userId) : false;
        $watchlistExists = $userId ? $this->watchlists->exists($game, $userId) : false;
        $likeCount = $this->likes->count($game);


        $reviews = $game->reviews()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        $userLists = [];
        if ($userId) {
            $userLists = ListModel::where('user_id', $userId)
                                 ->select('id', 'name')
                                 ->orderBy('name')
                                 ->get();
        }

        return view('games.show', [
            'game' => $gameData,
            'likeExists' => $likeExists,
            'watchlistExists' => $watchlistExists,
            'likeCount' => $likeCount,
            'reviews' => $reviews,
            'userLists' => $userLists, 
        ]);
    }

    private function translateGameData(array $game): array
        {
            $genreTranslations = [
                'Action' => 'Ação',
                'Adventure' => 'Aventura',
                'Shooter' => 'Tiro',
                'Puzzle' => 'Quebra-cabeça',
                'Sports' => 'Esportes',
                'Racing' => 'Corrida',
                'Strategy' => 'Estratégia',
                'Simulation' => 'Simulação',
                'Fighting' => 'Luta',
                'Platformer' => 'Plataforma',
                'Casual' => 'Casual',
                'Horror' => 'Terror',
                'Indie' => 'Independente',
                'Arcade' => 'Arcade',
                'Family' => 'Família',
                'Card' => 'Cartas',
                'Board' => 'Tabuleiro',
                'Educational' => 'Educativo',
                'Music' => 'Música',
            ];

            if (!empty($game['genres'])) {
                foreach ($game['genres'] as &$genre) {
                    $genre['name'] = $genreTranslations[$genre['name']] ?? $genre['name'];
                }
            }

        return $game;
    }
    }
