@extends('layout')

@section('title', 'Edit Comment')

@section('main')
  <h1 class="mb-4">Edit Comment</h1>

  <form method="POST" action="{{ route('comments.update', [$round, $comment]) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="body" class="form-label">Comment</label>
      <textarea name="body" id="body" class="form-control" rows="4" required>{{ old('body', $comment->body) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Comment</button>
    <a href="{{ route('rounds.show', $round) }}" class="btn btn-secondary">Cancel</a>
  </form>
@endsection
