<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FollowController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function toggle($arroba)
    {
        $user = User::where('arroba', $arroba)->firstOrFail();
        $currentUser = auth()->user();

        if ($currentUser->id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Você não pode seguir a si mesmo'
            ], 400);
        }

        if ($currentUser->isFollowing($user->id)) {
            $currentUser->unfollow($user->id);
            $isFollowing = false;
            $message = 'Você deixou de seguir ' . $user->name;
        } else {
            $currentUser->follow($user->id);
            $isFollowing = true;
            $message = 'Você agora está seguindo ' . $user->name;
        }

        return response()->json([
            'success' => true,
            'isFollowing' => $isFollowing,
            'followersCount' => $user->followersCount(),
            'message' => $message
        ]);
    }

    public function followers($arroba)
    {
        $user = User::where('arroba', $arroba)->firstOrFail();
        $followers = $user->followers()->paginate(24);

        return view('profile.followers', compact('user', 'followers'));
    }

    public function following($arroba)
    {
        $user = User::where('arroba', $arroba)->firstOrFail();
        $following = $user->following()->paginate(24);

        return view('profile.following', compact('user', 'following'));
    }
}