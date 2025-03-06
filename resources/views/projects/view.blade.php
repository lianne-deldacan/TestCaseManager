@extends('layouts.app')

@section('content')
<h1>Current Projects</h1>
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
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->service }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->manager }}</td>
                <td>{{ $project->created_at }}</td>
                <td>{{ $project->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection