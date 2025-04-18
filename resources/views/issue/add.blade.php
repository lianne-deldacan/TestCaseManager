@extends('layouts.app')

@section('content')

<body>
    <div class="container">
        <h2 id="projectHeader">ISSUE - Project Name</h2>

        <form action="{{ route('issue.save') }}" method="POST">
            @csrf

            <input type="hidden" id="issueNumber" name="issue_number" value="">
            <input type="hidden" id="project_name" name="project_name">

            <input type="hidden" name="project_id" value="{{ request('project_id') }}">
            <input type="hidden" name="test_case_id" value="{{ request('test_case_id') }}">

            <!-- First Line -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Name</label>
                        <input type="text" id="projectName" class="form-control" value="{{ $project_name }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Service</label>
                        <input type="text" id="serviceField" class="form-control" value="{{ $service }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tester</label>
                        <input type="text" id="testCaseTester" name="tester" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <!-- Third Line -->
            <div class="mb-3">
                <label>Failed Test Case Title</label>
                <select name="test_case_id" id="failedTestCaseDropdown" class="form-control">
                    <option value="">Select Failed Test Case</option>
                    @foreach($failedTestCases as $test)
                    <option value="{{ $test->id }}"
                        data-test-no="{{ $test->test_case_no }}"
                        data-category="{{ $test->category->name ?? 'N/A' }}"
                        data-tester="{{ $test->tester }}"
                        data-test-environment="{{ $test->test_environment ?? '' }}"
                        data-test-step="{{ $test->test_step ?? '' }}">
                        {{ $test->test_title }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Second Line -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date and Time Report</label>
                        <input type="text" id="dateTimeReport" name="date_time_report" class="form-control" value="{{ now() }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Test Environment</label>
                        <input type="text" id="testEnvironment" name="environment" class="form-control" readonly>
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

            <!-- Fourth Line -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Test Case Number</label>
                        <input type="text" id="testCaseNumber" class="form-control" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" id="testCaseCategory" class="form-control" readonly>
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

            <!-- Fifth Line -->
            <div class="mb-3">
                <label>Test Step</label>
                <textarea id="testStep" name="test_step" class="form-control" rows="2" disabled></textarea>
            </div>

            <!-- Sixth Line -->
            <div class="mb-3">
                <label>Issue Title</label>
                <input type="text" name="issue_title" class="form-control">
            </div>

            <!-- Seventh Line -->
            <div class="mb-3">
                <label>Issue Description</label>
                <textarea name="issue_description" class="form-control" rows="3"></textarea>
            </div>

            <!-- Eighth Line -->
            <div class="mb-3">
                <label>Add Screenshot URL</label>
                <input type="text" id="screenshotUrl" name="screenshot_url" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Issue</button>
        </form>
    </div>

    <script>
        let issueCounters = {}; // Store last issue numbers per project

        async function generateIssueNumber(projectId) {
            if (!issueCounters[projectId]) {
                try {
                    const response = await fetch(`/issues/last/${projectId}`);
                    const data = await response.json();
                    issueCounters[projectId] = data.last_issue_number || 0;
                } catch (error) {
                    console.error("Error fetching last issue number:", error);
                    issueCounters[projectId] = 0;
                }
            }

            issueCounters[projectId] += 1;
            return `BELL-${projectId}-${issueCounters[projectId].toString().padStart(3, '0')}`;
        }

        document.getElementById('projectDropdown').addEventListener('change', async function() {
            var selectedOption = this.options[this.selectedIndex];
            var projectId = selectedOption.value;
            var projectName = selectedOption.text;

            document.getElementById('projectHeader').innerText = "ISSUE - " + projectName;
            document.getElementById('serviceField').value = selectedOption.getAttribute('data-service') || '';

            if (projectId) {
                let issueNumber = await generateIssueNumber(projectId);
                document.getElementById('issueNumber').value = issueNumber;
            }
        });

        document.getElementById('projectDropdown').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('project_name').value = selectedOption.text;
        });
        
        document.getElementById('failedTestCaseDropdown').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('testCaseNumber').value = selectedOption.getAttribute('data-test-no') || '';
            document.getElementById('testCaseCategory').value = selectedOption.getAttribute('data-category') || '';
            document.getElementById('testCaseTester').value = selectedOption.getAttribute('data-tester') || '';
            document.getElementById('testEnvironment').value = selectedOption.getAttribute('data-test-environment') || '';
            document.getElementById('testStep').value = selectedOption.getAttribute('data-test-step') || '';
        });
    </script>


</body>


@endsection