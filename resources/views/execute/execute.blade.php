@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <button class="btn btn-sm btn-danger close-btn" onclick="window.history.back()">X</button>
    <div class="header text-center mb-4">
        <h2>Execute Test Case</h2>
    </div>

    <div class="card shadow p-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Project Name</label>
                <input type="text" class="form-control" value="{{ $testCase->project->name }}" disabled>
            </div>
            <div class="col-md-6">
                <label>Project ID</label>
                <input type="text" class="form-control" value="{{ $testCase->project->id }}" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Test Environment</label>
                <select class="form-control">
                    <option>SIT</option>
                    <option>UAT</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Tester Name</label>
                <input type="text" class="form-control" value="{{ $testCase->tester }}" readonly>
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-success btn-lg pass">Pass</button>
            <button class="btn btn-danger btn-lg fail">Fail</button>
            <button class="btn btn-secondary btn-lg not-run">Not Run</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.pass').addEventListener('click', function () {
        alert('Test case marked as Passed.');
    });

    document.querySelector('.fail').addEventListener('click', function () {
        alert('Test case marked as Failed.');
    });

    document.querySelector('.not-run').addEventListener('click', function () {
        alert('Test case marked as Not Run.');
    });
});
</script>
@endsection
