@extends('layouts.app')

@section('title', 'Select Project & Service')

@section('content')
<div class="container mt-5">
  <div class="card shadow-sm p-4">
    <h3 class="mb-4">Select Project and Service</h3>

<form method="GET" action="{{ route('categories.create') }}">
  @csrf

  <!-- Select Service -->
  <div class="form-group mb-3">
    <label for="service">Service</label>
    <select name="service" id="service" class="form-control" required>
      <option value="">-- Select Service --</option>
      @foreach(config('global.services') as $key => $name)
        <option value="{{ $key }}">{{ $name }}</option>
      @endforeach
    </select>
  </div>

  <!-- Select Project (Populated based on service) -->
  <div class="form-group mb-4">
    <label for="project">Project</label>
    <select name="project" id="project" class="form-control" required>
      <option value="">-- Select Project --</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Proceed</button>
</form>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('service').addEventListener('change', function () {
  const serviceKey = this.value;

  if (!serviceKey) return;

  fetch(`/api/projects/by-service/${serviceKey}`)
    .then(response => response.json())
    .then(projects => {
      const projectSelect = document.getElementById('project');
      projectSelect.innerHTML = '<option value="">-- Select Project --</option>';

      projects.forEach(project => {
        const option = document.createElement('option');
        option.value = project.id;
        option.textContent = project.name;
        projectSelect.appendChild(option);
      });
    });
});
</script>
@endpush