@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">
  <div class="form-container mb-5">
    <h2>Edit User</h2>

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
      <script>
        Swal.fire({
          title: 'User Updated Successfully!',
          icon: 'success',
          confirmButtonText: 'OK'
        });
      </script>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}">
      @csrf
      @method('PUT')

      <div class="form-row spaced">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group" style="position: relative;">
          <label for="password">New Password <small>(Leave blank to keep current)</small></label>
          <input type="password" name="password" id="password" />
          <i id="togglePassword" class="bi bi-eye-slash"
             style="position: absolute; right: 10px; top: 37px; cursor: pointer; font-size: 18px; color: #666;"></i>
        </div>

        <div class="form-group" style="position: relative;">
          <label for="password_confirmation">Confirm New Password</label>
          <input type="password" name="password_confirmation" id="password_confirmation" />
          <i id="toggleConfirmPassword" class="bi bi-eye-slash"
             style="position: absolute; right: 10px; top: 37px; cursor: pointer; font-size: 18px; color: #666;"></i>
        </div>
      </div>

      <div class="button-container d-flex justify-content-between">
        <a href="{{ route('users.create') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
      const isPassword = password.type === 'password';
      password.type = isPassword ? 'text' : 'password';
      togglePassword.classList.toggle('bi-eye-slash');
      togglePassword.classList.toggle('bi-eye');
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('password_confirmation');
    toggleConfirmPassword.addEventListener('click', () => {
      const isPassword = confirmPassword.type === 'password';
      confirmPassword.type = isPassword ? 'text' : 'password';
      toggleConfirmPassword.classList.toggle('bi-eye-slash');
      toggleConfirmPassword.classList.toggle('bi-eye');
    });
  });
</script>
@endpush