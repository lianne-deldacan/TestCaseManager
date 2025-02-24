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
        return response()->json(Project::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all(); 
        $nextID = Project::max('id') + 1;
        return view('projects.create', compact('projects', 'nextID'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'service' => 'required',
                'project_name' => 'required',
                'project_manager' => 'required|string',
            ]);

            // Store data in database
            $project = Project::create($request->all());

            // Check if the request expects a JSON response
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Project added successfully.',
                    'project' => $project
                ]);
            }

            // If not a JSON request, redirect with a success message
            return redirect()->route('testcases.create', ['project_id' => $project->id])
                ->with('success', 'Project created successfully! Now add test cases.');
        } catch (\Exception $e) {
            // Handle error for both web and JSON responses
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
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
}
