@extends('layouts.app')

@section('content')

<body>
    <div class="container">
        <button class="close-btn">X</button>
        <h2>ISSUE</h2>
        <form action="{{ route('issue.store') }}" method="POST">
            @csrf
            <!-- First Row: Project Name, Project Service, Tester -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Name</label>
                        <input type="text" name="project_name" class="form-control" value="{{ $project->name ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Service</label>
                        <input type="text" name="project_service" class="form-control" value="{{ $project->service ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tester</label>
                        <input type="text" name="tester" class="form-control" value="{{ $testCase->tester ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Second Row: Tester Date, Test Environment, Status -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date and Time Report</label>
                        <input type="text" name="date_time_report" class="form-control" value="{{ now() }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Test Environment</label>
                        <input type="text" name="environment" class="form-control" value="{{ $testCase->test_title ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Open">Open</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Third Row: Test Case No., Category, Assigned Developer -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Failed Test Case No.</label>
                        <input type="text" name="failed_test_case_no" class="form-control" value="{{ $testCase->test_case_no ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category</label>
                       <input type="text" name="category" class="form-control" value="{{ $testCase->category->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Assign Developer</label>
                        <select name="assigned_developer" class="form-control">
                            <option value="">Select Developer</option>
                            @foreach($developers as $developer)
                            <option value="{{ $developer }}">{{ $developer }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Fourth Row: Issue Title -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Issue Title</label>
                        <input type="text" name="issue_title" class="form-control" value="{{ request('test_title') ?? '' }}">
                    </div>
                </div>
            </div>

            <!-- Fifth Row: Test Step -->
            <div class="mb-3">
                <label>Test Step</label>
                <textarea name="issue_description" class="form-control" rows="4">{{ $testCase->test_step ?? '' }}</textarea>
            </div>

            <!-- Sixth Row: Screenshot -->
            <div class="mb-3">
                <label>Add Screenshot URL</label>
                <input type="text" name="screenshot_url" class="form-control">
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