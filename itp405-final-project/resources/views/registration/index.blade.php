@extends('layout')

@section('title', 'Register')

@section('main')
  <h1>Register</h1>

  <form method="post" action="{{ route('registration.create') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label" for="name">Full name</label>
      <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
    </div>
    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
    </div>
    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input type="password" id="password" name="password" class="form-control">
    </div>
    <input type="submit" value="Register" class="btn btn-primary">
  </form>
  @auth
  <form method="POST" action="{{ route('auth.logout') }}">
    @csrf
    <button type="submit" class="btn btn-link nav-link">Logout</button>
  </form>
@endauth

@endsection
