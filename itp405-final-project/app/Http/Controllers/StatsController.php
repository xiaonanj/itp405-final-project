<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreEntry;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $query = ScoreEntry::whereHas('round', function ($q) use ($request) {
            $q->where('user_id', auth()->id());

            if ($request->filled('bow_type')) {
                $q->where('bow_type', $request->input('bow_type'));
            }

            if ($request->filled('target_distance')) {
                $q->where('target_distance', $request->input('target_distance'));
            }

            if ($request->filled('is_outdoor')) {
                $q->where('is_outdoor', $request->input('is_outdoor'));
            }
        });

        $entries = $query->with('round')->get();

        $allScores = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
        $scoreCounts = array_fill_keys($allScores, 0);

        $totalArrows = 0;
        $totalScore = 0;
        $goldCount = 0;
        $hitCount = 0;

        foreach ($entries as $entry) {
            $arrowsPerEnd = $entry->round->arrows_per_end;

            for ($i = 1; $i <= $arrowsPerEnd; $i++) {
                $score = $entry->{"arrow{$i}_score"};

                if (is_null($score)) continue;

                $totalArrows++;
                $totalScore += $score;

                $label = $score === 0 ? 'M' : (string)$score;

                if (isset($scoreCounts[$label])) {
                    $scoreCounts[$label]++;
                }

                if ($label === '10') {
                    $goldCount++;
                }

                if ($label !== 'M') {
                    $hitCount++;
                }
            }
        }

        $average = $totalArrows ? round($totalScore / $totalArrows, 2) : 0;

        return view('stats.index', [
            'scoreCounts' => $scoreCounts,
            'totalArrows' => $totalArrows,
            'totalScore' => $totalScore,
            'average' => $average,
            'goldCount' => $goldCount,
            'hitCount' => $hitCount,
            'filters' => $request->only(['bow_type', 'target_distance', 'is_outdoor']),
        ]);
    }
}
