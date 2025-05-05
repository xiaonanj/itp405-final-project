<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Round;

class RoundController extends Controller
{
    public function index(Request $request)
    {
    $query = Round::where('user_id', auth()->id());

    if ($request->filled('bow_type')) {
        $query->where('bow_type', $request->input('bow_type'));
    }

    if ($request->filled('target_distance')) {
        $query->where('target_distance', $request->input('target_distance'));
    }

    if ($request->filled('is_outdoor')) {
        $query->where('is_outdoor', $request->input('is_outdoor'));
    }

    $sortOrder = $request->input('sort', 'desc');
    $query->orderBy('created_at', $sortOrder);

    return view('rounds.index', [
        'rounds' => $query->get(),
        'filters' => $request->only(['bow_type', 'target_distance', 'is_outdoor', 'sort']),
        ]);
        
    }


    public function create()
    {
        return view('rounds.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'is_outdoor' => 'required|boolean',
        'target_distance' => 'required|in:18,30,50',
        'bow_type' => 'required|in:barebow,recurve,compound',
        'session_type' => 'required|in:practice,competition',
        'location' => 'nullable|string|max:255',
        'arrows_per_end' => 'required|in:3,6',
    ]);

    $validated['user_id'] = auth()->id();

    \App\Models\Round::create($validated);

    return redirect()->route('rounds.index')->with('success', 'Round created successfully!');
}

public function show(Round $round)
{
    $round->load('scoreEntries');

    $processedEntries = $round->scoreEntries->sortBy('end_number')->map(function ($entry) use ($round) {
        $scores = [];
        for ($i = 1; $i <= $round->arrows_per_end; $i++) {
            $score = $entry->{"arrow{$i}_score"};
            if ($score === 10 && $entry->arrow1_score === 10) {
                $score = 'X';
            } elseif ($score === 0) {
                $score = 'M';
            }
            $scores[] = $score;
        }
        return [
            'end_number' => $entry->end_number,
            'scores' => $scores,
        ];
    });

    $rawScores = [];
    $goldCount = 0;

    foreach ($round->scoreEntries as $entry) {
        for ($i = 1; $i <= $round->arrows_per_end; $i++) {
            $val = $entry->{"arrow{$i}_score"};
            if (!is_null($val)) {
                $rawScores[] = $val;
                if ($val == 10) {
                    $goldCount++;
                }
            }
        }
    }

    $total = array_sum($rawScores);
    $average = count($rawScores) ? round($total / count($rawScores), 2) : 0;

    return view('rounds.show', [
        'round' => $round,
        'entries' => $processedEntries,
        'total' => $total,
        'average' => $average,
        'goldCount' => $goldCount
    ]);
}

public function destroy(Round $round)
{
    $round->delete();

    return redirect()->route('rounds.index')->with('success', 'Round deleted successfully.');
}




}
