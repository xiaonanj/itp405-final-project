<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WorldRankingController extends Controller
{
    public function index()
    {
        // Fetch Recurve Men rankings
        $menResponse = Http::get('https://api.worldarchery.org/v3/WORLDRANKINGS/', [
            'CatCode' => 'RM',
            'RankMax' => 10,
            'SortBy' => 'RANKING',
        ]);

        // Fetch Recurve Women rankings
        $womenResponse = Http::get('https://api.worldarchery.org/v3/WORLDRANKINGS/', [
            'CatCode' => 'RW',
            'RankMax' => 10,
            'SortBy' => 'RANKING',
        ]);

        // Decode JSON responses
        $menRankings = $menResponse->json()['items'] ?? [];
        $womenRankings = $womenResponse->json()['items'] ?? [];

        return view('world_rankings.index', compact('menRankings', 'womenRankings'));
    }
}
