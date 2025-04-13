@extends('layouts.login')

@section('title', 'Login')

@section('content')
<style>
    ul.errors {
        list-style-type: none;
        padding: 0;
    }
</style>
<div class="login-box">
    <div class="logo-circle">
        <img src="{{ asset('logo.png') }}" alt="Logo">
    </div>

    <h2>Log In</h2>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
    <!-- <div style="color: red; margin-bottom: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div> -->
    <script>
        let errors_html = `<ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>`

            Swal.fire({
                icon: "error",
                title: "Login error",
                html: errors_html,
            });
    </script>
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
    document.addEventListener("DOMContentLoaded", function() {
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', function() {
            const isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';

            this.classList.toggle('bi-eye-slash');
            this.classList.toggle('bi-eye');
        });
    });
</script>
<!-- 
Swal.fire({
    icon: "error",
    title: "Oops...",
    text: data.message || "Something went wrong!",
});
-->
@endpush