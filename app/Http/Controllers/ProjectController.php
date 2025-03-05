<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all(); // Retrieve all projects
        $projectName = $projects->isNotEmpty() ? $projects->first()->name : 'Default Project Name'; 
        return view('projects.index', compact('projects', 'projectName'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all(); 
        $nextID = Project::max('id') + 1; 
        return view('projects.create',  compact('projects', 'nextID'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'service' => 'required', 
                'name' => 'required',
                'manager' => 'required|string',
            ]);

            // Store data in database
            $project = Project::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Project added successfully.',
                'project' => $project,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    
    public function show(string $id)
    {
        $project = Project::with('testCases')->find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        return response()->json($project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->update($request->all());
        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }


    //TESTING
    public function getProjects($service)
    {
        // Retrieve projects where service matches
        $projects = Project::where('service', $service)->pluck('name', 'id');

        // Return as JSON response
        return response()->json($projects);
    }
}
