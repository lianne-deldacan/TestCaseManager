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

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="serviceField">Service</label>
                        <input type="text" id="serviceField" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Go to Add Issue</button>
        </form>
    </div>

    <script>
        document.getElementById('projectDropdown').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('serviceField').value = selectedOption.getAttribute('data-service') || '';
        });
    </script>
</body>

@endsection
