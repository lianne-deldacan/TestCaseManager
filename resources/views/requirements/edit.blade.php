@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Update {{ $requirement->requirement_number }}</h2>
        <form action="{{ route('requirements.update', $requirement->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="project_id" value="{{ $requirement->project_id }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="project-name" class="form-label">Project Name</label>
                    <input type="text" id="project-name" class="form-control" value="{{ $requirement->project->name }}" disabled>
                </div>
                <div class="col-md-4">
                    <label for="service">Service</label>
                    <input type="text" id="service" class="form-control" value="{{ $requirement->project->service }}" disabled>
                </div>

                <div class="col-md-4">
                    <label for="user" class="form-label">User</label>
                    <input type="text" id="user" name="user" class="form-control" value="{{ $requirement->user }}">
                </div>

                <div class="col-md-6">
                    <label for="requirement_number" class="form-label">Requirement No.</label>
                    <input type="text" id="requirement_number" name="number" class="form-control"
                        value="{{ $requirement->number }}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="requirement_title" class="form-label">Requirement Title</label>
                    <input type="text" id="requirement_title" name="title" class="form-control"
                        value="{{ $requirement->title }}" required>
                </div>
                <div class="col-md-12">
                    <label for="requirement_description" class="form-label">Description</label>
                    <textarea id="requirement_description" name="description" class="form-control" rows="3" style="resize: vertical;">{{ $requirement->description }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" id="category" name="category" class="form-control" value="{{ $requirement->category }}">
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">Input Date</label>
                    <input type="date" id="date" name="date" class="form-control"
                        value="{{ $requirement->date ? $requirement->date->format('Y-m-d') : '' }}" required>
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
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-check-circle"></i> Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<!-- For Requirements Type -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
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
        requirementTypeDropdown.addEventListener("change", function() {
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
        resetButton.addEventListener("click", function() {
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
    // SweetAlert for Add
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form[action='{{ route('requirements.update', $requirement->id) }}']");

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
                .then((response) => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text)
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    Swal.fire({
                        icon: "success",
                        title: "Requirement Updated!",
                        text: "Requirement has been successfully updated.",
                    }).then(() => {
                        window.location.href = document.referrer || "{{ route('requirements.create') }}";
                    });
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong! " + error,
                    });
                });
        });
    });
</script>