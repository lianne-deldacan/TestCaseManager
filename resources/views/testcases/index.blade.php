@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Test Case Form</h2>

        <form action="{{ route('testcases.store') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projectId }}">
            <input type="hidden" name="status" value="Not Run">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Project Name</label>
                    <input type="text" class="form-control" value="{{ $projectName }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Service</label>
                    <input type="text" class="form-control" value="{{ $service }}" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tester" class="form-label">Tester</label>
                    <input type="text" id="tester" name="tester" class="form-control" value="Tester Name">
                </div>
                <div class="col-md-6">
                    <label for="test_case_no" class="form-label">Test Case No.</label>
                    <input type="text" id="test_case_no" name="test_case_no" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="test_title" class="form-label">Test Title</label>
                    <input type="text" id="test_title" name="test_title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="test_step" class="form-label">Test Step</label>
                    <input type="text" id="test_step" name="test_step" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category_id" class="form-control" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-plus-circle"></i> Add</button>
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

    <a href="{{ route('testcases.export.csv') }}" class="btn btn-info">
        <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
    </a>
    <a href="{{ route('testcases.export.excel') }}" class="btn btn-warning">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </a>
    <a href="{{ route('testcases.export.pdf') }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </a>
    <button class="btn btn-dark" onclick="printTable()">
        <i class="bi bi-printer"></i> Print
    </button>
</div>

<div class="mt-4">
    <table id="testcasesTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Project Name</th>
                <th>Service</th>
                <th>Tester</th>
                <th>Test Case No.</th>
                <th>Test Title</th>
                <th>Test Step</th>
                <th>Category</th>
                <th>Date of Input</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testCases as $case)
            <tr>
                <td>{{ $case->project->name }}</td>
                <td>{{ $case->project->service }}</td>
                <td>{{ $case->tester }}</td>
                <td>{{ $case->test_case_no }}</td>
                <td>{{ $case->test_title }}</td>
                <td>{{ $case->test_step }}</td>
                <td>{{ $case->category->name }}</td>
                <td>{{ $case->date_of_input }}</td>
                <td>{{ $case->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
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
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category_id" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = document.querySelector("meta[name='csrf-token']")?.content || document.querySelector("input[name='_token']")?.value;
        const editModal = document.getElementById("editModal");
        const editForm = document.getElementById("editForm");
        const importForm = document.getElementById("importForm");

        // Initialize DataTable
        if (!$.fn.DataTable.isDataTable("#testcasesTable")) {
            $("#testcasesTable").DataTable();
        }

        // Handle Edit Modal
        editModal?.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            editForm.action = `/testcases/${button.getAttribute("data-id")}`;
            editForm.querySelector("#editTitle").value = button.getAttribute("data-title");
            editForm.querySelector("#editStep").value = button.getAttribute("data-step");
        });

        // Delete Test Case with SweetAlert Confirmation
        document.querySelector("#testcasesTable")?.addEventListener("click", function(event) {
            if (event.target.matches(".delete-btn")) {
                const rowId = event.target.getAttribute("data-id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/delete-case/${rowId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                    "Content-Type": "application/json"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Deleted!", "The test case has been removed.", "success");
                                    event.target.closest("tr").remove();
                                } else {
                                    Swal.fire("Failed!", "Unable to delete the test case.", "error");
                                }
                            })
                            .catch(error => {
                                console.error("Delete Error:", error);
                                Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                            });
                    }
                });
            }
        });

        // Add Test Case with SweetAlert Success
        document.querySelector("form[action='{{ route('testcases.store') }}']")?.addEventListener("submit", function(event) {
            event.preventDefault();
            const form = event.target;
            let formData = new FormData(form);

            fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Test Case Added!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        form.reset();
                        document.querySelector("#testcasesTable tbody").insertAdjacentHTML("beforeend", `
                        <tr>
                            <td>${data.test_case.test_case_no}</td>
                            <td>${data.test_case.test_title}</td>
                            <td>${data.test_case.category}</td>
                            <td>${data.test_case.date_of_input}</td>
                            <td>${data.test_case.test_step}</td>
                            <td>${data.test_case.priority}</td>
                            <td>${data.test_case.severity}</td>
                            <td>${data.test_case.screenshot}</td>
                        </tr>
                    `);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.message || "Something went wrong!"
                        });
                    }
                })
                .catch(error => {
                    console.error("Add Test Case Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Failed to add test case. Please try again."
                    });
                });
        });

        // Import Test Cases with SweetAlert
        importForm?.addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(importForm);

            fetch(importForm.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        Swal.fire({
                            icon: "success",
                            title: "Import Successful!",
                            text: data.message,
                            timer: 2000
                        }).then(() => location.reload());
                    }
                })
                .catch(error => {
                    console.error("Import Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Import Failed!",
                        text: "Something went wrong. Please try again."
                    });
                });
        });

        // Print Table
        window.printTable = function() {
            let printWindow = window.open("", "", "width=800,height=1000");
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Table</title>
                        <style>
                            @page { size: A4 landscape; margin: 10mm; }
                            body { font-family: Arial, sans-serif; margin: 10px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 12px; }
                        </style>
                    </head>
                    <body>
                        ${document.getElementById("testcasesTable").outerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    });
</script>
@endsection