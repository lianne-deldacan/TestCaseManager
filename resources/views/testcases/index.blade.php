@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Test Case Form</h2>

        <form id="testcaseForm" action="{{ route('testcases.store') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projectId ?? '' }}">
            <input type="hidden" name="status" value="Not Run">
            <div class="row g-3">
                <div class="form-group col-6">
                    <label for="project-name" class="form-label">Project Name</label>
                    <input type="text" id="project-name" class="form-control" value="{{ $project->name }}" disabled>
                </div>
                <div class="form-group col-6">
                    <label for="service">Service</label>
                    <input type="text" id="service" class="form-control" value="{{ $service ?? 'No service available' }}" disabled>
                    <input type="hidden" name="service" value="{{ $service ?? 'No service available' }}">
                </div>
                <div class="col-md-6">
                    <label for="test_environment" class="form-label">Test Environment</label>
                    <select id="test_environment" name="test_environment" class="form-control" required>
                        <option value="SIT">SIT</option>
                        <option value="UAT">UAT</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="tester" class="form-label">Tester</label>
                    <!-- <input type="text" id="tester" name="tester" class="form-control" value="Tester Name"> -->
                    <select id="tester" name="tester_id" class="form-control" required>
                        <!-- TODO: change to SELECT 2 plugin with pagination -->
                        @foreach($testers as $tester)
                        <option value="{{ $tester->id }}">{{ $tester->name }}</option>
                        @endforeach
                    </select>
                     
                </div>
                <div class="col-md-6">
                    <label for="test_case_no" class="form-label">Test Case No.</label>
                    <input type="text" id="test_case_no" name="test_case_no" class="form-control" required maxlength="6">
                </div>
                <div class="col-md-6">
                    <label for="test_title" class="form-label">Test Title</label>
                    <input type="text" id="test_title" name="test_title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="test_step" class="form-label">Test Step</label>
                    <textarea id="test_step" name="test_step" rows="1" cols="50" required></textarea>
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        @foreach($categories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_of_input" class="form-label">Date</label>
                    <input type="date" id="date_of_input" name="date_of_input" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priority</label>
                    <select id="priority" name="priority" class="form-control" required>
                        <!-- <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option> -->
                        @foreach(\App\Models\TestCase::PRIORITIES as $k => $priority)
                            <option value="{{ $k }}">{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn custom-btn btn-lg">
                    <i class="bi bi-plus-circle"></i> Add
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mt-4 d-flex flex-wrap align-items-center gap-2">
    <form id="importForm" action="{{ route('testcases.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
        @csrf
        <div class="d-flex align-items-center border rounded px-2">
            <input type="file" name="file" class="form-control border-0">
        </div>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-upload"></i> Import
        </button>
    </form>

    <a href="{{ route('testcases.export.csv', ['project_id' => $project->id]) }}" class="btn btn-info">
        <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
    </a>
    <a href="{{ route('testcases.export.excel', ['project_id' => $project->id]) }}" class="btn btn-warning">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </a>
    <a href="{{ route('testcases.export.pdf', ['project_id' => $project->id]) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </a>
    <button class="btn btn-dark" onclick="printTable()">
        <i class="bi bi-printer"></i> Print
    </button>
</div>


<!-- TABLE -->

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
            <tr>
                <td>{{ $testCase->test_case_no }}</td>
                <td>{{ $testCase->test_title }}</td>
                <td>{{ $testCase->test_step }}</td>
                <td>{{ $testCase->category->name }}</td>
                <td>{{ $testCase->priority }}</td>
                <td>{{ $testCase->status }}</td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-primary edit-btn"
                        data-id="{{ $testCase->id }}"
                        data-title="{{ $testCase->test_title }}"
                        data-category="{{ $testCase->category_id }}"
                        data-date="{{ $testCase->date_of_input }}"
                        data-step="{{ $testCase->test_step }}"
                        data-priority="{{ $testCase->priority }}"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('testcases.destroy', $testCase->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this test case?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('executeTestcases') }}" class="btn btn-success btn-lg">
        Execute
    </a>



</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST" action="{{route("testcases.update")}}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Test Case</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label for="edit-title" class="form-label">Test Title</label>
                        <input type="text" name="test_title" id="edit-title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-date" class="form-label">Date</label>
                        <input type="date" name="date_of_input" id="edit-date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-step" class="form-label">Test Step</label>
                        <input type="text" name="test_step" id="edit-step" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-priority" class="form-label">Priority</label>
                        <select name="priority" id="edit-priority" class="form-control" required>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.getElementById("editModal");
        const editForm = document.getElementById("editForm");

        document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function() {
            // Retrieve data from data attributes
            const id = this.getAttribute("data-id");
            const title = this.getAttribute("data-title");
            const category = this.getAttribute("data-category");
            const date = this.getAttribute("data-date");
            const step = this.getAttribute("data-step");
            const priority = this.getAttribute("data-priority");

            // Populate modal fields
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-title").value = title;
            document.getElementById("category_id").value = category;
            document.getElementById("edit-date").value = date;
            document.getElementById("edit-step").value = step;
            document.getElementById("edit-priority").value = priority;
        });
    });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.getElementById("editModal");
        editModal.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute("data-id");
            const title = button.getAttribute("data-title");
            const category = button.getAttribute("data-category");
            const date = button.getAttribute("data-date");
            const step = button.getAttribute("data-step");
            const priority = button.getAttribute("data-priority");

            const form = document.getElementById("editForm");
            form.action = `/testcases/${id}`;
            form.querySelector("#edit-id").value = id;
            form.querySelector("#edit-title").value = title;
            form.querySelector("#edit-category").value = category;
            form.querySelector("#edit-date").value = date;
            form.querySelector("#edit-step").value = step;
            form.querySelector("#edit-priority").value = priority;
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let selectedTestCaseId = null;

        document.querySelectorAll(".clickable-row").forEach(row => {
            row.addEventListener("click", function() {
                // Remove highlighting from all rows
                document.querySelectorAll(".clickable-row").forEach(r => r.classList.remove("table-active"));

                // Highlight selected row
                this.classList.add("table-active");

                // Store selected test case ID
                selectedTestCaseId = this.getAttribute("data-id");

                // Enable Execute button
                document.getElementById("executeBtn").disabled = false;
            });
        });

        document.getElementById("executeBtn").addEventListener("click", function() {
            if (selectedTestCaseId) {
                window.location.href = `/testcases/${selectedTestCaseId}/execute`;
            }
        });
    });
