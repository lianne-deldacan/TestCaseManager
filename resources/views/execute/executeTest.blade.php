@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Execute Test - Project: {{ $project->name }}</h2>

    <div class="card shadow p-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Project Name</label>
                <input type="text" class="form-control" value="{{ $project->name }}" disabled>
            </div>
            <div class="col-md-6">
                <label>Test Environment</label>
                <input type="text" class="form-control" value="{{ request('environment') }}" disabled>
            </div>
        </div>

        <div class="mt-4">
            <table id="testcasesTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Test Case No.</th>
                        <th>Test Title</th>
                        <th>Test Step</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Issue Number</th>
                        <th>Execution Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testCases as $testCase)
                    <tr id="testcase-row-{{ $testCase->id }}">
                        <td>{{ $testCase->test_case_no }}</td>
                        <td>{{ $testCase->test_title }}</td>
                        <td><input type="text" class="form-control" value="{{ $testCase->test_step }}" disabled></td>
                        <td>{{ $testCase->category->name }}</td>
                        <td>{{ $testCase->priority }}</td>
                        <td id="issue-number-{{ $testCase->id }}" style="cursor: pointer; color: blue;" onclick="fetchAuditTrail({{ $project->id }}, {{ $testCase->id }})"></td>
                        <td>{{ now()->format('Y-m-d') }}</td>
                        <td id="status-{{ $testCase->id }}">{{ $testCase->status ?? 'Not Run' }}</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="executeTestCase('{{ $testCase->id }}')">Execute</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Issue Modal -->
<div id="createIssueModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="generatedIssueNumber" class="fw-bold"></p>
                <button type="button" class="btn btn-primary" id="createIssueBtn">Create Issue</button>
            </div>
        </div>
    </div>
</div>

<!-- Audit Trail Modal -->
<div id="auditTrailModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Audit Trail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-sm btn-primary mb-3" onclick="redirectToCreateIssue({{ $testCase->id }})">View Issue</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Issue Number</th>
                        </tr>
                    </thead>
                    <tbody id="auditTrailTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let currentProjectId = {{$project -> id }};
    let issueCounters = {};
    let selectedTestCaseId = null;
    let auditTrail = {};

    async function executeTestCase(testCaseId) {
        selectedTestCaseId = testCaseId;

        const result = await Swal.fire({
            title: 'Execute Test Case',
            text: 'Choose status for this test case.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Pass',
            cancelButtonText: 'Fail',
            showDenyButton: true,
            denyButtonText: 'N/A',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            denyButtonColor: '#6c757d',
        });

        let newStatus = 'Not Run';

        if (result.isConfirmed) {
            newStatus = 'Pass';
        } else if (result.isDenied) {
            newStatus = 'N/A';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            newStatus = 'Fail';

            // 🛑 Await the issue number here
            const issueNumber = await generateIssueNumber(currentProjectId);

            const subResult = await Swal.fire({
                title: "There's something wrong in your test case",
                text: `Generated Issue Number: ${issueNumber}`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Create Issue',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
            });

            if (subResult.isConfirmed) {
                $(`#issue-number-${testCaseId}`).text(issueNumber);
                recordAuditTrail(testCaseId, 'Issue Created', newStatus, issueNumber);

                await Swal.fire({
                    icon: 'success',
                    title: 'Issue Created Successfully!',
                    text: `Issue Number: ${issueNumber}`,
                });
            }
        }

        if (newStatus !== 'Not Run') {
            updateTestCaseStatus(testCaseId, newStatus);
        }
    }

    function updateTestCaseStatus(testCaseId, newStatus) {
        $.ajax({
            url: "/update-status",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                test_case_id: testCaseId,
                status: newStatus
            },
            success: function(response) {
                $(`#status-${testCaseId}`).text(newStatus).css("background-color",
                    newStatus === "Pass" ? "#28a745" :
                    newStatus === "Fail" ? "#dc3545" :
                    "#6c757d");

                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated!',
                    text: `Test case marked as ${newStatus}.`,
                });

                recordAuditTrail(testCaseId, 'Executed', newStatus);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update test case status.',
                });
            }
        });
    }
    // Record audit trail
    function recordAuditTrail(testCaseId, action, status, issueNumber = '') {
        if (!auditTrail[testCaseId]) {
            auditTrail[testCaseId] = [];
        }
        auditTrail[testCaseId].push({
            date: new Date().toLocaleString(),
            action: action,
            status: status,
            issueNumber: issueNumber
        });

        // Update issue number in UI
        if (issueNumber) {
            $(`#issue-number-${testCaseId}`).text(issueNumber);
        }
    }


    // Fetch and display audit trail
    function fetchAuditTrail(projectId, testCaseId) {
        const trail = auditTrail[testCaseId] || [];
        const tableBody = trail.map(entry => `
        <tr>
            <td>${entry.date}</td>
            <td>${entry.action}</td>
            <td>${entry.status}</td>
            <td>${entry.issueNumber}</td>
        </tr>
    `).join('');

        $('#auditTrailTableBody').html(tableBody);

        // Update the View Issue button dynamically
        $('#auditTrailModal .btn-primary').attr('onclick', `redirectToCreateIssue(${testCaseId})`);

        $('#auditTrailModal').modal('show');
    }


    // Generate issue number


    async function generateIssueNumber(projectId) {
        if (!issueCounters[projectId]) {
            try {
                const response = await fetch(`/issues/last/${projectId}`);
                const data = await response.json();
                issueCounters[projectId] = data.last_issue_number;
            } catch (error) {
                console.error("Error fetching last issue number:", error);
                issueCounters[projectId] = 0;
            }
        }

        issueCounters[projectId] += 1;
        return `BELL-${projectId}-${issueCounters[projectId].toString().padStart(3, '0')}`;
    }


    function redirectToCreateIssue(testCaseId) {
        let issueNumber = $("#auditTrailTableBody tr:first td:last").text().trim();
        if (!issueNumber) {
            Swal.fire({
                icon: 'warning',
                title: 'No Issue Found',
                text: 'No issue has been logged for this test case yet.',
            });
            return;
        }

        let url = "{{ route('issue.create') }}?issue_number=" + issueNumber + "&test_case_id=" + testCaseId + "&project_id=" + currentProjectId;
        window.location.href = url;
    }

</script>
@endsection