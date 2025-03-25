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
            {{-- <div class="col-md-4">
                <label>Execute Id</label>
                <input type="text" class="form-control" value="{{ $execution->id }}" disabled>
            </div> --}}
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
                            <td id="issue-number-{{ $testCase->id }}" onclick="openIssueModal('{{ $testCase->id }}')" style="cursor: pointer; color: blue;"></td>
                            <td id="execution-date-{{ $testCase->id }}">{{ now()->format('Y-m-d') }}</td>
                            <td id="status-{{ $testCase->id }}">{{ $testCase->status ?? 'Not Run' }}</td>
                            <td>
                                <button class="btn btn-success btn-sm"  onclick="executeTestCase('{{ $testCase->id }}')">Execute</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
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
<!-- Create Issue Modal -->
<div id="createIssueModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Issue</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Issue Number: <span id="issueNumber"></span></p>
                <button type="button" class="btn btn-primary" id="createIssueBtn">Create Issue</button>
            </div>
        </div>
    </div>
</div>


<!-- Button to Open Modal -->
<button onclick="openIssueModal()" class="bg-green-500 text-white px-4 py-2 rounded">Show Create Issue</button>

<script>
$(document).on('click', '.status-btn', function () {
    let row = $(this).closest('tr'); // Get the specific row
    let status = row.find('.status-text').text().trim(); // Get the status text

    if (status === 'Fail') {
        $('#createIssueModal').modal('show'); // Show the modal
        $('#createIssueModal').data('row', row); // Store row reference
    }
});

</script>


<script>
$('#createIssueBtn').on('click', function () {
    let issueNumber = 'ISSUE-' + Math.floor(Math.random() * 100000); // Generate issue number
    $('#issueNumber').text(issueNumber); // Set in modal

    let row = $('#createIssueModal').data('row'); // Get the affected row
    row.find('.issue-number-column').text(issueNumber); // Update row in the table

    $('#createIssueModal').modal('hide'); // Hide modal after creating the issue
});


</script>



<script>
    function executeTestCase(testCaseId) {
    selectedTestCaseId = testCaseId;

    Swal.fire({
        title: 'Execute Test Case',
        text: 'Choose the status for the test case.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Pass',
        cancelButtonText: 'Fail',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        showDenyButton: true,
        denyButtonText: 'N/A',
        denyButtonColor: '#6c757d',
    }).then((result) => {
        let status = 'Not Run';

        if (result.isConfirmed) status = 'Pass';
        else if (result.isDenied) status = 'N/A';
        else if (result.dismiss === Swal.DismissReason.cancel) status = 'Fail';

        if (status !== 'Not Run') {
            $.ajax({
                url: "{{ route('execute.updateStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    test_case_id: testCaseId,
                    status: status,
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Test case updated successfully!',
                        text: `Status: ${status}`,
                    });

                    // Update status cell in the table
                    $(`#status-${testCaseId}`).text(status);
                    $(`#status-${testCaseId}`).css(
                        "background-color",
                        status === "Pass" ? "#28a745" :
                        status === "Fail" ? "#dc3545" : "#6c757d"
                    );
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to update status',
                        text: xhr.responseJSON?.message || 'Unknown error occurred.',
                    });
                },
            });
        }
    });
}

</script>

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


function openIssueModal(testCaseId) {
    fetch("{{ route('execute.generateIssueNumber') }}")
        .then(response => response.json())
        .then(data => {
            document.getElementById("issueNumber").innerText = "Issue Number: " + data.issue_number;
            document.getElementById("issueModal").classList.remove("hidden");
        });
}


function handleIssueAction() {
    let queryParams = new URLSearchParams({
        test_case_id: selectedTestCaseId,
    }).toString();

    window.location.href = "{{ route('execute.createIssue') }}?" + queryParams;
}


</script>
@endsection
