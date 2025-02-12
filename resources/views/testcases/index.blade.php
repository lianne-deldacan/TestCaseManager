@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Test Case Form</h2>
        <form action="{{ route('testcases.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="test_case_no" class="form-label">Test Case No.</label>
                    <input type="text" id="test_case_no" name="test_case_no" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="test_environment" class="form-label">Test Environment</label>
                    <input type="text" id="test_environment" name="test_environment" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="tester" class="form-label">Tester</label>
                    <input type="text" id="tester" name="tester" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="date_of_input" class="form-label">Date of Input</label>
                    <input type="date" id="date_of_input" name="date_of_input" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="test_title" class="form-label">Test Title</label>
                    <input type="text" id="test_title" name="test_title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="screenshot" class="form-label">Screenshot (URL or Text)</label>
                    <input type="text" id="screenshot" name="screenshot" class="form-control">
                </div>
                <div class="col-md-12">
                    <label for="test_description" class="form-label">Test Description</label>
                    <textarea id="test_description" name="test_description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="priority" class="form-label">Priority</label>
                    <select id="priority" name="priority" class="form-select" required>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="severity" class="form-label">Severity</label>
                    <select id="severity" name="severity" class="form-select" required>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-plus-circle"></i> Add</button>
            </div>
        </form>
    </div>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form id="importForm" action="{{ route('testcases.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
            @csrf
            <input type="file" name="file" class="form-control w-auto" required>
            <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Import Test Cases</button>
        </form>

        <a href="{{ route('testcases.export.csv') }}" class="btn btn-info"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
        <a href="{{ route('testcases.export.excel') }}" class="btn btn-warning"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
        <a href="{{ route('testcases.export.pdf') }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
    </div>
</div>

<style>
@media print {
    @page {
        size: A4 portrait; 
        margin: 10mm;
    }

    body * {
        display: none; 
    }

    #testcasesTable, #testcasesTable * {
        display: table; 
    }

    #testcasesTable {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto; 
    }

    th, td {
        border: 1px solid black;
        padding: 6px;
        text-align: left;
        font-size: 12px;
        word-wrap: break-word; 
    }
}
</style>

<button class="btn btn-dark" onclick="printTable()">
    <i class="bi bi-printer"></i> Print Table
</button>

<script>
function printTable() {
    var printWindow = window.open("", "", "width=800,height=1000");
    
    printWindow.document.write("<html><head><title>Print Table</title>");
    printWindow.document.write("<style>");
    printWindow.document.write("@page { size: A4 portrait; margin: 10mm; }");
    printWindow.document.write("body { margin: 0; padding: 0; }");
    printWindow.document.write("table { width: 100%; border-collapse: collapse; table-layout: auto; }");
    printWindow.document.write("th, td { border: 1px solid black; padding: 6px; text-align: left; font-size: 12px; word-wrap: break-word; }");
    printWindow.document.write("</style>");
    printWindow.document.write("</head><body>");
    printWindow.document.write(document.getElementById("testcasesTable").outerHTML);
    printWindow.document.write("</body></html>");

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}
</script>

    <div class="mt-4">
        <table id="testcasesTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
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
</div>

@endsection