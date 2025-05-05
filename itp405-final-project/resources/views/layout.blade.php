<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Archery Tracker')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
      <a class="navbar-brand" href="{{ route('rounds.index') }}">🏹 Archery Tracker</a>
      <ul class="navbar-nav ms-auto">
        @auth
        <li class="nav-item"><a class="nav-link" href="{{ route('rounds.index') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('rounds.create') }}">New Round</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('stats.index') }}">Stats</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('favorites.index') }}">Favorites</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('world-rankings.index') }}">World Rankings</a></li>
        <li class="nav-item">
            <form method="POST" action="{{ route('auth.logout') }}">
              @csrf
              <button class="btn btn-link nav-link" type="submit">Logout</button>
            </form>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('registration.index') }}">Register</a></li>
        @endauth
      </ul>
    </div>
  </nav>

  <div class="container">
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    @yield('main')
    @yield('scripts')

  </div>
</body>
</html>
