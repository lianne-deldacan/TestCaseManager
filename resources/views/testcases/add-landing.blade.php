@extends('layouts.app')


@section('content')
<!-- This is the landing page for Adding Test Cases -->
<div class="container d-flex justify-content-center mt-5">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 800px;">
        <h2 class="text-center mb-4">New Test Case Form</h2>
        <form id="service-form" class="text-center">
            @csrf
            <div class="row g-3 justify-content-center">
                <div class="col-md-6">
                    <label for="service-select" class="form-label">Select Service:</label>
                    <select id="service-select" name="service" class="form-control" required>
                        <option value="" disabled selected>Select Service</option>
                        <option value="IT">IT</option>
                        <option value="Marketing">Marketing</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="project-select" class="form-label">Select Project:</label>
                    <select id="project-select" name="project" class="form-control" required>
                        <option value="" disabled selected>Select Project</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Add Test Case</button>
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

        // Redirect to test cases page on form submission
        $('#service-form').submit(function(event) {
            event.preventDefault();
            let projectId = $('#project-select').val();

            if (projectId) {
                window.location.href = "{{ route('testcases.create', ['project_id' => '']) }}" + projectId;
            } else {
                alert('Please select a project.');
            }
        });
    });
</script>
@endsection
