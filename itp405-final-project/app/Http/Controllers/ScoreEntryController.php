<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Round;
use App\Models\ScoreEntry;
use App\Models\Comment;

class ScoreEntryController extends Controller
{
    public function create(Round $round)
    {
        $entries = $round->scoreEntries()->orderBy('end_number')->get();
        $comment = $round->comments()->where('user_id', auth()->id())->first();
    
        $existingData = $entries->map(function ($entry) use ($round) {
            $scores = [];
            for ($i = 1; $i <= $round->arrows_per_end; $i++) {
                $score = $entry->{"arrow{$i}_score"};
                if ($score === 0) {
                    $score = 'M';
                }
                $scores[] = $score;
            }
            return $scores;
        });
    
        return view('scores.create', [
            'round' => $round,
            'entries' => $entries,
            'comment' => $comment,
            'existingData' => $existingData->toJson()
        ]);
    }
    

    public function store(Request $request, Round $round)
    {
        $request->validate([
            'scores_json' => 'required|json',
        ]);
    
        $ends = json_decode($request->input('scores_json'), true);
    
        foreach ($ends as $index => $arrowScores) {
            if (count($arrowScores) !== $round->arrows_per_end) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "End #" . ($index + 1) . " must have exactly {$round->arrows_per_end} arrows.");
            }
        }
    
        $round->scoreEntries()->delete();
    
        foreach ($ends as $index => $arrowScores) {
            $data = [
                'round_id' => $round->id,
                'end_number' => $index + 1,
            ];
    
            for ($i = 0; $i < count($arrowScores); $i++) {
                $score = $arrowScores[$i];
                if ($score === 'X') $score = 10;
                if ($score === 'M') $score = 0;
                $data["arrow" . ($i + 1) . "_score"] = (int) $score;
            }
    
            ScoreEntry::create($data);
        }
        return redirect()->route('rounds.show', $round)->with('success', 'Scores updated!');
    }
    
}
