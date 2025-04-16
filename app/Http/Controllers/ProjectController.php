<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('manager')
            ->latest()
            ->get();
            // ->all();
        $projectName = $projects->isNotEmpty() ? $projects->first()->name : 'Default Project Name';
        return view('projects.view', compact('projects', 'projectName'));
    }

    public function create()
    {
        $projects = Project::all();
        $nextID = (Project::max('id') ?? 0) + 1;
        
        return view('projects.create', compact('projects', 'nextID'));
    }

    public function store(Request $request)
    {
        
        // Validate input data
        $request->validate([
            'service' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'manager' => 'required|string|max:255',
        ]);

        // Create the project
        $project = Project::create($request->only(['service', 'name', 'manager']));

        // Return a JSON response with the project data
        return response()->json([
            'success' => true,
            'message' => 'Project added successfully!',
            'project' => $project
        ]);
    }

    public function show(string $id)
    {
        $project = Project::with('testCases')->find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        return response()->json($project);
    }

    public function edit(string $id)
    {
        
    }

    public function update(Request $request, string $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->update($request->all());
        return response()->json($project);
    }

    public function destroy(string $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function getProjects($service)
    {
        $projects = Project::where('service', $service)->pluck('name', 'id');
        return response()->json($projects);
    }

    public function getProjectsByService(Request $request)
    {
        $request->validate(['service' => 'required|string']);

        $projects = Project::where('service', $request->input('service'))->pluck('name', 'id');

        return response()->json($projects);
    }
}

