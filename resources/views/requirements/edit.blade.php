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
                <div class="mb-3">
                    <label for="project-name" class="form-label">Project Name</label>
                    <input type="text" id="project-name" class="form-control" value="{{ $requirement->project->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="service">Service</label>
                    <input type="text" id="service" class="form-control" value="{{ $requirement->project->service }}" disabled>
                </div>

                <div class="col-md-6">
                    <label for="user" class="form-label">User</label>
                    <input type="text" id="user" name="user" class="form-control" value="{{ $requirement->user }}">
                </div>

                <div class="col-md-6">
                    <label for="requirement_number" class="form-label">Requirement No.</label>
                    <input type="text" id="requirement_number" name="requirement_number" class="form-control"
                        value="{{ $requirement->requirement_number }}" readonly>
                </div>

                <div class="col-md-6">
                    <label for="requirement_title" class="form-label">Requirement Title</label>
                    <input type="text" id="requirement_title" name="requirement_title" class="form-control"
                        value="{{ $requirement->requirement_title }}" required>
                </div>
                <input type="hidden" name="category_id" id="category_id">
                <div class="col-md-6">
                    <label for="dynamic_select" class="form-label">Select Service or Category</label>
                    <select name="dynamic_select" id="dynamic_select" class="form-control">
                        <option value="" disabled selected>Select a Service</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">Input Date</label>
                    <input type="date" id="date" name="date" class="form-control"
                        value="{{ $requirement->date ? $requirement->date->format('Y-m-d') : '' }}" required>
                </div>
                <div class="col-md-6">
                    <label for="requirement_type" class="form-label">Requirement Type</label>
                    <select id="requirement_type" name="requirement_type" class="form-control" required>
                        <option value="" disabled selected>Select Requirement Type</option>
                        <option value="Functional">Functional</option>
                        <option value="Non-Functional">Non-Functional</option>
                        <option value="Nice to Have">Nice to Have</option>
                    </select>
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

        requirementTypeDropdown.addEventListener("change", function() {
            if (this.value === "Non-Functional") {
                this.innerHTML = `
                <option value="" disabled selected>Select Non-Functional Type</option>
                <option value="Performance">Performance</option>
                <option value="Security">Security</option>
                <option value="Usability">Usability</option>
                <option value="Design">Design</option>
                <option value="Change Request">Change Request</option>
            `;
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let originalOptions = [];

        // Load services from categories
        $.ajax({
            url: '/get-services-from-categories',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#dynamic_select').empty().append('<option value="" disabled selected>Select a Service</option>');
                $.each(data, function(index, service) {
                    $('#dynamic_select').append('<option value="service_' + service + '">' + service + '</option>');
                });

                originalOptions = $('#dynamic_select').html();
            },
            error: function(xhr) {
                console.error(xhr.responseJSON);
            }
        });

        // Handle selection change
        $('#dynamic_select').change(function() {
            let selectedValue = $(this).val();

            if (selectedValue.startsWith("service_")) {
                // Extract service name
                let serviceName = selectedValue.replace("service_", "");

                $.ajax({
                    url: '/get-categories-by-service/' + encodeURIComponent(serviceName),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#dynamic_select').empty().append('<option value="" disabled selected>Select a Category</option>');

                        if ($.isEmptyObject(data)) {
                            $('#dynamic_select').append('<option value="" disabled>No Categories Available</option>');
                        } else {
                            $.each(data, function(id, name) {
                                $('#dynamic_select').append('<option value="' + id + '">' + name + '</option>');
                            });
                        }

                        // Add a back option
                        $('#dynamic_select').append('<option value="reset">← Back to Services</option>');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON);
                    }
                });

            } else if (selectedValue === "reset") {
                // Reset to service selection
                $('#dynamic_select').html(originalOptions);
                $('#category_id').val(""); // Reset category ID
            } else {
                // Store category ID in hidden input
                $('#category_id').val(selectedValue);
            }
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
        const form = document.querySelector("form[action='{{ route('requirements.update', $requirement->id) }}']");

        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            // Ensure requirement_type updates before submission
            let requirementType = document.getElementById("requirement_type");
            let nonFunctionalType = document.getElementById("non_functional_type");

            if (requirementType.value === "Non-Functional" && nonFunctionalType && nonFunctionalType.value) {
                requirementType.value = nonFunctionalType.value; // Assign non-functional type
            }

            let formData = new FormData(form);

            Swal.fire({
                title: "Are you sure?",
                text: "You are about to update this requirement.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
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
                                    title: "Requirement Updated!",
                                    text: "Requirement has been successfully updated.",
                                    showConfirmButton: false,
                                    timer: 1500,
                                }).then(() => {
                                    window.location.href = document.referrer || "{{ route('requirements.create') }}";
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
                                text: "Failed to update requirement. Please try again.",
                            });
                        });
                }
            });
        });
    });
</script>