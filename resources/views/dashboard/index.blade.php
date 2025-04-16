@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Analytics</h1>
    
    <!-- Filters -->
    <form method="GET" action="{{ route('dashboard.index') }}">
        <div class="row mb-4">
            <div class="col-md-4">
                <select name="project" class="form-control">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="service" class="form-control">
                    <option value="">Select Service</option>
                    @foreach($services as $service)
                        <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ ucfirst($service) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Analytics Cards -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Projects</h5>
                    <p class="card-text">{{ $projectsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Test Cases</h5>
                    <p class="card-text">{{ $testCasesCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text">{{ $categoriesCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Requirements</h5>
                    <p class="card-text">{{ $requirementsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues Overview -->
    <h2>Issues Overview</h2>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Pass</h5>
                    <p class="card-text">{{ $passCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Fail</h5>
                    <p class="card-text">{{ $failCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">N/A</h5>
                    <p class="card-text">{{ $naCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5 class="card-title">N/R</h5>
                    <p class="card-text">{{ $nrCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3>Total Issues: {{ $issuesCount }}</h3>

    <!-- Issues Chart -->
    <h3>Issues by Status</h3>
    <div class="row">
        <div class="col-md-12 mb-3">
            <canvas id="issuesChart" style="width: 20%; height: 80px;"></canvas>
        </div>
    </div>
    
    <!-- Project Distribution Chart -->
    <h3>Project Distribution</h3>
    <div class="row">
        <div class="col-md-12 mb-3">
            <canvas id="projectsChart" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Issues by Status Chart
        const issuesChart = new Chart(document.getElementById('issuesChart'), {
            type: 'pie',
            data: {
                labels: ['Pass', 'Fail', 'N/A', 'N/R'],
                datasets: [{
                    data: [{{ $passCount }}, {{ $failCount }}, {{ $naCount }}, {{ $nrCount }}],
                    backgroundColor: ['#28a745', '#dc3545', '#6c757d', '#343a40'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        // Project Distribution Chart
        const projectsChart = new Chart(document.getElementById('projectsChart'), {
            type: 'bar',
            data: {
                labels: ['Projects', 'Test Cases', 'Categories', 'Requirements'],
                datasets: [{
                    label: 'Total Count',
                    data: [{{ $projectsCount }}, {{ $testCasesCount }}, {{ $categoriesCount }}, {{ $requirementsCount }}],
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Categories'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
