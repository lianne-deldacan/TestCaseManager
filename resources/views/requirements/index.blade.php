@extends('layouts.app')

@section('content')
<h1>Requirements for {{ $project->name }}</h1>
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

<script>
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
</script>