<!DOCTYPE html>
<html>
<head>
    <title>Test Cases Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px; 
        }
        th, td {
            padding: 5px;
            text-align: left;
            border: 1px solid black;
            word-wrap: break-word; 
        }
        td {
            max-width: 200px; 
            overflow: hidden;
        }
        td.screenshot {
            max-width: 150px; 
            overflow: hidden;
            text-align: center;
        }
        img {
            max-width: 100px; 
            height: auto;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Test Cases Report</h2>
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
                    <td class="screenshot">
                        @if(filter_var($case->screenshot, FILTER_VALIDATE_URL))
                            <img src="{{ $case->screenshot }}">
                        @else
                            {{ $case->screenshot }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
