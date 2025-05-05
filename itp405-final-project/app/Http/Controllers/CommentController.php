<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Round;


class CommentController extends Controller
{
    public function store(Request $request, Round $round)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'round_id' => $round->id,
            'body' => $request->input('body'),
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function edit(Round $round, Comment $comment)
    {
        return view('comments.edit', compact('round', 'comment'));
    }

    public function update(Request $request, Round $round, Comment $comment)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update([
            'body' => $request->input('body'),
        ]);

        return redirect()->route('rounds.show', $round)->with('success', 'Comment updated.');
    }

    public function destroy(Round $round, Comment $comment)
    {
        $comment->delete();

        return redirect()->route('rounds.show', $round)->with('success', 'Comment deleted.');
    }
}
