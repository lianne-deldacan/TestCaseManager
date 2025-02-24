@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="project-form-container">
        <h2 class="project-title">Create a New Project</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="project-input-group">
                <label for="name" class="project-label">Project Name</label>
                <input type="text" class="project-input" id="name" name="name" required>
            </div>

            <div class="project-input-group">
                <label for="description" class="project-label">Project Description (Optional)</label>
                <textarea class="project-textarea" id="description" name="description" rows="4"></textarea>
            </div>

            <button type="submit" class="project-btn">Save Project</button>
        </form>
    </div>
</div>

@endsection
