<!DOCTYPE html>
<html>
<head>
    <title>Test Cases Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Test Cases Report</h2>
    <table>
        <thead>
            <tr>
                <th>Test Case No.</th>
                <th>Environment</th>
                <th>Tester</th>
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Pass/Fail</th>
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
</body>
</html>
