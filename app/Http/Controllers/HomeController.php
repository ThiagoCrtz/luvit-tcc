<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListModel;
use App\Models\Review; 
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myLists = ListModel::where('user_id', $user->id)
            ->with('items')->withCount('items')
            ->latest()
            ->take(4)
            ->get();

        $followingIds = $user->following()->pluck('table_users.id'); 

        $friendsReviews = Review::whereIn('user_id', $followingIds)
        ->with(['user', 'game', 'movie'])
        ->latest()
        ->paginate(4); 

        $communityLists = ListModel::where('user_id', '!=', $user->id)
            ->with(['user', 'items' => function($query) {
                $query->take(3);
            }])
            ->withCount('items')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact('myLists', 'friendsReviews', 'communityLists', 'user'));
    }
}