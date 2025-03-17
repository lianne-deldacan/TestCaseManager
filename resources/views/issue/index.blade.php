@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Issue List</h2>

    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex">
            <a href="{{ route('issues.export.excel') }}" class="btn btn-success me-2">Export to Excel</a>
            <a href="{{ route('issues.export.csv') }}" class="btn btn-info">Export to CSV</a>
        </div>

        <!-- Filters Section -->
        <div class="d-flex">
            <select id="statusFilter" class="form-select me-2" style="width: 150px;">
                <option value="">All Statuses</option>
                <option value="Open">Open</option>
                <option value="Resolved">Resolved</option>
                <option value="In Progress">In Progress</option>
                <option value="Closed">Closed</option>
                <option value="Reopened">Reopened</option>
            </select>

            <select class="form-select" name="project_id" id="project_id">
                <option value="">All Projects</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>


            <select id="testerFilter" class="form-select" style="width: 150px;">
                <option value="">All Testers</option>
                @foreach($testers as $tester)
                    <option value="{{ $tester }}">{{ $tester }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card shadow p-4">
        <div class="table-responsive">
            <table id="issuesTable" class="table table-striped table-bordered" style="width:100%;">
                <thead class="table-dark">
                    <tr>
                        <th>Issue No.</th>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Execution ID</th>
                        <th>Test Environment</th>
                        <th>Tester</th>
                        <th>Date Reported</th>
                        <th>Status</th>
                        <th>Issue Title</th>
                        <th>Issue Description</th>
                        <th>Screenshot</th>
                        <th>Assign Developer</th>
                        <th>Developer Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($issues->count() > 0)
                        @foreach ($issues as $issue)
                        <tr>
                            <td>{{ $issue->issue_number }}</td>
                            <td>{{ $issue->project_id }}</td>
                            <td>{{ $issue->project->name ?? 'N/A' }}</td>
                            <td>{{ $issue->execution ? $issue->execution->id : 'N/A' }}</td>
                            <td>{{ $issue->execution ? $issue->execution->environment : 'N/A' }}</td>
                            <td>{{ $issue->tester ?? 'N/A' }}</td>
                            <td>{{ $issue->created_at ? $issue->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $issue->status == 'Open' ? 'danger' : ($issue->status == 'Resolved' ? 'success' : 'warning') }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            <td>{{ $issue->issue_title }}</td>
                            <td>{{ $issue->issue_description }}</td>
                            <td>
                                @if ($issue->screenshot_url)
                                    <a href="{{ $issue->screenshot_url }}" target="_blank">View Screenshot</a>
                                @else
                                    No Screenshot
                                @endif
                            </td>
                            <td>{{ $issue->assigned_developer ?? 'Not Assigned' }}</td>
                            <td>{{ $issue->developer_notes ?? 'No notes' }}</td>

                            <!-- Edit Button in Actions Column -->
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editIssue({{ $issue->id }}, '{{ $issue->status }}', '{{ $issue->developer_notes }}')">
                                    Edit
                                </button>

                                <!-- Delete Button -->
                                <button class="btn btn-danger btn-sm" onclick="deleteIssue({{ $issue->id }})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="14" class="text-center">No issues found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Editing Status and Developer Notes -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="{{ route('issue.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Issue ID (hidden) -->
                    <input type="hidden" id="issueId" name="issue_id">

                    <!-- Status Dropdown -->
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Closed">Closed</option>
                            <option value="Reopened">Reopened</option>
                        </select>
                    </div>

                    <!-- Developer Notes -->
                    <div class="form-group mb-3">
                        <label for="developer_notes">Developer Notes</label>
                        <textarea class="form-control" name="developer_notes" id="developer_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editIssue(issueId, status, developerNotes) {
        document.getElementById('issueId').value = issueId;
        document.getElementById('status').value = status;
        document.getElementById('developer_notes').value = developerNotes;
    }

    function deleteIssue(issueId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/issues/' + issueId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        Swal.fire('Deleted!', 'The issue has been deleted.', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong. Try again later.', 'error');
                    }
                });
            }
        });
    }

    // Initialize DataTables with search and filtering
    $(document).ready(function () {
        var table = $('#issuesTable').DataTable({
            "searching": true, 
            "paging": true,    
            "lengthChange": false, 
            "pageLength": 10,  
            "order": [[0, 'desc']],
        });

        // Filter by status
        $('#statusFilter').on('change', function () {
            table.column(7).search(this.value).draw();
        });

        // Filter by project
        $('#projectFilter').on('change', function () {
            table.column(1).search(this.value).draw();
        });

        // Filter by tester
        $('#testerFilter').on('change', function () {
            table.column(5).search(this.value).draw();
        });
    });

    $('#editForm').submit(function (e) {
        e.preventDefault();
        
        $.ajax({
            url: $('#editForm').attr('action'),
            type: 'POST',
            data: $('#editForm').serialize(),
            success: function (response) {
                $('#editModal').modal('hide');
                Swal.fire('Success!', 'Issue status and developer notes updated successfully!', 'success').then(() => {
                    location.reload();
                });
            },
            error: function () {
                Swal.fire('Error!', 'There was an issue updating the status. Please try again.', 'error');
            }
        });
    });
</script>

@endsection
