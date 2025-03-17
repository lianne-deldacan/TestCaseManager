@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Issue</h2>

    <form action="{{ route('issue.store') }}" method="POST">
        @csrf

        <!-- Auto Issue Number -->
        <div class="form-group">
            <label>Auto Issue Number</label>
            <input type="text" name="issue_number" value="{{ uniqid() }}" readonly>
        </div>

        <!-- Project ID -->
        <div class="form-group">
            <label>Project ID</label>
            <input type="text" name="project_id" value="{{ $project->id ?? 'N/A' }}" readonly>
        </div>

        <!-- Execution ID -->
        <div class="form-group">
            <label>Execution ID</label>
            <input type="text" name="execution_id" value="{{ $execution->id ?? 'N/A' }}" readonly>
        </div>

        <!-- Date and Time Report -->
        <div class="form-group">
            <label>Date and Time Report</label>
            <input type="text" name="date_time_report" value="{{ now() }}" readonly>
        </div>

        <!-- Tester Name (from TestCases) -->
        <div class="form-group">
            <label>Tester Name</label>
            <input type="text" name="tester" value="{{ $tester ?? 'N/A' }}" readonly>
        </div>

        <!-- Project Name -->
        <div class="form-group">
            <label>Project Name</label>
            <input type="text" name="project_name" value="{{ $project->name ?? 'N/A' }}" readonly>
        </div>

        <!-- Test Environment -->
        <div class="form-group">
            <label>Test Environment</label>
            <input type="text" name="environment" value="{{ $execution->environment ?? 'N/A' }}" readonly>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" value="Open" readonly>
        </div>

        <!-- Issue Title -->
        <div class="form-group">
            <label>Issue Title</label>
            <input type="text" name="issue_title" required>
        </div>

        <!-- Issue Description -->
        <div class="form-group">
            <label>Issue Description</label>
            <textarea name="issue_description" required></textarea>
        </div>

        <!-- Add Screenshot URL -->
        <div class="form-group">
            <label>Add Screenshot URL</label>
            <input type="text" name="screenshot_url">
        </div>

        <!-- Assign Developer -->
        <div class="form-group">
            <label>Assign Developer</label>
            <select name="assigned_developer">
                <option value="">-- Select Developer --</option>
                @foreach($developers as $developer)
                    <option value="{{ $developer }}">{{ $developer }}</option>
                @endforeach
            </select>
        </div>

        <!-- Hidden Inputs for Failed Cases -->
        @foreach($failedCases as $case)
            <input type="hidden" name="failed_cases[]" value="{{ $case }}">
        @endforeach

        <!-- Buttons -->
        <div class="buttons">
            <button type="reset">Cancel</button>
            <button type="submit">Add Issue</button>
        </div>
    </form>
</div>

@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'success',
                title: 'Issue Added Successfully',
                text: '{{ session("success") }}',
            }).then(() => {
                window.location.href = "{{ route('issue.index') }}";
            });
        });
    </script>
@endif

@endsection
