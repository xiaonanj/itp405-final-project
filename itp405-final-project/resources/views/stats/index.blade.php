@extends('layout')

@section('title', 'Statistics')

@section('main')
  <h1 class="mb-4">Statistics</h1>

  <form method="GET" class="row g-3 mb-4" id="filterForm">
    <div class="col-md-3">
      <label for="bow_type" class="form-label">Bow Type</label>
      <select name="bow_type" id="bow_type" class="form-select" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="barebow" {{ request('bow_type') == 'barebow' ? 'selected' : '' }}>Barebow</option>
        <option value="recurve" {{ request('bow_type') == 'recurve' ? 'selected' : '' }}>Recurve</option>
        <option value="compound" {{ request('bow_type') == 'compound' ? 'selected' : '' }}>Compound</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="target_distance" class="form-label">Distance</label>
      <select name="target_distance" id="target_distance" class="form-select" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="18" {{ request('target_distance') == '18' ? 'selected' : '' }}>18m</option>
        <option value="30" {{ request('target_distance') == '30' ? 'selected' : '' }}>30m</option>
        <option value="50" {{ request('target_distance') == '50' ? 'selected' : '' }}>50m</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="is_outdoor" class="form-label">Round Type</label>
      <select name="is_outdoor" id="is_outdoor" class="form-select" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="1" {{ request('is_outdoor') === '1' ? 'selected' : '' }}>Outdoor</option>
        <option value="0" {{ request('is_outdoor') === '0' ? 'selected' : '' }}>Indoor</option>
      </select>
    </div>
  </form>

  <p class="mb-2"><strong>Total Arrows:</strong> {{ $totalArrows }}</p>
  <p class="mb-2"><strong>Average:</strong> {{ $average }} | <strong>Total Score:</strong> {{ $totalScore }}</p>
  <p class="mb-4"><strong>Golds:</strong> {{ $goldCount }} | <strong>Hits:</strong> {{ $hitCount }}/{{ $totalArrows }}</p>

  <div style="max-width: 60%; margin: 0 auto;">
    <canvas id="barChart" height="120"></canvas>
  </div>
  <div style="max-width: 40%; margin: 0 auto; padding-top: 20px;">
    <canvas id="pieChart" height="120" class="mt-4"></canvas>
  </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
const allLabels = ['X', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1', 'M'];
const scoreCounts = @json($scoreCounts);
const data = allLabels.map(label => scoreCounts[label] ?? 0);
const total = {{ $totalArrows }};

const scoreColors = {
  'X': '#FFD700', '10': '#FFD700', '9': '#FFD700',
  '8': '#DC3545', '7': '#DC3545',
  '6': '#0D6EFD', '5': '#0D6EFD',
  '4': '#212529', '3': '#212529',
  '2': '#F8F9FA', '1': '#F8F9FA',
  'M': '#28A745'
};
const backgroundColors = allLabels.map(label => scoreColors[label] || '#ccc');

new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: allLabels,
    datasets: [{
      label: 'Score Percentage (%)',
      data: data.map(count => total ? ((count / total) * 100).toFixed(2) : 0),
      backgroundColor: backgroundColors,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      datalabels: {
        anchor: 'end',
        align: 'end',
        formatter: (val) => `${val}%`,
        font: {
          weight: 'bold'
        }
      },
      tooltip: {
        callbacks: {
          label: ctx => `${ctx.raw}%`
        }
      }
    },
    layout: {
      padding: {
        top: 20
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: val => `${val}%`
        }
      }
    }
  },
  plugins: [ChartDataLabels]
});

new Chart(document.getElementById('pieChart'), {
  type: 'pie',
  data: {
    labels: allLabels,
    datasets: [{
      data: data,
      backgroundColor: backgroundColors,
    }]
  },
  options: {
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
@endsection
