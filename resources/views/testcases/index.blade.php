@extends('layouts.app')

@section('content')



<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Test Case Form</h2>

        <form action="{{ route('testcases.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control" value="{{ $projectName }}" readonly>
            </div>

            <input type="hidden" name="project_id" value="{{ $projectId }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="test_case_no" class="form-label">Test Case No.</label>
                    <input type="text" id="test_case_no" name="test_case_no" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="test_environment" class="form-label">Test Environment</label>
                    <select id="test_environment" name="test_environment" class="form-control" required>
                        <option value="development">Development (DEV)</option>
                        <option value="testing">Testing (TEST)</option>
                        <option value="staging">Staging (STG)</option>
                        <option value="uat">UAT (User Acceptance Testing)</option>
                        <option value="performance_testing">Performance Testing</option>
                        <option value="security_testing">Security Testing</option>
                        <option value="production">Production (PROD)</option>
                        <option value="sandbox">Sandbox</option>
                        <option value="integration_testing">Integration Testing</option>
                        <option value="regression_testing">Regression Testing</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tester" class="form-label">Tester</label>
                    <input type="text" id="tester" name="tester" class="form-control" required>
                </div>
                <div class="col-md-6"> 
                   <label for="date_of_input" class="form-label">Date of Input</label>
                   <input type="text" id="date_of_input" name="date_of_input" class="form-control" value="{{ date('Y-m-d') }}" required readonly>
                </div>
                <div class="col-md-6">
                    <label for="test_title" class="form-label">Test Title</label>
                    <input type="text" id="test_title" name="test_title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="screenshot" class="form-label">Screenshot (URL or Text)</label>
                    <input type="text" id="screenshot" name="screenshot" class="form-control">
                </div>
                <div class="col-md-12">
                    <label for="test_description" class="form-label">Test Description</label>
                    <textarea id="test_description" name="test_description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="Pending" {{ old('status', $status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Pass" {{ old('status', $status ?? '') == 'Pass' ? 'selected' : '' }}>Pass</option>
                        <option value="Fail" {{ old('status', $status ?? '') == 'Fail' ? 'selected' : '' }}>Fail</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="priority" class="form-label">Priority</label>
                    <select id="priority" name="priority" class="form-select" required>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="severity" class="form-label">Severity</label>
                    <select id="severity" name="severity" class="form-select" required>
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

    <div class="mt-4 d-flex flex-wrap align-items-center gap-2">
        <form id="importForm" action="{{ route('testcases.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
            @csrf
            <div class="d-flex align-items-center border rounded px-2">
                <input type="file" name="file" class="form-control border-0">
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Import</button>
        </form>

        <a href="{{ route('testcases.export.csv') }}" class="btn btn-info"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
        <a href="{{ route('testcases.export.excel') }}" class="btn btn-warning"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
        <a href="{{ route('testcases.export.pdf') }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
        <button class="btn btn-dark" onclick="printTable()"><i class="bi bi-printer"></i> Print</button>
    </div>

    <div class="mt-4">
    <table id="testcasesTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Project ID</th>
                <th>Project Name</th>
                <th>Test Case No.</th>
                <th>Environment</th>
                <th>Tester</th>
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Severity</th>
                <th>Screenshot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testCases as $case)
            <tr>
                <td>{{ $case->project->id }}</td>
                <td>{{ $case->project->name }}</td>
                <td>{{ $case->test_case_no }}</td>
                <td>{{ $case->test_environment }}</td>
                <td>{{ $case->tester }}</td>
                <td>{{ $case->date_of_input }}</td>
                <td>{{ $case->test_title }}</td>
                <td>{{ $case->test_description }}</td>
                <td>{{ $case->status }}</td>
                <td>{{ $case->priority }}</td>
                <td>{{ $case->severity }}</td>
                <td>{{ $case->screenshot }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

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

<!--Swal for Add-->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form[action='{{ route('testcases.store') }}']");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value
            }
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Test Case Added!",
                    text: "Your test case has been successfully added.",
                    showConfirmButton: false,
                    timer: 1500
                });

                form.reset();

                let newRow = `
                <tr>
                    <td>${data.test_case.project_id}</td>
                    <td>${data.test_case.project_name}</td>
                    <td>${data.test_case.test_case_no}</td>
                    <td>${data.test_case.test_environment}</td>
                    <td>${data.test_case.tester}</td>
                    <td>${data.test_case.date_of_input}</td>
                    <td>${data.test_case.test_title}</td>
                    <td>${data.test_case.test_description}</td>
                    <td>${data.test_case.status}</td>
                    <td>${data.test_case.priority}</td>
            <td>${data.test_case.severity}</td>
        <td>${data.test_case.screenshot}</td>
    </tr>
`;
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
document.addEventListener("DOMContentLoaded", function () {
    const importForm = document.getElementById("importForm");

    importForm.addEventListener("submit", function (event) {
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
