@extends('layouts.app')

@section('title', 'User List')

@section('content')
<div class="container-fluid">
  <div class="card">
    <h5 class="card-header">User Management</h5>
    <div class="card-body">
      <div class="d-flex pb-3 justify-content-end">
        <a href="#" class="btn btn-primary">Add User</a>
      </div>
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role() }}</td>
            <td>{{ $user->created_at->diffForHumans() }}</td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('users.destroy', $user) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <a href="javascript(0)" class="btn btn-sm btn-danger">
                    <button type="submit" style="padding:0;background-color:inherit;font-size:inherit;" onclick="return confirm('Delete user?')">Delete</button>
                  </a>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $users->links() }}
    </div>
  </div>

  @if(session('success'))
  <div style="color: green;">{{ session('success') }}</div>
  @endif
</div>
@endsection