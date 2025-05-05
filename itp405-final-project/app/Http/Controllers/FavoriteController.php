<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Round;

class FavoriteController extends Controller
{
    public function toggle(Round $round)
    {
        $user = auth()->user();

        if ($user->favoriteRounds()->where('round_id', $round->id)->exists()) {
            $user->favoriteRounds()->detach($round);
            return back()->with('success', 'Removed from favorites');
        } else {
            $user->favoriteRounds()->attach($round);
            return back()->with('success', 'Added to favorites');
        }
    }

    public function index()
    {
        $favorites = auth()->user()->favoriteRounds()->with('user')->get();
        return view('favorites.index', compact('favorites'));
    }
}
