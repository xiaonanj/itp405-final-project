@extends('layout')

@section('title', 'Round Details')

@section('main')
  <h1 class="mb-4">Round Details</h1>

  <p>
    <strong>Date:</strong> {{ $round->created_at->format('Y-m-d') }} |
    <strong>Session:</strong> {{ ucfirst($round->session_type) }} |
    <strong>Bow:</strong> {{ ucfirst($round->bow_type) }} |
    <strong>{{ $round->is_outdoor ? 'Outdoor' : 'Indoor' }}</strong> |
    <strong>Location:</strong> {{ $round->location ?? 'N/A' }}
  </p>

  <table class="table table-bordered text-center align-middle w-auto">
    <thead>
      <tr>
        <th rowspan="2">End</th>
        <th colspan="{{ $round->arrows_per_end }}">Arrows</th>
        <th rowspan="2">Sum</th>
      </tr>
      <tr>
        @for ($i = 1; $i <= $round->arrows_per_end; $i++)
          <th>{{ $i }}</th>
        @endfor
      </tr>
    </thead>
    <tbody>
      @foreach ($entries as $entry)
        <tr>
          <td>{{ $entry['end_number'] }}</td>
          @php $sum = 0; @endphp
          @foreach ($entry['scores'] as $score)
  <td>
    {{ is_numeric($score) ? $score : '10' }}
    @php $sum += is_numeric($score) ? $score : 0; @endphp
  </td>
@endforeach


          <td><strong>{{ $sum }}</strong></td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="{{ $round->arrows_per_end + 1 }}"><strong>Total</strong></td>
        <td><strong>{{ $total }}</strong></td>
      </tr>
    </tfoot>
  </table>

  <p>
    <strong>Average:</strong> {{ $average }} |
    <strong>Golds (10s):</strong> {{ $goldCount }}
  </p>

  <form method="POST" action="{{ route('favorites.toggle', $round) }}">
    @csrf
    <button class="btn btn-outline-warning">
        {{ auth()->user()->favoriteRounds->contains($round) ? '★ Unfavorite' : '☆ Favorite' }}
    </button>
</form>

<h4 class="mt-4">Comments</h4>
@if ($round->comments->isEmpty())
  <p>No comments yet.</p>
@else
  <ul class="list-group mt-3 mb-4">
    @foreach ($round->comments->sortByDesc('created_at') as $comment)
      <li class="list-group-item">
        <strong>{{ $comment->user->name }}</strong> 
        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        <p>{{ $comment->body }}</p>

        @if (auth()->id() === $comment->user_id)
          <a href="{{ route('comments.edit', [$round, $comment]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
          <form action="{{ route('comments.destroy', [$round, $comment]) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this comment?')">Delete</button>
          </form>
        @endif
      </li>
    @endforeach
  </ul>
@endif


  <a href="{{ route('rounds.index') }}" class="btn btn-secondary mt-3">Back to Rounds</a>
@endsection
