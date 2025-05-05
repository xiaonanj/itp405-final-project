@extends('layout')

@section('title', 'My Rounds')

@section('main')
  <h1 class="mb-4">My Archery Rounds</h1>

  <form method="GET" class="mb-4 row g-2 align-items-end" id="filterForm">
  <div class="col-auto">
    <label class="form-label" for="bow_type">Bow Type</label>
    <select name="bow_type" id="bow_type" class="form-select" onchange="document.getElementById('filterForm').submit()">
      <option value="">All</option>
      <option value="barebow" {{ request('bow_type') == 'barebow' ? 'selected' : '' }}>Barebow</option>
      <option value="recurve" {{ request('bow_type') == 'recurve' ? 'selected' : '' }}>Recurve</option>
      <option value="compound" {{ request('bow_type') == 'compound' ? 'selected' : '' }}>Compound</option>
    </select>
  </div>

  <div class="col-auto">
    <label class="form-label" for="target_distance">Distance</label>
    <select name="target_distance" id="target_distance" class="form-select" onchange="document.getElementById('filterForm').submit()">
      <option value="">All</option>
      <option value="18" {{ request('target_distance') == '18' ? 'selected' : '' }}>18m</option>
      <option value="30" {{ request('target_distance') == '30' ? 'selected' : '' }}>30m</option>
      <option value="50" {{ request('target_distance') == '50' ? 'selected' : '' }}>50m</option>
    </select>
  </div>

  <div class="col-auto">
    <label class="form-label" for="is_outdoor">Type</label>
    <select name="is_outdoor" id="is_outdoor" class="form-select" onchange="document.getElementById('filterForm').submit()">
      <option value="">All</option>
      <option value="1" {{ request('is_outdoor') == '1' ? 'selected' : '' }}>Outdoor</option>
      <option value="0" {{ request('is_outdoor') == '0' ? 'selected' : '' }}>Indoor</option>
    </select>
  </div>

  <div class="col-auto">
    <label class="form-label" for="sort">Sort by Date</label>
    <select name="sort" id="sort" class="form-select" onchange="document.getElementById('filterForm').submit()">
      <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Newest First</option>
      <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest First</option>
    </select>
  </div>
  <div class="col-auto">
  <a href="{{ route('rounds.index') }}" class="btn btn-secondary">Clear Filters</a>
</div>

</form>

  @if ($rounds->isEmpty())
    <p>You haven't created any rounds yet. <a href="{{ route('rounds.create') }}">Create one now</a>.</p>
  @else


    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Indoor/Outdoor</th>
            <th>Distance</th>
            <th>Bow Type</th>
            <th>Session Type</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($rounds as $round)
        <tr>
            <td>{{ $round->created_at->format('Y-m-d') }}</td>
            <td>{{ $round->is_outdoor ? 'Outdoor' : 'Indoor' }}</td>
            <td>{{ $round->target_distance }}m</td>
            <td>{{ ucfirst($round->bow_type) }}</td>
            <td>{{ ucfirst($round->session_type) }}</td>
            <td>{{ $round->location }}</td>
            <td>
                <div class="d-flex flex-wrap gap-1">
                    <a href="{{ route('scores.create', $round) }}" class="btn btn-sm btn-outline-primary">Edit Scores</a>
                    <a href="{{ route('rounds.show', $round) }}" class="btn btn-sm btn-outline-secondary">View</a>

                    <form method="POST" action="{{ route('favorites.toggle', $round) }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-warning">
                        {{ auth()->user()->favoriteRounds->contains($round) ? '★ Unfavorite' : '☆ Favorite' }}
                    </button>
                    </form>

                    <form method="POST" action="{{ route('rounds.destroy', $round) }}" onsubmit="return confirm('Are you sure you want to delete this round?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>

                </div>
            </td>
          </tr>

        @endforeach
        </tbody>

    </table>
  @endif
  <a href="{{ route('rounds.create') }}" class="btn btn-primary mb-3">+ Create New Round</a>

@endsection
