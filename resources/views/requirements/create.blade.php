@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Add Requirements Form</h2>

        <form action="{{ route('requirements.store') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ request('project_id') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="project-name" class="form-label">Project Name</label>
                    <input type="text" id="project-name" class="form-control" value="{{ $projectName }}" disabled>
                </div>
                <div class="col-md-4">
                    <label for="service">Service</label>
                    <input type="text" id="service" class="form-control" value="{{ $service ?? 'No service available' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label for="user" class="form-label">User</label>
                    <input type="text" id="user" name="user" class="form-control" value="">
                </div>
                <div class="col-md-6">
                    <label for="requirement_number" class="form-label">Requirement No.</label>
                    <input type="text" id="requirement_number" name="number" class="form-control"
                        value="{{ $requirementNumber }}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="requirement_title" class="form-label">Requirement Title</label>
                    <input type="text" id="requirement_title" name="title" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label for="requirement_description" class="form-label">Description</label>
                    <textarea id="requirement_description" name="description" class="form-control" rows="3" style="resize: vertical;"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" id="category" name="category" class="form-control" value="">
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">Input Date</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="flex-grow-1">
                        <label for="requirement_type" class="form-label">Requirement Type</label>
                        <select id="requirement_type" name="type" class="form-control" required>
                            <option value="" disabled selected>Select Requirement Type</option>
                            <option value="Functional">Functional</option>
                            <option value="Non-Functional">Non-Functional</option>
                            <option value="Nice To Have">Nice To Have</option>
                        </select>
                    </div>
                    <button type="button" id="resetRequirementType" class="btn btn-secondary ms-2">Reset</button>
                </div>

                <!-- Change Request -->
                <div class="col-md-12" id="change_request_container" style="display: none;">
                    <label for="change_request" class="form-label">Specify Change Request</label>
                    <textarea id="change_request" name="change_request_input" class="form-control" rows="3" style="resize: vertical;"></textarea>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-plus-circle"></i> Add</button>
            </div>
        </form>
    </div>
</div>

<div class="mt-4">
    <div class="mt-4 d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('requirements.export.csv', ['project_id' => $project->id]) }}" class="btn btn-info">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
        </a>
        <a href="{{ route('requirements.export.excel', ['project_id' => $project->id]) }}" class="btn btn-warning">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="{{ route('requirements.export.pdf', ['project_id' => $project->id]) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <button class="btn btn-dark" onclick="printTable()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>
    <table id="testcasesTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Project Name</th>
                <th>User</th>
                <th>Requirement No.</th>
                <th>Requirement Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Requirement Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requirements as $requirement)
            <tr>
                <td>{{ $requirement->project->name }}</td>
                <td>{{ $requirement->user }}</td>
                <td>{{ $requirement->number }}</td>
                <td>{{ $requirement->title }}</td>
                <td>{{ $requirement->description }}</td>
                <td>{{ $requirement->category }}</td>
                <td>{{ $requirement->type }}</td>
                <td>
                    <a href="{{ route('requirements.edit', $requirement->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<!-- <script>
    function printTable() {
        var printWindow = window.open('', '', 'width=800,height=1000');
        printWindow.document.write('<html><head><title>Print Requirements Table</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: A4 landscape; margin: 10mm; }');
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 10px; }');
        printWindow.document.write('h2 { text-align: center; margin-bottom: 15px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 12px; }');
        printWindow.document.write('th { background-color: #f2f2f2; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Requirements Table</h2>');
        printWindow.document.write(document.getElementById('testcasesTable').outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script> -->

<!-- For Requirements Type -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const requirementTypeDropdown = document.getElementById("requirement_type");
        const resetButton = document.getElementById("resetRequirementType");
        const changeRequestContainer = document.getElementById("change_request_container");
        const changeRequestInput = document.getElementById("change_request");

        // Store the original options
        const originalOptions = `
            <option value="" disabled selected>Select Requirement Type</option>
            <option value="Functional">Functional</option>
            <option value="Non-Functional">Non-Functional</option>
            <option value="Nice To Have">Nice To Have</option>
        `;

        // Non-Functional Requirement Options
        const nonFunctionalOptions = `
            <option value="" disabled selected>Select Non-Functional Type</option>
            <option value="Security">Security</option>
            <option value="Performance">Performance</option>
            <option value="Usability">Usability</option>
            <option value="Change Request">Change Request</option>
        `;

        // Handle Requirement Type selection
        requirementTypeDropdown.addEventListener("change", function () {
            if (this.value === "Non-Functional") {
                // Change to non-functional options
                this.innerHTML = nonFunctionalOptions;
            } else if (this.value === "Change Request") {
                // Display Change Request input field when "Change Request" is selected
                changeRequestContainer.style.display = "block";
                requirementTypeDropdown.removeAttribute("name"); // Remove name so form doesn't submit it
                changeRequestInput.setAttribute("name", "type"); // Assign name to change_request input
            } else {
                // Hide Change Request field if any other type is selected
                changeRequestContainer.style.display = "none";
                changeRequestInput.removeAttribute("name");
                requirementTypeDropdown.setAttribute("name", "type");
            }
        });

        // Reset button event listener
        resetButton.addEventListener("click", function () {
            requirementTypeDropdown.innerHTML = originalOptions; // Reset options
            requirementTypeDropdown.value = ""; // Reset selection
            changeRequestContainer.style.display = "none"; // Hide Change Request input field
            changeRequestInput.removeAttribute("name"); // Remove name to avoid submission issues
            requirementTypeDropdown.setAttribute("name", "type");
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        document.getElementById("date").value = today;
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form[action='{{ route('requirements.store') }}']");

        form.addEventListener("submit", function(event) {
            event.preventDefault();

            // Ensure requirement_type updates before submission
            let requirementType = document.getElementById("requirement_type");
            let nonFunctionalType = document.getElementById("non_functional_type");

            if (requirementType.value === "Non-Functional" && nonFunctionalType.value) {
                requirementType.value = nonFunctionalType.value; // Assign non-functional type
            }

            let formData = new FormData(form);

            fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value,
                    },
                })
                .then(response => {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.includes("application/json")) {
                        return response.json(); // Parse JSON only if response is JSON
                    } else {
                        return response.text(); // Return as text for debugging
                    }
                })
                .then((data) => {
                    if (typeof data === "string") {
                        console.error("Received non-JSON response:", data); // Log HTML response
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Unexpected response from the server. Check console for details.",
                        });
                        return;
                    }

                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Requirement Added!",
                            text: "Requirement has been successfully added.",
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            location.reload(); // Refresh the page
                            form.reset();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error!",
                            text: data.message || "Please check your inputs.",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Failed to add requirement. Please try again.",
                    });
                });
        });
    });
</script>