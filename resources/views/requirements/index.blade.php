@extends('layouts.app')

@section('content')
<h1>Requirements for {{ $project->name }}</h1>
<div class="mt-4">
    <div class="mt-4 d-flex flex-wrap align-items-center gap-2">
        <a href="" class="btn btn-info">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
        </a>
        <a href="" class="btn btn-warning">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="" class="btn btn-danger">
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
                <td>{{ $requirement->requirement_number }}</td>
                <td>{{ $requirement->requirement_title }}</td>
                <td>{{ $requirement->category->name }}</td>
                <td>{{ $requirement->requirement_type }}</td>
                <td>
                    <!-- Add action buttons here -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection