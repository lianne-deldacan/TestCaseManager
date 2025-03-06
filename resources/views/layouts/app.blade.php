<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>My Application</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">

    <!-- DataTables CSS (Updated Version) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--Swal CDN link-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="flex-shrink-0 p-3 text-white" style="width: 280px; background-color: #777AAC; min-height: 100vh;">
            <a href="/" class="d-flex justify-content-center pb-3 mb-3 link-light text-decoration-none">
                <img src="{{ asset('logo.png') }}" alt="Logo" width="150">
            </a>
            <ul class="list-unstyled ps-0">
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
                <li class="mb-1 border-bottom">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                        data-bs-toggle="collapse" data-bs-target="#testcases-collapse" aria-expanded="true">
                        Test Cases
                    </button>
                    <div class="collapse show" id="testcases-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                            <li><a href="{{ route('landing', ['page' => 'add']) }}" class="text-white d-inline-flex text-decoration-none rounded">Add Test Cases</a></li>
                            <li><a href="{{ route('landing', ['page' => 'view']) }}" class="text-white d-inline-flex text-decoration-none rounded">View Test Cases</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 border-bottom">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                        data-bs-toggle="collapse" data-bs-target="#issues-collapse" aria-expanded="true">
                        Issues
                    </button>
                    <div class="collapse show" id="issues-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                            <li><a href="#" class="text-white d-inline-flex text-decoration-none rounded">View Issues</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 border-bottom">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed fw-bold fs-4 text-white"
                        data-bs-toggle="collapse" data-bs-target="#categories-collapse" aria-expanded="true">
                        Categories
                    </button>
                    <div class="collapse show" id="categories-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-3 ms-3">
                            <li><a href="/categories" class="text-white d-inline-flex text-decoration-none rounded">View Categories</a></li>
                            <li><a href="/categories/create" class="text-white d-inline-flex text-decoration-none rounded">Add Category</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Main content -->
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>


    <footer class="text-center mt-4">
        <p>&copy; 2025 Test Cases</p>
    </footer>

    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (Required by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS (Updated Version) -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#testcasesTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
</body>

</html>