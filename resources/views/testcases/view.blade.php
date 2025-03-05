@extends('layouts.app')

@section('content')
<h1>Test Cases for {{ $project->name }}</h1>  
<div class="mt-4">
    <table id="testcasesTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Project Name</th>
                <th>Service</th>
                <th>Tester</th>
                <th>Test Case No.</th>
                <th>Test Title</th>
                <th>Test Step</th>
                <th>Category</th>
                <th>Date of Input</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($testCases as $case)
                <tr>
                    <td>{{ $case->project->name }}</td>
                    <td>{{ $case->project->service }}</td>
                    <td>{{ $case->tester }}</td>
                    <td>{{ $case->test_case_no }}</td>
                    <td>{{ $case->test_title }}</td>
                    <td>{{ $case->test_step }}</td>
                    <td>{{ $case->category->name }}</td>
                    <td>{{ $case->date_of_input }}</td>
                    <td>{{ $case->status }}</td>
                </tr>
                @endforeach
            </tbody>
    </table>
</div>
@endsection