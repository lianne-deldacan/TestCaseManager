@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Test Case Form</h2>

<form action="{{ route('testcases.store') }}" method="POST">
    @csrf
    <input type="hidden" name="status" value="Not Run">

    <div class="row g-3">
        <div class="col-md-6">
            <label for="service" class="form-label">Service</label>
            <select id="service" name="service" class="form-control">
                <option value="">-- Select Service --</option>
                @foreach($services as $service)
                    <option value="{{ $service }}">{{ $service }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="project_id" class="form-label">Project</label>
            <select id="project_id" name="project_id" class="form-control">
                <option value="">-- Select Project --</option>
            </select>
        </div>

        <!-- Other form fields -->
        @foreach (['tester', 'test_environment', 'test_case_no', 'test_title', 'test_step', 'category_id', 'date_of_input', 'priority'] as $field)
        <div class="col-md-6">
            <label for="{{ $field }}" class="form-label">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
            <input type="{{ $field === 'date_of_input' ? 'date' : 'text' }}" id="{{ $field }}" class="form-control" disabled>
        </div>
        @endforeach
    </div>
</form>

    </div>
</div>



<script>
$(document).ready(function () {
    // Listen for changes in the Service dropdown
    $('#service').on('change', function () {
        let service = $(this).val();
        let projectDropdown = $('#project_id');

        // Reset the Project dropdown
        projectDropdown.empty().append('<option value="">-- Select Project --</option>');

        // If a service is selected, make the AJAX request
        if (service) {
            $.ajax({
                url: "{{ route('projects.getByService') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    service: service,
                },
                success: function (projects) {
                    $.each(projects, function (id, name) {
                        projectDropdown.append(`<option value="${id}">${name}</option>`);
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseJSON);
                    alert('Error fetching projects: ' + (xhr.responseJSON?.error || 'Unknown error.'));
                }
            });
        }
    });

    // Listen for changes in the Project dropdown
    $('#project_id').on('change', function () {
        let projectId = $(this).val();

        if (projectId) {
            $.ajax({
                url: "{{ url('testcases/project-details') }}/" + projectId,
                type: "GET",
                success: function (response) {
                    // Populate disabled input fields
                    $('#test_case_no').val(response.project.test_case_no || '');
                    $('#test_title').val(response.project.test_title || '');
                    $('#test_step').val(response.project.test_step || '');
                    $('#priority').val(response.project.priority || '');
                    $('#date_of_input').val(response.project.date_of_input || '');

                    // Populate test cases in the table
                    let tableBody = $('#testcase-table-body');
                    tableBody.empty();

                    if (response.testCases && response.testCases.length > 0) {
                        response.testCases.forEach(testCase => {
                            tableBody.append(`
                                <tr>
                                    <td>${testCase.test_case_no}</td>
                                    <td>${testCase.test_title}</td>
                                    <td>${testCase.test_step}</td>
                                    <td>${testCase.priority}</td>
                                    <td>${testCase.date_of_input}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="5">No test cases found.</td></tr>');
                    }
                },
                error: function (xhr) {
                    alert('Error fetching project details: ' + (xhr.responseJSON?.error || 'Unknown error.'));
                }
            });
        }
    });
});
</script>



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

@if(isset($project))
    <a href="{{ route('testcases.export.csv', ['project_id' => $project->id]) }}" class="btn btn-info">
        <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
    </a>

    <a href="{{ route('testcases.export.excel', ['project_id' => $project->id]) }}" class="btn btn-warning">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </a>

    <a href="{{ route('testcases.export.pdf', ['project_id' => $project->id]) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </a>
@else
    <p>No project selected for export.</p>
@endif


    <button class="btn btn-dark" onclick="printTable()">
        <i class="bi bi-printer"></i> Print
    </button>
</div>

   <h3 class="mt-5">Test Cases</h3>
<table id="testcases-table" class="table table-bordered">
    <thead>
        <tr>
            <th>Test Case No</th>
            <th>Test Title</th>
            <th>Test Step</th>
            <th>Tester</th>
            <th>Environment</th>
            <th>Date of Input</th>
            <th>Priority</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="7" class="text-center">No test cases found.</td>
        </tr>
    </tbody>
</table>

</div>




// <!-- Edit Modal -->
// {{-- <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
//     <div class="modal-dialog">
//         <div class="modal-content">
//             <form id="editForm" method="POST">
//                 @csrf
//                 @method('PUT')
//                 <div class="modal-header">
//                     <h5 class="modal-title" id="editModalLabel">Edit Test Case</h5>
//                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
//                 </div>
//                 <div class="modal-body">
//                     <input type="hidden" name="id" id="edit-id">
//                     <div class="mb-3">
//                         <label for="edit-title" class="form-label">Test Title</label>
//                         <input type="text" name="test_title" id="edit-title" class="form-control" required>
//                     </div>
//                     <div class="mb-3">
//                         <label for="category_id" class="form-label">Category</label>
//                         <select name="category_id" id="category_id" class="form-control">
//                             @foreach($categories as $category)
//                             <option value="{{ $category->id }}">{{ $category->name }}</option>
//                             @endforeach
//                         </select>
//                     </div>
//                     <div class="mb-3">
//                         <label for="edit-date" class="form-label">Date</label>
//                         <input type="date" name="date_of_input" id="edit-date" class="form-control" required>
//                     </div>
//                     <div class="mb-3">
//                         <label for="edit-step" class="form-label">Test Step</label>
//                         <input type="text" name="test_step" id="edit-step" class="form-control" required>
//                     </div>
//                     <div class="mb-3">
//                         <label for="edit-priority" class="form-label">Priority</label>
//                         <select name="priority" id="edit-priority" class="form-control" required>
//                             <option value="High">High</option>
//                             <option value="Medium">Medium</option>
//                             <option value="Low">Low</option>
//                         </select>
//                     </div>
//                 </div>
//                 <div class="modal-footer">
//                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
//                     <button type="submit" class="btn btn-primary">Save Changes</button>
//                 </div>
//             </form>
//         </div>
//     </div>
// </div> --}}

<script>
  document.getElementById('project_id').addEventListener('change', function() {
    let projectId = this.value;
    if (projectId) {
        fetch(`/get-project-details/${projectId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('service').value = data.service;
                document.getElementById('tester').value = data.tester;
                document.getElementById('test_case_no').value = data.test_case_no;
                document.getElementById('test_title').value = data.test_title;
                document.getElementById('test_step').value = data.test_step;
                document.getElementById('category_id').value = data.category;
                document.getElementById('date_of_input').value = data.date_of_input;
                document.getElementById('priority').value = data.priority;
            });
    }
});

</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.getElementById("editModal");
        const editForm = document.getElementById("editForm");

        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                // Populate modal fields with button data
                const id = this.getAttribute("data-id");
                const title = this.getAttribute("data-title");
                const category = this.getAttribute("data-category");
                const date = this.getAttribute("data-date");
                const step = this.getAttribute("data-step");
                const priority = this.getAttribute("data-priority");

                editForm.action = `/testcases/${id}`;
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
    document.addEventListener("DOMContentLoaded", function () {
        let selectedTestCaseId = null;

        document.querySelectorAll(".clickable-row").forEach(row => {
            row.addEventListener("click", function () {
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

        document.getElementById("executeBtn").addEventListener("click", function () {
            if (selectedTestCaseId) {
                // Ensure the URL points to the correct route
                window.location.href = `/execute-testcases?testCaseId=${selectedTestCaseId}`;
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

        form.addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(form);

            fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value,
                    },
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Test Case Added!",
                            text: "Your test case has been successfully added.",
                            showConfirmButton: false,
                            timer: 1500,
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
                .catch((error) => {
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