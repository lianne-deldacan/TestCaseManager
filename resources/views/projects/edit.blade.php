@extends('layouts.app')

@section('content')
<div class="card shadow-lg p-4">
  <h2 class="text-center mb-4">Edit Project</h2>
  <form action="{{ route('projects.update', $project->id) }}" method="POST">
    @csrf
    @method('PUT')
      <div class="row g-3">
          <div class="col-md-6">
              <label for="service" class="form-label">Service</label>
              <select id="service" name="service" class="form-control" required>
                @foreach(config('global.services') as $k => $service)
                    <option value="{{ $k }}" {{ (string)$project->service === (string)$service ? 'selected' : '' }}>
                        {{ $service }}
                    </option>
                @endforeach
            </select>
            
            
            
                      </div>
          <div class="col-md-4">
              <label for="project_id" class="form-label">Project ID</label>
              <input type="text" id="project_id" name="id" class="form-control" readonly value="{{ $project->id }}">

          </div>
          <div class="col-md-6">
              <label for="project_name" class="form-label">Project Name</label>
              <input type="text" id="project_name" name="name" class="form-control" required value="{{$project->name}}">
          </div>
          <div class="col-md-6">
              <label for="project_manager" class="form-label">Project Manager</label>
              <select id="project_manager" name="manager_id" class="form-control" required>
                  <option value="" disabled selected>Select a project manager</option>
                  @foreach($managers as $manager)
                  <option value="{{ $manager->id }}" {{ $project->manager_id == $manager->id ? 'selected' : '' }}>
                    {{ $manager->name }}
                </option>
                
                  @endforeach
              </select>
          </div>
          
      </div>
    
    
  
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-pencil-square"></i> Update Project
        </button>
        
        </div>
    
  </form>
</div>
@endsection