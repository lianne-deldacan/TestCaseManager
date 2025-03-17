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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testCases as $testCase)
                    <tr id="testcase-row-{{ $testCase->id }}">
                        <td>{{ $testCase->test_case_no }}</td>
                        <td>{{ $testCase->test_title }}</td>
                        <td>{{ $testCase->test_step }}</td>
                        <td>{{ $testCase->category->name }}</td>
                        <td>{{ $testCase->priority }}</td>
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
            <!-- Submit Button -->
        <div class="text-center mt-3">
            <button class="btn btn-lg btn-success" onclick="submitTestCases()">Submit</button>
        </div>
</div>

<!-- Modal -->
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
                <button class="btn btn-info" onclick="changeStatus('N/R')">N/R</button>
                <button class="btn btn-primary" onclick="saveTestStatus()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Test Status</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<script>
    let selectedTestCaseId = null;

    function openTestModal(testCaseId) {
        selectedTestCaseId = testCaseId;
        let row = document.getElementById("testcase-row-" + testCaseId);
        document.getElementById("modalTestCaseNo").value = row.cells[0].innerText;
        document.getElementById("modalTestTitle").value = row.cells[1].innerText;
        document.getElementById("modalTestStep").value = row.cells[2].innerText;
        document.getElementById("modalCategory").value = row.cells[3].innerText;
        document.getElementById("modalPriority").value = row.cells[4].innerText;
        
        new bootstrap.Modal(document.getElementById("testModal")).show();
    }

    function changeStatus(status) {
        document.getElementById("toastMessage").innerText = "You changed status to " + status;
        new bootstrap.Toast(document.getElementById("statusToast")).show();
    }

    function saveTestStatus() {
        let status = document.getElementById("toastMessage").innerText.replace("You changed status to ", "");
        if (selectedTestCaseId) {
            document.getElementById("status-" + selectedTestCaseId).innerText = status;
        }
        Swal.fire({
            icon: 'success',
            title: 'Test Information Saved Successfully'
        });
    }

     function submitTestCases() {
    let failedTestCases = [];
    let failMessage = "The following test cases failed:\n";
    let issueUrl = "/create-issue?"; // Base URL for issue creation

    // Loop through all test case rows and check the status
    document.querySelectorAll("#testcasesTable tbody tr").forEach(row => {
        let testCaseId = row.id.replace("testcase-row-", ""); // Extract Test Case ID
        let status = row.querySelector("td:nth-child(6)").innerText.trim(); // Get Status Text

        if (status.toLowerCase() === "fail") {
            failedTestCases.push(testCaseId);

            let testCaseTitle = row.querySelector("td:nth-child(2)").innerText.trim();
            failMessage += `- ${testCaseTitle}\n`;
        }
    });

    if (failedTestCases.length > 0) {
        issueUrl += failedTestCases.map(id => `failed_cases[]=${id}`).join("&"); // Proper array format

        // Show SweetAlert with failed test cases
        Swal.fire({
            icon: 'error',
            title: 'Test Execution Completed',
            text: failMessage,
            showCancelButton: true,
            confirmButtonText: 'Create Issue',
            cancelButtonText: 'Close',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = issueUrl; // Redirect to issue creation page with failed test cases
            }
        });
    } else {
        // Success message if all passed
        Swal.fire({
            icon: 'success',
            title: 'Test Execution Completed',
            text: 'All test cases passed successfully! No issues to create.',
        });
    }
}


    $(document).ready(function () {
        $('#testcasesTable').DataTable();
    });
</script>
@endsection
