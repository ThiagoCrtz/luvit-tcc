<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use App\Services\LikeService;
use App\Services\ReviewService;
use App\Services\WatchlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieActionController extends Controller
{
    public function __construct(
        private MovieService     $movies,
        private LikeService      $likes,
        private ReviewService    $reviews,
        private WatchlistService $watchlists
    ) {}

    public function toggleLike(Request $request, int $tmdbId)
    {
        if (!Auth::check()) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'error' => 'Unauthorized'], 401) 
                : redirect()->route('login');
        }

        $movie  = $this->movies->createOrUpdateMovie($tmdbId);
        $result = $this->likes->toggle($movie, Auth::id());
        

        $response = [
            'success' => true,
            'added' => $result['liked'],  
            'count' => $result['likeCount'] 
        ];

        return $request->wantsJson()
            ? response()->json($response)
            : back()->with('success', $result['liked'] ? 'Like adicionado' : 'Like removido');
    }

    public function review(Request $request, int $tmdbId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'review' => 'required|string|min:4|max:1000',
            'rating' => 'nullable|integer|between:1,5',
            'parent_id' => 'nullable|integer|exists:table_reviews,id',
        ], [
            'review.required' => 'Por favor, escreva sua opinião sobre o filme.',
            'review.min' => 'Seu comentário deve ter pelo menos 4 caracteres.',
            'review.max' => 'Seu comentário não pode ter mais de 1000 caracteres.',
            'rating.between' => 'A avaliação deve ser entre 1 e 5 estrelas.',
        ]);

        $movie = $this->movies->createOrUpdateMovie($tmdbId);

        $this->reviews->add(
            $movie,
            Auth::id(),
            $validated['review'],
            $validated['rating'] ?? null,
            $validated['parent_id'] ?? null
        );

        $msg = isset($validated['parent_id'])
            ? 'Resposta adicionada com sucesso!'
            : 'Comentário adicionado com sucesso!';

        return redirect()->back()->with('success', $msg);
    }

    public function reply(Request $request, int $tmdbId, int $reviewId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

         $validated = $request->validate([
        'review' => 'required|string|min:1|max:1000',
        ]);

        $movie = $this->movies->createOrUpdateMovie($tmdbId);

        $this->reviews->add(
            $movie,
            Auth::id(),
            $validated['review'],
            null,
            $reviewId
        );

        return back()->with('success', 'Resposta adicionada com sucesso!');
    }


    public function toggleWatchlist(Request $request, int $tmdbId)
    {
        if (!Auth::check()) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'error' => 'Unauthorized'], 401) 
                : redirect()->route('login');
        }

        $movie  = $this->movies->createOrUpdateMovie($tmdbId);
        $result = $this->watchlists->toggle($movie, Auth::id());

        $response = [
            'success' => true,
            'added' => $result['added'] 
        ];

        return $request->wantsJson()
            ? response()->json($response)
            : back()->with('success', $result['added'] ? 'Adicionado à watchlist' : 'Removido da watchlist');
    }
}
