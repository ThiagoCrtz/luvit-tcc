<?php
namespace App\Http\Controllers;

use App\Services\GameService;
use App\Services\LikeService;
use App\Services\ReviewService;
use App\Services\WatchlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameActionController extends Controller
{
    public function __construct(
        private GameService $games,
        private LikeService $likes,
        private ReviewService $reviews,
        private WatchlistService $watchlists
    ) {}

    public function toggleLike(Request $request, int $rawgId)
    {
        if (!Auth::check()) {
            return $request->wantsJson()
                ? response()->json(['error' => 'Unauthorized'], 401)
                : redirect()->route('login');
        }

        $game = $this->games->createOrUpdateGame($rawgId);
        $result = $this->likes->toggle($game, Auth::id());

        return $request->wantsJson()
            ? response()->json([
                'success' => true,
                'added' => $result['liked'],
                'count' => $result['likeCount'],
                'liked' => $result['liked'] ?? false,
                'likeCount' => $result['likeCount'] ?? 0
            ])
            : back()->with('success', $result['liked'] ? 'Like adicionado' : 'Like removido');
    }

    public function review(Request $request, int $rawgId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'review' => 'required|string|min:4|max:1000',
            'rating' => 'nullable|integer|between:1,5',
            'parent_id' => 'nullable|integer|exists:table_reviews,id',
        ], [
            'review.required' => 'Por favor, escreva sua opinião sobre o jogo.',
            'review.min' => 'Sua review deve ter pelo menos 4 caracteres.',
            'review.max' => 'Sua review não pode ter mais de 1000 caracteres.',
            'rating.between' => 'A avaliação deve ser entre 1 e 5 estrelas.',
        ]);

        $game = $this->games->createOrUpdateGame($rawgId);

        $this->reviews->add(
            $game,
            Auth::id(),
            $validated['review'],
            $validated['rating'] ?? null,
            $validated['parent_id'] ?? null
        );

        $msg = isset($validated['parent_id'])
            ? 'Resposta adicionada com sucesso!'
            : 'Review adicionada com sucesso!';

        return redirect()->back()->with('success', $msg);
    }

    public function reply(Request $request, int $rawgId, int $reviewId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
        'review' => 'required|string|min:1|max:1000',
        ]);

        $game = $this->games->createOrUpdateGame($rawgId);

        $this->reviews->add(
            $game,
            Auth::id(),
            $validated['review'],
            null,
            $reviewId
        );

        return back()->with('success', 'Resposta adicionada com sucesso!');
    }

    public function toggleWatchlist(Request $request, int $rawgId)
    {
        if (!Auth::check()) {
            return $request->wantsJson()
                ? response()->json(['error' => 'Unauthorized'], 401)
                : redirect()->route('login');
        }

        $game = $this->games->createOrUpdateGame($rawgId);
        $result = $this->watchlists->toggle($game, Auth::id());

        return $request->wantsJson()
            ? response()->json([
                'inWatchlist' => $result['added'] ?? false,
                'added' => $result['added'] ?? false
            ])
            : back()->with('success', $result['added'] ? 'Adicionado à watchlist' : 'Removido da watchlist');
    }
}
