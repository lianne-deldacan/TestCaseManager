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

        th,
        td {
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
                <th>Project Name</th>
                <th>Service</th>
                <th>Tester</th>
                <th>Test Case No.</th>
                <th>Test Title</th>
                <th>Test Step</th>
                <th>Category</th>
                <th>Date</th>
                <th>Priority</th>
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
                <td>{{ $case->priority }}</td>
                <td>{{ $case->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>