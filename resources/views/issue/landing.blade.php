@extends('layouts.app')

@section('content')

<body>
    <div class="container">
        <h2>Select Project and Service</h2>

<form action="{{ route('issue.add') }}" method="GET">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="serviceDropdown">Service</label>
                <select id="serviceDropdown" class="form-control" required>
                    <option value="">Select Service</option>
                    <option value="IT">IT</option>
                    <option value="Marketing">Marketing</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="projectDropdown">Project</label>
                <select name="project_id" id="projectDropdown" class="form-control" required>
                    <option value="">Select Project</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" data-service="{{ $proj->service }}">
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <input type="hidden" id="selectedServiceInput" name="service" value="">

    <button type="submit" class="btn btn-primary mt-3">Continue</button>
</form>

    </div>

<script>
    document.getElementById('serviceDropdown').addEventListener('change', function () {
        const selectedService = this.value;
        document.getElementById('selectedServiceInput').value = selectedService;

        const projectDropdown = document.getElementById('projectDropdown');
        const options = projectDropdown.querySelectorAll('option');

        options.forEach((option, index) => {
            if (index === 0) return; // keep the "Select Project" default

            const projectService = option.getAttribute('data-service');
            option.style.display = (projectService === selectedService) ? 'block' : 'none';
        });

        projectDropdown.value = ""; // reset selection when service changes
    });
</script>

</body>

@endsection
