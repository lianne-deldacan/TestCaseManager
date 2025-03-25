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
            <div class="col-md-4">
                <label>Execute Id</label>
                <input type="text" class="form-control" value="{{ $execution->id }}" disabled>
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
                        <td id="issue-number-{{ $testCase->id }}" onclick="openIssueModal({{ $testCase->id }})" style="cursor: pointer; color: blue;"></td>
                        <td id="execution-date-{{ $testCase->id }}">{{ now()->format('Y-m-d') }}</td>
                        <td id="status-{{ $testCase->id }}">Not Run</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openTestModal({{ $testCase->id }})">Run</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center mt-3">
        <button class="btn btn-lg btn-success" onclick="submitTestCases()">Submit</button>
    </div>
</div>

<!-- Test Execution Modal -->
<div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testModalLabel">Execute Test Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Test Case No.</label>
                <input type="text" class="form-control" id="modalTestCaseNo" disabled>
                <label>Test Title</label>
                <input type="text" class="form-control" id="modalTestTitle" disabled>
                <label>Test Step</label>
                <input type="text" class="form-control" id="modalTestStep" disabled>
                <label>Category</label>
                <input type="text" class="form-control" id="modalCategory" disabled>
                <label>Priority</label>
                <input type="text" class="form-control" id="modalPriority" disabled>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="changeStatus('Pass')">Pass</button>
                <button class="btn btn-danger" onclick="changeStatus('Fail')">Fail</button>
                <button class="btn btn-secondary" onclick="changeStatus('N/A')">N/A</button>
                <button class="btn btn-primary" onclick="saveTestStatus()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Issue Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Test Case No.:</strong> <span id="issueTestCaseNo"></span></p>
                <p><strong>Test Title:</strong> <span id="issueTestTitle"></span></p>
                <p><strong>Test Step:</strong> <span id="issueTestStep"></span></p>
                <button class="btn btn-primary" id="issueModalButton" onclick="handleIssueAction()">Create Issue</button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedTestCaseId = null;

    function openTestModal(testCaseId) {
        selectedTestCaseId = testCaseId;
        let row = document.getElementById("testcase-row-" + testCaseId);
        document.getElementById("modalTestCaseNo").value = row.cells[0].innerText;
        document.getElementById("modalTestTitle").value = row.cells[1].innerText;
        document.getElementById("modalTestStep").value = row.cells[2].querySelector('input').value;
        document.getElementById("modalCategory").value = row.cells[3].innerText;
        document.getElementById("modalPriority").value = row.cells[4].innerText;
        new bootstrap.Modal(document.getElementById("testModal")).show();
    }

let issueCounter = 1; // Initialize issue counter

function changeStatus(status) {
    let statusCell = document.getElementById("status-" + selectedTestCaseId);
    let issueNumberCell = document.getElementById("issue-number-" + selectedTestCaseId);

    statusCell.innerText = status; // Update status text

    if (status === "Pass") {
        statusCell.style.backgroundColor = "#28a745";
        Swal.fire({ icon: 'success', title: 'Test Passed Successfully' });
    } else if (status === "Fail") {
        statusCell.style.backgroundColor = "#dc3545";

        // If no issue number exists, create a new one
        if (!issueNumberCell.innerText.trim()) {
            let issueNumber = `BELL-${String(issueCounter).padStart(4, '0')}`;
            issueNumberCell.innerText = issueNumber;
            issueNumberCell.style.cursor = "pointer";
            issueNumberCell.style.color = "blue";
            issueNumberCell.onclick = () => openIssueModal(selectedTestCaseId, false);
            issueCounter++;
        }

        // Open issue modal in "Create Issue" mode
        openIssueModal(selectedTestCaseId, false);
    }
}


function openIssueModal(testCaseId, isViewing = true) {
    let issueNumberCell = document.getElementById("issue-number-" + testCaseId);

    if (issueNumberCell.innerText.trim() !== "") {
        let row = document.getElementById("testcase-row-" + testCaseId);
        
        if (row) {  // Ensure the row is found
            document.getElementById("issueTestCaseNo").innerText = row.cells[0].innerText || "N/A";
            document.getElementById("issueTestTitle").innerText = row.cells[1].innerText || "N/A";
            
            let testStepInput = row.cells[2].querySelector('input');
            document.getElementById("issueTestStep").innerText = testStepInput ? testStepInput.value : "N/A";

            // Set button text based on action
            let issueModalButton = document.getElementById("issueModalButton");
            if (isViewing) {
                issueModalButton.innerText = "View Issue";
            } else {
                issueModalButton.innerText = "Create Issue";
            }

            $('#issueModal').modal('show');
        } else {
            console.error("Row not found for testCaseId: " + testCaseId);
        }
    }
}

function handleIssueAction() {
    let testCaseNo = document.getElementById("issueTestCaseNo").innerText;
    let testTitle = document.getElementById("issueTestTitle").innerText;
    let testStep = document.getElementById("issueTestStep").innerText;

    if (testCaseNo.trim()) {
        let queryParams = new URLSearchParams({
            test_case_no: testCaseNo,
            test_title: testTitle,
            test_step: testStep
        }).toString();

        window.location.href = "{{ route('issue.create') }}?" + queryParams;
    } else {
        alert("Failed test case number not found!");
    }
}

</script>
@endsection
