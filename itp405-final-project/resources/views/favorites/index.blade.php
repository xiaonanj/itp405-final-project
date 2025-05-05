@extends('layout')

@section('title', 'My Favorites')

@section('main')
<h1 class="mb-4">My Favorite Rounds</h1>

@if ($favorites->isEmpty())
  <p>You haven't favorited any rounds yet. <a href="{{ route('rounds.index') }}">Browse rounds</a></p>
@else
  <ul class="list-group">
    @foreach ($favorites as $round)
      @php
        $scores = $round->scoreEntries->flatMap(function ($entry) use ($round) {
            return collect(range(1, $round->arrows_per_end))->map(function ($i) use ($entry) {
                return $entry["arrow{$i}_score"];
            });
        })->filter(); // remove nulls

        $total = $scores->sum();
        $average = $scores->count() > 0 ? number_format($total / $scores->count(), 2) : '0.00';
      @endphp

      <li class="list-group-item">
        <strong>{{ $round->created_at->format('Y-m-d') }}</strong> |
        {{ ucfirst($round->session_type) }} |
        {{ $round->target_type }}
        {{ $round->target_distance }}m {{ ucfirst($round->bow_type) }} |
        {{ $round->is_outdoor ? 'Outdoor' : 'Indoor' }} |
        Total score: {{ $total }} |
        Average score: {{ $average }}

        <a href="{{ route('rounds.show', $round) }}" class="btn btn-sm btn-outline-secondary float-end">View</a>
      </li>
    @endforeach
  </ul>
@endif
@endsection
