=@extends('layouts.app')

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

            <select id="project_id" class="form-select me-2">
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
                        <th>Project</th>
                        <th>Execution</th>
                        <th>Environment</th>
                        <th>Tester</th>
                        <th>Date Reported</th>
                        <th>Status</th>
                        <th>Issue Title</th>
                        <th>Description</th>
                        <th>Screenshot</th>
                        <th>Developer</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($issues as $issue)
                    <tr>
                        <td>{{ $issue->issue_number }}</td>
                        <td>{{ $issue->project->name }}</td>
                        <td></td>
                        <td>{{ $issue->environment }}</td>
                        <td>{{ $issue->tester }}</td>
                        <td>{{ $issue->created_at->format('Y-m-d') }}</td>
                        <td>{{ $issue->status }}</td>
                        <td>{{ $issue->title }}</td>
                        <td>{{ $issue->description }}</td>
                        <td>
                            @if ($issue->screenshot)
                                <a href="{{ asset('storage/screenshots/'.$issue->screenshot) }}" target="_blank">View Screenshot</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $issue->developer }}</td>
                        <td>{{ $issue->notes }}</td>
                        <td>
                            <a href="" class="btn btn-warning btn-sm">Edit</a>
                            <form action="" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
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
                <h5 class="modal-title">Edit Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" id="issueId">

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="status">
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Closed">Closed</option>
                            <option value="Reopened">Reopened</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="developer_notes">Developer Notes</label>
                        <textarea class="form-control" id="developer_notes" rows="3"></textarea>
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
    $(document).ready(function () {
        var table = $('#issuesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('issue.list') }}",
                data: function (d) {
                    d.status = $('#statusFilter').val();
                    d.project_id = $('#project_id').val();
                    d.tester = $('#testerFilter').val();
                }
            },
            columns: [
                { data: 'issue_number' },
                { data: 'project.name', defaultContent: 'N/A' },
                { data: 'execution.id', defaultContent: 'N/A' },
                { data: 'execution.environment', defaultContent: 'N/A' },
                { data: 'tester' },
                { data: 'formatted_date' },
                { data: 'status' },
                { data: 'issue_title' },
                { data: 'issue_description' },
                { 
                    data: 'screenshot_url',
                    render: function (data) {
                        return data ? `<a href="${data}" target="_blank">View Screenshot</a>` : "No Screenshot";
                    }
                },
                { data: 'assigned_developer', defaultContent: 'Not Assigned' },
                { data: 'developer_notes', defaultContent: 'No Notes' },
                { data: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#statusFilter, #project_id, #testerFilter').on('change', function () {
            table.ajax.reload();
        });

        // Edit Issue Modal
        window.editIssue = function (id, status, notes) {
            $('#issueId').val(id);
            $('#status').val(status);
            $('#developer_notes').val(notes);
            $('#editModal').modal('show');
        };

        $('#editForm').submit(function (e) {
            e.preventDefault();
            let id = $('#issueId').val();

            $.ajax({
                url: `/issues/${id}`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: $('#status').val(),
                    developer_notes: $('#developer_notes').val()
                },
                success: function () {
                    $('#editModal').modal('hide');
                    Swal.fire('Success!', 'Issue updated.', 'success');
                    table.ajax.reload();
                },
                error: function () {
                    Swal.fire('Error!', 'Could not update issue.', 'error');
                }
            });
        });

        // Delete Issue
        window.deleteIssue = function (id) {
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
                        url: `/issues/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function () {
                            Swal.fire('Deleted!', 'The issue has been deleted.', 'success');
                            table.ajax.reload();
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        };
    });
</script>
@endsection
