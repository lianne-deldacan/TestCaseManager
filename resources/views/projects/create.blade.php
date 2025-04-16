@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Add Project</h2>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="service" class="form-label">Service</label>
                    <select id="service" name="service" class="form-control" required>
                        <option value="" disabled selected>Select a service</option>
                        @foreach(config('global.services') as $k => $service)
                            <option value="{{ $k }}">{{ $service }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="project_id" class="form-label">Project ID</label>
                    <input type="text" id="project_id" name="id" class="form-control" readonly value="{{ $nextID }}">

                </div>
                <div class="col-md-6">
                    <label for="project_name" class="form-label">Project Name</label>
                    <input type="text" id="project_name" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="project_manager" class="form-label">Project Manager</label>
                    <select id="project_manager" name="manager_id" class="form-control" required>
                        <option value="" disabled selected>Select a project manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-plus-circle"></i> Add Project
                </button>
            </div>
        </form>
    </div>

    <div class="mt-4">
        <table id="projectsTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Project ID</th>
                    <th>Service</th>
                    <th>Project Name</th>
                    <th>Project Manager</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->service }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->manager->name }}</td>
                        <td>
                            <a href="#" 
                            class="btn btn-primary go-to-test-cases" 
                            data-project-id="{{ $project->id }}" 
                            data-service="{{ $project->service }}">
                            Go to Test Cases
                         </a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert & Fetch API Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form[action='{{ route('projects.store') }}']");
    
        form.addEventListener("submit", function (event) {
            event.preventDefault();
    
            let formData = new FormData(form);
    
            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value,
                    "Accept": "application/json" // Ensure the response is expected as JSON
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Parse the response as JSON
            })
            .then(data => {
                console.log(data);
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Project Added!",
                        text: "Your project has been successfully added.",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    form.reset();
    
                    let newRow = `
                        <tr>
                            <td>${data.project.id}</td>
                            <td>${data.project.service}</td>
                            <td>${data.project.name}</td>
                            <td>${data.manager_name}</td>
                            <td>
                                   <a href="#" 
                            class="btn btn-primary go-to-test-cases" 
                            data-project-id="${data.project.id}"
                            data-service="${data.project.service}">
                            Go to Test Cases
                         </a>
                                </td>
                        </tr>
                    `;
                    document.querySelector("#projectsTable tbody").insertAdjacentHTML('beforeend', newRow);
                    document.querySelector("#project_id").value = parseInt(data.project.id) + 1;
                } else {
                    console.log(data);
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
                    text: "Failed to add project. Please try again.",
                });
            });
        });
    });
    </script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('go-to-test-cases')) {
            e.preventDefault();

            const projectId = e.target.getAttribute('data-project-id');
            const service = e.target.getAttribute('data-service');

            let url = "{{ route('testcases.create', ['project_id' => '__PROJECT_ID__', 'service' => '__SERVICE__']) }}";
            url = url.replace('__PROJECT_ID__', projectId).replace('__SERVICE__', service);

            window.location.href = url;
        }
    });
});

</script>

@endsection
