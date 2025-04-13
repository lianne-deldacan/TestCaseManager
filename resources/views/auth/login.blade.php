@extends('layouts.login')

@section('title', 'Login')

@section('content')
<div class="login-box">
    <div class="logo-circle">
        <img src="{{ asset('logo.png') }}" alt="Logo">
    </div>

    <h2>Log In</h2>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required />

        <label for="password">Password</label>
        <div style="position: relative;">
            <input type="password" name="password" id="password" required />
            <i id="togglePassword" class="bi bi-eye-slash"
              style="position: absolute; right: 10px; top: 10px; cursor: pointer; font-size: 18px; color: #666;"></i>
        </div>


        <div class="button-container">
            <button type="submit">Log In</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function () {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';

        this.classList.toggle('bi-eye-slash');
        this.classList.toggle('bi-eye');
    });
</script>
@endpush


