<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; //
class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index(Request $request)
    {
        $projectID = $request->query('project_id'); // Get project_id from query string

        if (!$projectID) {
            return redirect()->back()->with('error', 'No project selected.');
        }

        $project = Project::find($projectID);

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        // Get only requirements for the selected project
        $requirements = Requirement::where('project_id', $projectID)->with('project')->get();

        return view('requirements.index', compact('requirements', 'project'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $project = Project::find($request->query('project_id'));

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        $projectID = $project->id;
        $projectName = $project->name;
        $service = $project->service ?? 'Default Service';
        // Get the next requirement count for this project
        $requirementCount = Requirement::where('project_id', $projectID)->count() + 1;
        $requirements = Requirement::where('project_id', $projectID)->get();
        // Generate formatted requirement number
        $requirementNumber = "Requirement - {$projectID} - " . str_pad($requirementCount, 3, '0', STR_PAD_LEFT);

        return view('requirements.create', compact('project', 'projectName', 'service', 'requirementNumber', 'requirements'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'user' => 'required|string|max:255',
            'requirement_title' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'requirement_type' => 'required|string|max:255',
            'requirement_number' => 'required',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray()); // ✅ Logs errors

            return response()->json([
                'success' => false,
                'message' => 'Validation failed!',
                'errors' => $validator->errors()
            ], 422);
        }

        $requirement = Requirement::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Requirement added successfully!',
            'requirement' => $requirement
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requirement $requirement)
    {
        return view('requirement.edit', compact('requirement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requirement $requirement)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user' => 'required|string|max:255',
            'requirement_title' => 'required|string|max:255',
            'category_id' => 'required|int',
            'requirement_type' => 'required|string|max:255',
            'requirement_number' => 'required',
            'date' => 'required|date',
        ]);

        $requirement->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Requirement updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requirement $requirement)
    {
        $requirement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Requirement deleted successfully!',
        ]);
    }
}
