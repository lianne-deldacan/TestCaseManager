@extends('layouts.app')

@section('content')

<body>
    <div class="container">
        <button class="close-btn">X</button>
        <h2>ISSUE</h2>
        <form action="{{ route('issue.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="form-group">
                    <label>Issue Number</label>
                    <input type="text" name="issue_number" class="form-control" value="{{ request('issue_number') }}" readonly>
                    <input type="hidden" name="test_case_id" value="{{ request('test_case_id') ?? $testCase->id ?? '' }}">
                    <input type="hidden" name="project_id" value="{{ $project->id ?? 'MISSING' }}">
                </div>
                <div class="form-group">
                    <label>Failed Test Case No.</label>
                    <input type="text" name="failed_test_case_no" value="{{ $testCase -> test_case_no }}" readonly>
                </div>
                <div class="form-group">
                    <label>Date and Time Report</label>
                    <input type="text" name="date_time_report" value="{{ now() }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Project Name</label>
                    <input type="text" name="project_name" value="{{ $project->name ?? '' }}" readonly>
                </div>
                <div class="form-group">
                    <label>Test Environment</label>
                    <input type="text" name="environment" value="{{ $testCase -> test_title }}" readonly>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option>Open</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Issue Title</label>
                    <input type="text" name="issue_title" value="{{ request('test_title') ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Tester</label>
                    <input type="text" name="tester" value="{{ $testCase -> tester }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label>Test Step</label>
                <textarea name="issue_description">{{ $testCase -> test_step }}</textarea>
            </div>
            <div class="form-group">
                <label>Add Screenshot URL</label>
                <input type="text" name="screenshot_url">
            </div>
            <div class="form-group">
                <label>Assign Developer</label>
                <select name="assigned_developer" class="form-control">
                    <option value="">Select Developer</option>
                    @foreach($developers as $developer)
                    <option value="{{ $developer }}">{{ $developer }}</option>
                    @endforeach
                </select>

            </div>
            <div class="buttons">
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