{{-- @extends('layouts.app')

@section('title', 'User List')

@section('content')
<div class="container">
  <h2>User Management</h2>

  @if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
  @endif

  <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Create User</a>

  <table id="userTable" class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->created_at->diffForHumans() }}</td>
        <td>
          <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>

          <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('#userTable').DataTable();
  });
</script>
@endpush --}}
