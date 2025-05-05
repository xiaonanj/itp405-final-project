@extends('layout')

@section('title', 'Create Round')

@section('main')
  <h1>Create a New Round</h1>

  <form method="POST" action="{{ route('rounds.store') }}">
    @csrf

    <div class="mb-3">
  <label class="form-label">Indoor or Outdoor</label><br>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="is_outdoor" value="1" {{ old('is_outdoor') === '1' ? 'checked' : '' }}>
    <label class="form-check-label">Outdoor</label>
  </div>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="is_outdoor" value="0" {{ old('is_outdoor') === '0' ? 'checked' : '' }}>
    <label class="form-check-label">Indoor</label>
  </div>
  @error('is_outdoor') <div class="text-danger">{{ $message }}</div> @enderror
</div>


    <div class="mb-3">
      <label class="form-label">Target Distance</label>
      
      <select name="target_distance" class="form-select">
      <option value="">---</option>

        @foreach ([18, 30, 50] as $distance)
          <option value="{{ $distance }}" {{ old('target_distance') == $distance ? 'selected' : '' }}>
            {{ $distance }} meters
          </option>
        @endforeach
      </select>
      @error('target_distance') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Bow Type</label>
      <select name="bow_type" class="form-select">
        <option value="">---</option>
        @foreach (['barebow', 'recurve', 'compound'] as $bow)
            <option value="{{ $bow }}" {{ old('bow_type') == $bow ? 'selected' : '' }}>
            {{ ucfirst($bow) }}
            </option>
        @endforeach
        </select>
      @error('bow_type') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
  <label class="form-label">Session Type</label><br>
  @foreach (['practice', 'competition'] as $type)
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="session_type" value="{{ $type }}" {{ old('session_type') == $type ? 'checked' : '' }}>
      <label class="form-check-label">{{ ucfirst($type) }}</label>
    </div>
  @endforeach
  @error('session_type') <div class="text-danger">{{ $message }}</div> @enderror
</div>


<div class="mb-3">
  <label class="form-label">Arrows Per End</label><br>
  @foreach ([3, 6] as $count)
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="arrows_per_end" value="{{ $count }}" {{ old('arrows_per_end') == $count ? 'checked' : '' }}>
      <label class="form-check-label">{{ $count }}</label>
    </div>
  @endforeach
  @error('arrows_per_end') <div class="text-danger">{{ $message }}</div> @enderror
</div>


    <div class="mb-3">
      <label class="form-label">Location (optional)</label>
      <input type="text" name="location" class="form-control" value="{{ old('location', $position->cityName ?? '') }}" placeholder="Enter location">
      @error('location') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-success">Create Round</button>
  </form>
@endsection
