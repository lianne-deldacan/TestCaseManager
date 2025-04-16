@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Issue</h2>

    <form action="{{ route('issue.update', $issue->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Issue Number</label>
            <input type="text" name="issue_number" value="{{ $issue->issue_number }}" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="In Progress" {{ $issue->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Resolved" {{ $issue->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="Closed" {{ $issue->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                <option value="Reopened" {{ $issue->status == 'Reopened' ? 'selected' : '' }}>Reopened</option>
            </select>
        </div>

        <div class="form-group">
            <label>Developer Notes</label>
            <textarea name="developer_notes" class="form-control" rows="4">{{ $issue->developer_notes ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    </form>
</div>
@endsection
