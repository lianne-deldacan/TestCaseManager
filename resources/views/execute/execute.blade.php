@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Execute Test Cases - Project: {{ $project->name }}</h2>

    <div class="card shadow p-4">
        <div class="row mb-3">
            <!-- Project Name -->
            <div class="col-md-6">
                <label>Project Name</label>
                <input type="text" class="form-control" value="{{ $project->name }}" disabled>
            </div>

            <!-- Project ID -->
            <div class="col-md-6">
                <label>Project ID</label>
                <input type="text" class="form-control" value="{{ $project->id }}" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Test Environment Dropdown -->
            <div class="col-md-6">
                <label>Test Environment</label>
                <select id="testEnvironment" class="form-control">
                    <option value="SIT">SIT</option>
                    <option value="UAT">UAT</option>
                </select>
            </div>

            <!-- Dummy Tester Name -->
            <div class="col-md-6">
                <label>Tester Name</label>
                <input type="text" class="form-control" value="Dummy Tester" readonly>
            </div>
        </div>

        <!-- Run Test Button -->
        <div class="text-center mt-3">
            <a id="executeTestBtn" class="btn btn-success">Run Test</a>
        </div>
    </div>
</div>

<script>
document.getElementById('executeTestBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default action

    let environment = document.getElementById('testEnvironment').value;
    let projectId = {{ $project->id }};
    let executeId = {{ $executions->first()->id ?? 'null' }}; // Get the first execution ID (modify if needed)

    if (executeId === 'null') {
        alert('No execution ID found. Please ensure an execution record exists.');
        return;
    }

    // Redirect to executeTest page with execute_id and environment as query parameters
    window.location.href = `/executeTest/${projectId}?execute_id=${executeId}&environment=${environment}`;
});
</script>
@endsection
