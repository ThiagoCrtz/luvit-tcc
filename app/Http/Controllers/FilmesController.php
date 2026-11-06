<?php

namespace App\Http\Controllers;

use App\Services\TmdbClient;
use App\Services\MovieService;
use App\Services\LikeService;
use App\Services\WatchlistService;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\ListModel;

class FilmesController extends Controller
{
    public function __construct(
        private TmdbClient       $tmdb,
        private MovieService     $movies,
        private LikeService      $likes,
        private WatchlistService $watchlists,
        private ReviewService    $reviews
    ) {}
    public function index()
    {
        $popular = $this->tmdb->popular(page: 1);
        $filmes  = array_slice($popular['results'] ?? [], 0, 30);

        return view('index', compact('filmes'));
    }
    public function show(int $id)
    {
        $movieData = Cache::remember("tmdb_movie_details_{$id}", 1440, function () use ($id) {
            return $this->tmdb->movieDetails($id);
        });

        $movie = $this->movies->ensureFromTmdbData($movieData);
        $userId        = Auth::id();
        $likeExists    = $userId ? $this->likes->exists($movie, $userId) : false;
        $watchlistExists = $userId ? $this->watchlists->exists($movie, $userId) : false;
        $likeCount     = $this->likes->count($movie);

        $reviews = $movie->reviews()
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

        return view('filmes.show', [
            'movieData'       => $movieData,
            'filme'           => $movieData,  
            'movie'           => $movie,      
            'likeExists'      => $likeExists,
            'watchlistExists' => $watchlistExists,
            'likeCount'       => $likeCount,
            'reviews'         => $reviews,
            'userLists'       => $userLists, 
        ]);
    }
}
