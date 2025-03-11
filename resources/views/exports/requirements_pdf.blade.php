<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Requirements Report</h2>
    <table>
        <thead>
            <tr>
                <th>Project Name</th>
                <th>User</th>
                <th>Requirement No.</th>
                <th>Requirement Title</th>
                <th>Category</th>
                <th>Requirement Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requirements as $requirement)
                <tr>
                    <td>{{ $requirement->project->name ?? 'N/A' }}</td>
                    <td>{{ $requirement->user }}</td>
                    <td>{{ $requirement->requirement_number }}</td>
                    <td>{{ $requirement->requirement_title }}</td>
                    <td>{{ $requirement->category->name ?? 'N/A' }}</td>
                    <td>{{ $requirement->requirement_type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
