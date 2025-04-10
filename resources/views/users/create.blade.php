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

    {{-- Success Message (Trigger Swal if success session exists) --}}
    @if (session('success'))
      <script>
        Swal.fire({
          title: 'User Created Successfully!',
          icon: 'success',
          draggable: true,
          confirmButtonText: 'OK'
        });
      </script>
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
        <div class="form-group" style="position: relative;">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required />
          <i id="togglePassword" class="bi bi-eye-slash"
            style="position: absolute; right: 10px; top: 37px; cursor: pointer; font-size: 18px; color: #666;"></i>
        </div>

        <div class="form-group" style="position: relative;">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" name="password_confirmation" id="password_confirmation" required />
          <i id="toggleConfirmPassword" class="bi bi-eye-slash"
            style="position: absolute; right: 10px; top: 37px; cursor: pointer; font-size: 18px; color: #666;"></i>
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
              <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil-square"></i> Edit
              </a>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="bi bi-trash3-fill"></i> Delete
                </button>
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

    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function () {
      const isPassword = password.type === 'password';
      password.type = isPassword ? 'text' : 'password';
      this.classList.toggle('bi-eye-slash');
      this.classList.toggle('bi-eye');
    });

    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('password_confirmation');
    toggleConfirmPassword.addEventListener('click', function () {
      const isPassword = confirmPassword.type === 'password';
      confirmPassword.type = isPassword ? 'text' : 'password';
      this.classList.toggle('bi-eye-slash');
      this.classList.toggle('bi-eye');
    });
  });
</script>
@endpush
