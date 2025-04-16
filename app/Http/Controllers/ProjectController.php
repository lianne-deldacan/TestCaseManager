<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('manager')
            ->orderBy('id', 'asc') // Sort by ID ascending
            ->get();
    
        $projectName = $projects->first()->name ?? 'Default Project Name';
    
        return view('projects.view', compact('projects', 'projectName'));
    }
    

    public function create()
    {
        $projects = Project::all();
        $nextID = (Project::max('id') ?? 0) + 1;
        $managers = User::where('role', 'Manager')->get();

        return view('projects.create', compact('projects', 'nextID', 'managers'));
    }

    public function store(Request $request)
    {
     
        $request->validate([
            'service' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'manager_id' => 'required|string|max:255',
        ]);

        $project = Project::create($request->only(['service', 'name', 'manager_id']));
        $manager = User::find($request->manager_id);

        if (!$manager) {
            return response()->json(['message' => 'Manager not found', 'managerId' => $request->manager_id], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Project added successfully!',
            'project' => $project,
            'manager_name' => $manager ? $manager->name : null,
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
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // dd($project);
        $managers = User::where('role', 'Manager')->get();
        return view('projects.edit', compact('project', 'managers'));
    }

    public function update(Request $request, Project $project)
    {
        

        $project->update($request->all());
        $projects = Project::with('manager')
            ->orderBy('id', 'asc') // Sort by ID ascending
            ->get();
    
        $projectName = $projects->first()->name ?? 'Default Project Name';
        return redirect()->route('projects.index')
        ->with('success', 'Project updated successfully')
        ->with('projectName', $projectName)
        ->with('projects', $projects); // flash entire collection
    
    }

    public function destroy(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->testCases()->delete();

        // Then delete the project
        $project->delete();

         return redirect()->back()->with('success', 'Project deleted successfully');
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

