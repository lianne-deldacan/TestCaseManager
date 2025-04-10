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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="generatedIssueNumber" class="fw-bold"></p>
                
            <form action="{{ route('issue.store') }}" method="POST">
                @csrf
                <input type="hidden" name="issue_number" value="{{ $issueNumber ?? '' }}">
                <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
                <input type="hidden" name="test_case_id" value="{{ $testCase->id ?? '' }}">

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
                            <input type="text" name="environment" class="form-control" value="{{ $testCase->test_environment ?? '' }}" readonly>
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
                                @if(isset($developers))
                                    @foreach($developers as $developer)
                                        <option value="{{ $developer }}">{{ $developer }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Issue Title</label>
                            <input type="text" name="issue_title" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Test Step</label>
                    <textarea name="test_step_display" class="form-control" rows="2" disabled>{{ $testCase->test_step ?? '' }}</textarea>
                    <input type="hidden" name="test_step" value="{{ $testCase->test_step ?? '' }}">
                </div>

                <div class="mb-3">
                    <label>Issue Description</label>
                    <textarea name="issue_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label>Add Screenshot URL</label>
                    <input type="text" name="screenshot_url" class="form-control">
                </div>

                <div class="buttons">
                    <button type="reset" class="cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Issue</button>
                </div>
            </form>

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

<!-- Testcase form modal-->

<!-- Execute Test Case Modal -->
<div class="modal fade" id="executeTestModal" tabindex="-1" aria-labelledby="executeTestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="executeTestModalLabel">Execute Test Case</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="executeForm">
          @csrf
          <input type="hidden" name="test_case_id" id="modal_test_case_id">
          
          <!-- Row 1: Project Name | Service -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="project-name" class="form-label">Project Name</label>
              <input type="text" id="project-name" class="form-control" value="{{ $project->name }}" disabled>
            </div>
            <div class="col-md-6">
              <label for="service" class="form-label">Service</label>
              <input type="text" id="service" class="form-control" value="{{ $service ?? 'No service available' }}" disabled>
            </div>
          </div>

          <!-- Row 2: Test Environment | Tester | Date -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="test_environment" class="form-label">Test Environment</label>
              <select id="test_environment" name="test_environment" class="form-control" required>
                <option value="SIT">SIT</option>
                <option value="UAT">UAT</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="tester" class="form-label">Tester</label>
              <input type="text" id="tester" name="tester" class="form-control" value="Tester Name">
            </div>
            <div class="col-md-4">
              <label for="date" class="form-label">Date</label>
              <input type="date" id="date" name="date" class="form-control">
            </div>
          </div>

          <!-- Row 3: Category | Test Case No -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="category" class="form-label">Category</label>
              <input type="text" id="category" name="category" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="test_case_no" class="form-label">Test Case No.</label>
              <input type="text" id="test_case_no" name="test_case_no" class="form-control" required>
            </div>
          </div>

          <!-- Row 4: Test Title (Full Width) -->
          <div class="mb-3">
            <label for="test_title" class="form-label">Test Title</label>
            <input type="text" id="test_title" name="test_title" class="form-control" required>
          </div>

          <!-- Row 5: Test Step (Full Width) -->
          <div class="mb-3">
            <label for="test_step" class="form-label">Test Step</label>
            <textarea id="test_step" name="test_step" rows="3" class="form-control" required></textarea>
          </div>

          <!-- Status Selection Buttons -->
          <div class="text-center">
            <button type="button" class="btn btn-success" id="passBtn">Pass</button>
            <button type="button" class="btn btn-secondary" id="naBtn">N/A</button>
            <button type="button" class="btn btn-danger" id="failBtn">Fail</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<script>
    function handleIssueAction(testCaseId, testCaseNo, testTitle, testStep, projectId, projectName, projectService, tester, environment) {
        // Set values for hidden and readonly fields
        document.querySelector('input[name="test_case_id"]').value = testCaseId;
        document.querySelector('input[name="project_id"]').value = projectId;
        document.querySelector('input[name="issue_number"]').value = "ISSUE-" + Date.now(); // Unique Issue Number

        document.querySelector('input[name="failed_test_case_no"]').value = testCaseNo;
        document.querySelector('input[name="project_name"]').value = projectName;
        document.querySelector('input[name="project_service"]').value = projectService;
        document.querySelector('input[name="tester"]').value = tester;
        document.querySelector('input[name="environment"]').value = environment;
        document.querySelector('textarea[name="test_step"]').value = testStep;

        // Clear user input fields
        document.querySelector('input[name="issue_title"]').value = "";
        document.querySelector('textarea[name="issue_description"]').value = "";
        document.querySelector('input[name="screenshot_url"]').value = "";

        // Show the modal
        var issueModal = new bootstrap.Modal(document.getElementById('createIssueModal'));
        issueModal.show();
    }

    document.getElementById("issueForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission

        let formData = new FormData(this); // Collect form data

        fetch("{{ route('issue.store') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest", // Ensure Laravel detects AJAX request
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content // Get CSRF token from meta tag
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                var issueModal = bootstrap.Modal.getInstance(document.getElementById('createIssueModal'));
                issueModal.hide();

                // Refresh issue list
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting the form.');
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Handle Pass
    document.getElementById("passBtn").addEventListener("click", function () {
        updateTestCaseStatus(selectedTestCaseId, "Pass");
        $("#executeTestModal").modal("hide");
    });

    // Handle N/A
    document.getElementById("naBtn").addEventListener("click", function () {
        updateTestCaseStatus(selectedTestCaseId, "N/A");
        $("#executeTestModal").modal("hide");
    });

    // Handle Fail (Open Issue Creation Modal)
    document.getElementById("failBtn").addEventListener("click", async function () {
        let issueNumber = await generateIssueNumber(currentProjectId);

        // Populate issue modal
        $("#generatedIssueNumber").text(`Issue Number: ${issueNumber}`);
        $('input[name="issue_number"]').val(issueNumber);
        $("#createIssueModal").modal("show");

        // Hide execution modal
        $("#executeTestModal").modal("hide");
    });
});

</script>


<script>
    let currentProjectId = {{$project -> id }};
    let issueCounters = {};
    let selectedTestCaseId = null;
    let auditTrail = {};

    async function executeTestCase(testCaseId) {
    selectedTestCaseId = testCaseId;

    // Find the test case data from the Blade-passed JSON array
    const testCase = @json($testCases).find(tc => tc.id == testCaseId);

    if (!testCase) {
        console.error("Test case not found.");
        return;
    }

    // Fill modal fields
    $('#modal_test_case_id').val(testCase.id);
    $('#test_case_no').val(testCase.test_case_no);
    $('#test_title').val(testCase.test_title);
    $('#test_step').val(testCase.test_step);
    $('#test_environment').val("{{ request('environment') }}");
    $('#category').val(testCase.category?.name ?? '');
    $('#service').val(testCase.service?.name ?? '');
    $('#test_environment').val(testCase.test_environment ?? 'SIT');
    $('#tester').val(testCase.tester ?? "Tester Name");
    $('#date').val(new Date().toISOString().split('T')[0]); //this is based on date today


    // Show the Execute Test Modal
    const modal = new bootstrap.Modal(document.getElementById('executeTestModal'));
    modal.show();

    // Handle Status Change (When user selects Pass/Fail/N/A)
    $('#status').off('change').on('change', async function () {
        let newStatus = $(this).val();

        if (newStatus === "Fail") {
            // Generate issue number
            const issueNumber = await generateIssueNumber(currentProjectId);

            // Fill issue number in the "Create Issue" modal
            $('#generatedIssueNumber').text(`Issue Number: ${issueNumber}`);

            // Show the Create Issue modal instead of Swal
            $('#createIssueModal').modal('show');

            // Record issue creation in audit trail
            recordAuditTrail(testCaseId, 'Issue Created', newStatus, issueNumber);
        }

        // Update test case status
        updateTestCaseStatus(testCaseId, newStatus);
    });
}

// async function executeTestCase(testCaseId) {
//     selectedTestCaseId = testCaseId;

//     const result = await Swal.fire({
//         title: 'Execute Test Case',
//         text: 'Choose status for this test case.',
//         icon: 'question',
//         showCancelButton: true,
//         confirmButtonText: 'Pass',
//         cancelButtonText: 'Fail',
//         showDenyButton: true,
//         denyButtonText: 'N/A',
//         confirmButtonColor: '#28a745',
//         cancelButtonColor: '#dc3545',
//         denyButtonColor: '#6c757d',
//     });

//     let newStatus = 'Not Run';

//     if (result.isConfirmed) {
//         newStatus = 'Pass';
//     } else if (result.isDenied) {
//         newStatus = 'N/A';
//     } else if (result.dismiss === Swal.DismissReason.cancel) {
//         newStatus = 'Fail';

//         const issueNumber = await generateIssueNumber(currentProjectId);

//         const subResult = await Swal.fire({
//             title: "There's something wrong in your test case",
//             text: `Generated Issue Number: ${issueNumber}`,
//             icon: 'error',
//             showCancelButton: true,
//             confirmButtonText: 'Create Issue',
//             cancelButtonText: 'Cancel',
//             confirmButtonColor: '#007bff',
//             cancelButtonColor: '#6c757d',
//         });

//         if (subResult.isConfirmed) {
//             $(`#issue-number-${testCaseId}`).text(issueNumber);
//             recordAuditTrail(testCaseId, 'Issue Created', newStatus, issueNumber);

//             // Set issue number in modal and show the modal instead of Swal
//             $('#generatedIssueNumber').text(`Issue Number: ${issueNumber}`);
//             $('#createIssueModal').modal('show');
//         }
//     }

//     if (newStatus !== 'Not Run') {
//         updateTestCaseStatus(testCaseId, newStatus);
//     }
// }


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