</script>


<script>
    function printTable() {
        var printWindow = window.open('', '', 'width=800,height=1000');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: A4 landscape; margin: 10mm; }');
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 10px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 12px; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(document.getElementById('testcasesTable').outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!--DELETE FUNCTION-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let dataTable;

        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#testcasesTable')) {
            dataTable = $('#testcasesTable').DataTable(); // Use the existing instance
        } else {
            dataTable = $('#testcasesTable').DataTable(); // Initialize DataTable
        }

        // Handle delete button click
        $('#testcasesTable').on('click', '.delete-btn', function() {
            const button = $(this);
            const rowId = button.data('id'); // Get row ID

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, proceed with deletion
                    fetch(`/delete-case/${rowId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                        })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then((data) => {
                            console.log('Row deleted successfully:', data);
                            const row = button.closest('tr');
                            dataTable.row(row).remove().draw(); // Remove and redraw table

                            // SweetAlert success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'The row has been deleted.',
                            });
                        })
                        .catch((error) => {
                            console.error('Error:', error);

                            // SweetAlert error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Unable to delete the row. Please try again.',
                            });
                        });
                }
            });
        });
    });
</script>


<!--Swal for Add-->
<script>
    // SweetAlert for Add
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form[action='{{ route('testcases.store') }}']");

        if (!form) return; // Prevent errors if form is not found

        form.addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(form);

            fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value,
                        "Accept": "application/json"
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Test Case Added!",
                            text: "Your test case has been successfully added.",
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            location.reload(); // Reload AFTER Swal message disappears
                        });

                        form.reset();

                        let newRow = `
                    <tr>
                        <td>${data.test_case.test_case_no}</td>
                        <td>${data.test_case.test_title}</td>
                        <td>${data.test_case.category}</td>
                        <td>${data.test_case.date_of_input}</td>
                        <td>${data.test_case.test_step}</td>
                        <td>${data.test_case.priority}</td>
                    </tr>`;
                        document.querySelector("#testcasesTable tbody").innerHTML += newRow;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.message || "Something went wrong!",
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Failed to add test case. Please try again.",
                    });
                });
        });
    });
</script>

<!--Swal for Import-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const importForm = document.getElementById("importForm");

        importForm.addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(importForm);

            fetch(importForm.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        Swal.fire({
                            icon: "success",
                            title: "Import Successful!",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    console.error("Import Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Import Failed!",
                        text: "Something went wrong. Please try again.",
                    });
                });
        });
    });
</script>



@endsection