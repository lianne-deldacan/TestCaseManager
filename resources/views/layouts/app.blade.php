<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bell Testing Tool</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">

    <!-- DataTables CSS (Updated Version) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Swal CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        .btn-toggle-nav a {
            text-decoration: none !important;
            color: inherit !important; 
        }

        .btn-toggle-nav a:hover {
            text-decoration: underline !important;
            text-decoration-thickness: 3px !important;
            color: rgba(191, 200, 238, 0.8)!important;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        @if (!Request::is('login'))
            <div class="flex-shrink-0 p-3 text-white" style="width: 280px; background-color: #777AAC; min-height: 100vh;">
                <a href="/" class="d-flex justify-content-center pb-3 mb-3 link-light text-decoration-none">
                    <img src="{{ asset('logo.png') }}" alt="Logo" width="150">
                </a>
                <ul class="list-unstyled ps-0">

                    {{-- Dashboard --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="true">
                            Dashboard
                        </button>
                        <div class="collapse show" id="dashboard-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                <li><a href="{{ route('dashboard.index') }}" class="text-white d-inline-flex text-decoration-none rounded">Analytics</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Project --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#project-collapse" aria-expanded="true">
                            Project
                        </button>
                        <div class="collapse show" id="project-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                <li><a href="{{ route('projects.create') }}" class="text-white d-inline-flex text-decoration-none rounded">Add Project</a></li>
                                <li><a href="{{ route('projects.index') }}" class="text-white d-inline-flex text-decoration-none rounded">View Projects</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Requirements --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#reqs-collapse" aria-expanded="true">
                            Requirements
                        </button>
                        <div class="collapse show" id="reqs-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                <li><a href="{{ route('landing', ['page' => 'add_requirements']) }}" class="text-white d-inline-flex text-decoration-none rounded">Add Requirements</a></li>
                                <li><a href="{{ route('landing', ['page' => 'view_requirements']) }}" class="text-white d-inline-flex text-decoration-none rounded">View Requirements</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Test Cases --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#testcases-collapse" aria-expanded="true">
                            Test Cases
                        </button>
                        <div class="collapse show" id="testcases-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                <li><a href="{{ route('landing', ['page' => 'add']) }}" class="text-white d-inline-flex text-decoration-none rounded">Add Test Cases</a></li>
                                <li><a href="{{ route('landing', ['page' => 'view']) }}" class="text-white d-inline-flex text-decoration-none rounded">View Test Cases</a></li>
                                <li><a href="{{ route('executeTestcases') }}" class="text-white d-inline-flex text-decoration-none rounded">Run Test Cases</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Issues --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#issues-collapse" aria-expanded="true">
                            Issues
                        </button>
                        <div class="collapse show" id="issues-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                <li><a href="{{ route('landing') }}" class="text-white d-inline-flex text-decoration-none rounded">Add Issues</a></li>
                                <li><a href="{{ route('issues') }}" class="text-white d-inline-flex text-decoration-none rounded">View Issues</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Categories --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#categories-collapse" aria-expanded="true">
                            Categories
                        </button>
                        <div class="collapse show" id="categories-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                
                                <li><a href="/categories/create" class="text-white d-inline-flex text-decoration-none rounded">Add Category</a></li>
                                <li><a href="/categories" class="text-white d-inline-flex text-decoration-none rounded">View Categories</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Users --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#users-collapse" aria-expanded="true">
                            Users
                        </button>
                        <div class="collapse show" id="categories-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                {{-- <li><a href="/users" class="text-white d-inline-flex text-decoration-none rounded">View Users</a></li> --}}
                                <li><a href="/users/create" class="text-white d-inline-flex text-decoration-none rounded">Add Users</a></li>
                                <li><a href="{{ route('users') }}" class="text-white d-inline-flex text-decoration-none rounded">View Users</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Account --}}
                    <li class="mb-1 border-bottom">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                            data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="true">
                            Account
                        </button>
                        <div class="collapse show" id="account-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn text-white d-inline-flex text-decoration-none rounded p-0 border-0 bg-transparent">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        @endif

        {{-- Main content --}}
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; 2025 Test Cases</p>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function () {
            $('#testcasesTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
