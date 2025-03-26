@extends('layouts.app')

@section('content')

<body>
    <div class="container mt-4">
        <button type="button" class="btn-close float-end" aria-label="Close"></button>
        <h2 class="mb-4">Issue Form</h2>
        <form action="{{ route('issue.store') }}" method="POST">
            @csrf

            <!-- 1st Line: Project Name, Project Service -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="project_name" class="form-control" value="{{ $project->name ?? '' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Project Service</label>
                    <input type="text" name="project_service" class="form-control" value="{{ $project->service ?? 'N/A' }}" readonly>
                </div>
            </div>

            <!-- 2nd Line: Tester, Date -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tester</label>
                    <input type="text" name="tester" class="form-control" value="{{ $testCase->tester }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date and Time Report</label>
                    <input type="text" name="date_time_report" class="form-control" value="{{ now() }}" readonly>
                </div>
            </div>

            <!-- 3rd Line: Test Case No., Category -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Failed Test Case No.</label>
                    <input type="text" name="failed_test_case_no" class="form-control" value="{{ $testCase->test_case_no }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ $testCase->category->name ?? 'N/A' }}" readonly>
                </div>
            </div>

            <!-- 4th Line: Test Title -->
            <div class="mb-3">
                <label class="form-label">Test Title</label>
                <input type="text" name="test_title" class="form-control" value="{{ $testCase->test_title }}" readonly>
            </div>

            <!-- 5th Line: Test Step (Textbox) -->
            <div class="mb-3">
                <label class="form-label">Test Step</label>
                <textarea name="issue_description" class="form-control">{{ $testCase->test_step }}</textarea>
            </div>

            <!-- Additional Fields -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option>Open</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Add Screenshot URL</label>
                    <input type="text" name="screenshot_url" class="form-control">
                </div>
            </div>

            <!-- Assign Developer -->
            <div class="mb-4">
                <label class="form-label">Assign Developer</label>
                <select name="assigned_developer" class="form-select">
                    <option value="">Select Developer</option>
                    @foreach($developers as $developer)
                        <option value="{{ $developer }}">{{ $developer }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end">
                <button type="reset" class="cancel-btn">Cancel</button>
                <button type="submit" class="add-btn">Add Issue</button>
            </div>
        </form>
    </div>
</body>

@if(session('success'))
<script>
    function handleIssueAction() {
        let testCaseNo = document.getElementById("issueTestCaseNo").innerText;
        let testTitle = document.getElementById("issueTestTitle").innerText;
        let testStep = document.getElementById("issueTestStep").innerText;

        if (testCaseNo) {
            let queryParams = new URLSearchParams({
                test_case_no: testCaseNo,
                test_title: testTitle,
                test_step: testStep
            }).toString();

            window.location.href = `/issue/create?${queryParams}`;
        } else {
            alert("Failed test case number not found!");
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
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