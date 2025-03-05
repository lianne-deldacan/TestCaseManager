@extends('layouts.app')

@section('content')
<h1>Test Cases for {{ $project->name }}</h1>  
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
@endsection