@extends('layouts.app')

@section('content')
<!-- Universal Landing Page -->
<div class="container d-flex justify-content-center mt-5">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 800px;">
        <h2 class="text-center mb-4">Select a Project</h2>

        <form id="service-form" class="text-center">
            @csrf
            <input type="hidden" id="action-type" value="{{ request()->query('page') }}"> <!-- Stores Add/View -->

            <div class="row g-3 justify-content-center">
                <div class="col-md-6">
                    <label for="service-select" class="form-label">Select Service:</label>
                    <select id="service-select" name="service" class="form-control" required>
                        <option value="" disabled selected>Select Service</option>
                        @foreach(config('global.services') as $k => $service)
                            <option value="{{ $k }}">{{ $service }}</option>
                        @endforeach
                        <!-- <option value="IT">IT</option>
                        <option value="Marketing">Marketing</option> -->
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="project-select" class="form-label">Select Project:</label>
                    <select id="project-select" name="project" class="form-control" required>
                        <option value="" disabled selected>Select Project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch projects when a service is selected
        $('#service-select').change(function() {
            let service = $(this).val();

            if (service) {
                $.ajax({
                    url: '/get-projects/' + encodeURIComponent(service),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#project-select').empty().append('<option value="" disabled selected>Select Project</option>');

                        if ($.isEmptyObject(data)) {
                            $('#project-select').append('<option value="" disabled>No Projects Available</option>');
                        } else {
                            $.each(data, function(id, name) {
                                $('#project-select').append('<option value="' + id + '">' + name + '</option>');
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON);
                    }
                });
            }
        });

        // Handle form submission
        $('#service-form').submit(function(event) {
            event.preventDefault();
            let projectId = $('#project-select').val();
            let actionType = $('#action-type').val(); // Get action (add/view)
            
            if (projectId) {
                if (actionType === "add") {
                    window.location.href = "{{ route('testcases.create', ['project_id' => '']) }}" + projectId;
                } else if (actionType === "view") {
                    window.location.href = "{{ route('testcases.view', ['project_id' => '']) }}" + projectId;
                } else if (actionType === "add_requirements") {
                    window.location.href = "{{ route('requirements.create', ['project_id' => '']) }}" + projectId;
                } else if (actionType === "view_requirements") {
                    window.location.href = "{{ route('requirements.index', ['project_id' => '']) }}" + projectId;
                } else {
                    alert("Invalid action type.");
                }
            } else {
                alert('Please select a project.');
            }
        });
    });
</script>
@endsection