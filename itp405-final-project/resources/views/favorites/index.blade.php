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
        })->filter();

        $total = $scores->sum();
        $average = $scores->count() > 0 ? number_format($total / $scores->count(), 2) : '0.00';

        $favoritedAt = $round->pivot->created_at->format('Y-m-d');
      @endphp

      <li class="list-group-item">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong>Favorited:</strong> {{ $favoritedAt }} <br>
            <strong>Round:</strong> {{ $round->created_at->format('Y-m-d') }} |
            {{ ucfirst($round->session_type) }} |
            {{ $round->target_distance }}m {{ ucfirst($round->bow_type) }} |
            {{ $round->is_outdoor ? 'Outdoor' : 'Indoor' }} <br>
            <strong>Total:</strong> {{ $total }} |
            <strong>Average:</strong> {{ $average }}
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('rounds.show', $round) }}" class="btn btn-sm btn-outline-secondary">View</a>
            
            <form method="POST" action="{{ route('favorites.toggle', $round) }}">
              @csrf
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this round from favorites?')">
                Remove
              </button>
            </form>
          </div>
        </div>
      </li>
    @endforeach
  </ul>
@endif
@endsection
