@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container">
  {{-- ========== FORM SECTION ========== --}}
  <div class="form-container mb-5">
    <h2>Create Account</h2>

    {{-- Validation Errors --}}
    @if ($errors->any())
      <div style="color: red; margin-bottom: 20px;">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
      <div style="color: green; margin-bottom: 20px;">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="form-row spaced">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" value="{{ old('name') }}" required />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" required />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required />
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" name="password_confirmation" id="password_confirmation" required />
        </div>
      </div>

      <div class="button-container">
        <button type="submit">Create</button>
      </div>
    </form>
  </div>

  {{-- ========== DATATABLE SECTION ========== --}}
  <div class="card p-4 shadow-sm">
    <h3 class="mb-4">Users List</h3>
    <div class="table-responsive">
      <table id="usersTable" class="table table-bordered table-hover w-100">
        <thead class="table-dark">
          <tr>
            <th style="width: 5%;">ID</th>
            <th style="width: 30%;">Name</th>
            <th style="width: 35%;">Email</th>
            <th style="width: 30%;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('#usersTable').DataTable({
      responsive: true,
      columnDefs: [
        { targets: [3], orderable: false }
      ]
    });
  });
</script>
@endpush
