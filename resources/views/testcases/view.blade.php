@extends('layouts.app')

@section('content')
<h1>Test Cases for {{ $project->name }}</h1>
<div class="mt-4">
    <div class="mt-4 d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('testcases.export.csv', ['project_id' => $project->id]) }}" class="btn btn-info">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
        </a>
        <a href="{{ route('testcases.export.excel', ['project_id' => $project->id]) }}" class="btn btn-warning">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="{{ route('testcases.export.pdf', ['project_id' => $project->id]) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <button class="btn btn-dark" onclick="printTable()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>
    <table id="testcasesTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Input by</th>
                <th>Test Case No.</th>
                <th>Test Title</th>
                <th>Test Step</th>
                <th>Category</th>
                <th>Date of Input</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($project->test_cases as $case)
            <tr>
                <td>{{ $case->tester->name }}</td>
                <td>{{ $case->test_case_no }}</td>
                <td>{{ $case->test_title }}</td>
                <td>{{ $case->test_step }}</td>
                <td>{{ $case->category->name }}</td>
                <td>{{ $case->date_of_input->format('F j, Y') }}</td>
                <td>{{ $case->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
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