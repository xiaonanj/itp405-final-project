@extends('layout')

@section('title', 'Add Scores')

@section('main')
  <h1>Add Scores</h1>
  <p>
    <strong>Date:</strong> {{ $round->created_at->format('Y-m-d') }} |
    <strong>Session:</strong> {{ ucfirst($round->session_type) }} |
    <strong>Bow type:</strong> {{ ucfirst($round->bow_type) }} |
    <strong>{{ $round->is_outdoor ? 'Outdoor' : 'Indoor' }}</strong> |
    <strong>Location:</strong> {{ $round->location ?? 'N/A' }}
  </p>

  <div class="mb-3">
    <strong>Total Score:</strong> <span id="totalScore">0</span> |
    <strong>Average:</strong> <span id="averageScore">0.00</span>
  </div>

  <div id="scoreError" class="alert alert-danger d-none"></div>
  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <form method="POST" action="{{ route('scores.store', $round) }}" id="scoreForm">
    @csrf
    <div id="scoreDisplay" class="mb-4"></div>
    <input type="hidden" name="scores_json" id="scoresJsonInput">
    <button class="btn btn-success mt-2">Submit All Scores</button>
  </form>

  <hr>

  <div class="d-flex flex-wrap gap-2 mb-3">
    @foreach (['X', 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 'M'] as $score)
      <button type="button" class="btn btn-outline-primary score-btn" data-score="{{ $score }}">{{ $score }}</button>
    @endforeach
    <button type="button" class="btn btn-outline-danger" id="undoBtn">Undo</button>
  </div>

  <hr>
  <h5>Comments</h5>

  <h4 class="mt-4">Comments</h4>
@if ($round->comments->isEmpty())
  <p>No comments yet. Be the first to comment!</p>
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

<form method="POST" action="{{ route('comments.store', $round) }}">
  @csrf
  <div class="mb-3">
    <label for="body" class="form-label">Add a comment</label>
    <textarea name="body" class="form-control" rows="3" placeholder="Write something..." required></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

  <style>
    .score-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      text-align: center;
      line-height: 40px;
      font-weight: bold;
      margin-right: 5px;
      color: white;
    }
    .selected-score {
      outline: 3px solid black;
    }
    .bg-yellow { background-color: #ffc107; }
    .bg-red { background-color: #dc3545; }
    .bg-blue { background-color: #0d6efd; }
    .bg-black { background-color: #212529; }
    .bg-white { background-color: #f8f9fa; color: black; border: 1px solid #ccc; }
    .bg-green { background-color: #28a745; }
    .bg-secondary { background-color: #6c757d; }
  </style>

  <script>
    const arrowsPerEnd = {{ $round->arrows_per_end }};
    let allEnds = {!! $existingData !!};
    let selected = null;

    function scoreToNumber(s) {
      if (s === 'X') return 10;
      if (s === 'M') return 0;
      return parseInt(s);
    }

    function getColorClass(score) {
      if (score === 'X' || score == 10 || score == 9) return 'bg-yellow';
      if (score == 8 || score == 7) return 'bg-red';
      if (score == 6 || score == 5) return 'bg-blue';
      if (score == 4 || score == 3) return 'bg-black';
      if (score == 2 || score == 1) return 'bg-white';
      if (score == 0 || score === 'M') return 'bg-green';
      return 'bg-secondary';
    }

    function updateTotals() {
      const allScores = allEnds.flat().map(scoreToNumber).filter(n => !isNaN(n));
      const total = allScores.reduce((a, b) => a + b, 0);
      const average = allScores.length ? (total / allScores.length).toFixed(2) : '0.00';
      document.getElementById('totalScore').innerText = total;
      document.getElementById('averageScore').innerText = average;
    }

    function renderScores() {
      const display = document.getElementById('scoreDisplay');
      display.innerHTML = '';

      allEnds.forEach((end, endIndex) => {
        const row = document.createElement('div');
        row.className = 'mb-2 d-flex align-items-center gap-2 flex-wrap';

        const endLabel = document.createElement('strong');
        endLabel.innerText = `${endIndex + 1}.`;
        row.appendChild(endLabel);

        let rowTotal = 0;

        end.forEach((score, arrowIndex) => {
          const colorClass = getColorClass(score);
          const div = document.createElement('div');
          div.className = `score-circle ${colorClass}`;

          if (selected && selected.endIndex === endIndex && selected.arrowIndex === arrowIndex) {
            div.classList.add('selected-score');
          }

          div.innerText = score ?? '-';
          rowTotal += scoreToNumber(score ?? '0');

          div.addEventListener('click', () => {
            selected = (selected && selected.endIndex === endIndex && selected.arrowIndex === arrowIndex)
              ? null : { endIndex, arrowIndex };
            renderScores();
          });

          row.appendChild(div);
        });

        const totalText = document.createElement('span');
        totalText.innerHTML = `<strong>= ${rowTotal}</strong>`;
        row.appendChild(totalText);

        display.appendChild(row);
      });

      document.getElementById('scoresJsonInput').value = JSON.stringify(allEnds);
      updateTotals();
    }

    function addScore(score) {
      if (selected) {
        allEnds[selected.endIndex][selected.arrowIndex] = score;
        selected = null;
      } else {
        if (!allEnds.length || allEnds[allEnds.length - 1].length === arrowsPerEnd) {
          allEnds.push([]);
        }
        allEnds[allEnds.length - 1].push(score);
      }
      renderScores();
    }

    function undoLast() {
      if (!allEnds.length) return;
      const lastEnd = allEnds[allEnds.length - 1];
      lastEnd.pop();
      if (lastEnd.length === 0) {
        allEnds.pop();
      }
      selected = null;
      renderScores();
    }

    document.querySelectorAll('.score-btn').forEach(btn => {
      btn.addEventListener('click', () => addScore(btn.dataset.score));
    });

    document.getElementById('undoBtn').addEventListener('click', undoLast);

    // Prevent submission if any end has fewer than required arrows
    document.getElementById('scoreForm').addEventListener('submit', function (e) {
      const errorBox = document.getElementById('scoreError');
      errorBox.classList.add('d-none');
      errorBox.innerText = '';

      const invalidEndIndex = allEnds.findIndex(end => end.length !== arrowsPerEnd);
      if (invalidEndIndex !== -1) {
        e.preventDefault();
        errorBox.innerText = `End #${invalidEndIndex + 1} must have exactly ${arrowsPerEnd} arrows.`;
        errorBox.classList.remove('d-none');
      }
    });

    renderScores();
  </script>
@endsection
