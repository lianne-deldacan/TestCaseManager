@extends('layouts.app')

@section('content')
<h1>Current Projects</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="mt-4">
    <table id="projectsTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Project ID</th>
                <th>Service</th>
                <th>Project Name</th>
                <th>Manager</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->service }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->manager->name }}</td>
                <td>{{ $project->created_at->format('F j, Y') }}</td>
                <td>{{ $project->updated_at->format('F j, Y') }}</td>
                <td>
                    <div class="d-flex">
                        <!-- Edit Button -->
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning me-2">
                         Edit
                        </a>
                        <!-- Delete Button -->
                        <button 
                            type="button" 
                            class="btn btn-sm btn-danger" 
                            onclick="confirmDelete({{ $project->id }})">
                            Delete
                        </button>
                        <form id="delete-form-{{ $project->id }}" action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(projectId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${projectId}`).submit();
            }
        });
    }
</script>
@endsection