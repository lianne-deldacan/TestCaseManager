<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestCase;
use App\Models\Project;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TestCasesExport;
use App\Imports\TestCasesImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class TestCaseController extends Controller
{
    // View test cases for a specific project
    public function view(Request $request)
    {
        $project = Project::find($request->query('project_id'));

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found');
        }

        $testCases = TestCase::where('project_id', $project->id)->get(); // Fetch test cases for the selected project
        $service = $project->service ?? 'Default Service'; // Fallback if `service` is not defined

        return view('testcases.view', compact('testCases', 'project', 'service'));
    }

    // Show the landing page
    public function showLanding(Request $request)
    {
        return view('landing');
    }

    // List all test cases
    public function index(Request $request)
    {
        // Get the `project_id` from the query string
        $projectId = $request->query('project_id');

        // Fetch the project and its test cases
        $project = Project::with('testCases')->find($projectId);

        // Handle the case where the project does not exist
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        // Fetch test cases for the current project
        $testCases = $project->testCases()->with('category')->get();

        // Fetch all categories (you can customize this if needed)
        $categories = Category::all();

        // Pass project-specific data to the view
        return view('testcases.index', [
            'testCases' => $testCases,
            'projectId' => $project->id,
            'projectName' => $project->name,
            'service' => $project->service ?? 'Default Service',
            'categories' => $categories, // Pass categories to the view
        ]);
    }




    // Show the create form for a new test case
    public function create(Request $request)
    {
        $project = Project::find($request->query('project_id'));
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        $categories = Category::all(); // Fetch all categories for dropdown

        $testCases = TestCase::where('project_id', $project->id)->get();
        return view('testcases.index', [
            'project' => $project,
            'projectId' => $project->id,
            'projectName' => $project->name,
            'service' => $project->service ?? 'Default Service',
            'categories' => $categories,
            'testCases' => $testCases
        ]);
    }


    // Store a new test case
    public function store(Request $request)
    {
        try {
            $request->validate([
                'project_id' => 'required|exists:projects,id',
                'test_case_no' => 'required|string|max:255',
                'test_title' => 'required|string|max:255',
                'test_step' => 'required|string|max:255',
                'category_id' => 'required|integer|exists:categories,id',
                'tester' => 'required|string|max:255',
                'date_of_input' => 'required|date',
                'status' => 'required|string|max:255',
                'priority' => 'required|string|max:255',
            ]);

            // Create the test case
            $testCase = TestCase::create($request->only([
                'project_id',
                'test_case_no',
                'test_title',
                'test_step',
                'category_id',
                'tester',
                'date_of_input',
                'status',
                'priority',
            ]));

            // Fetch the newly created test case with relationships
            $testCase = TestCase::with(['project', 'category'])->find($testCase->id);

            return response()->json([
                'success' => true,
                'message' => 'Test Case added successfully.',
                'test_case' => $testCase,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    // Update an existing test case
    public function update(Request $request, $id)
    {
        $testCase = TestCase::findOrFail($id);

        $validated = $request->validate([
            'test_title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'date_of_input' => 'required|date',
            'test_step' => 'required|string|max:255',
            'priority' => 'required|in:High,Medium,Low',
        ]);

        $testCase->update($validated);

        return redirect()->route('testcases.index')->with('success', 'Test case updated successfully!');
    }


    public function edit($id)
    {
        // Fetch the test case by ID
        $testCase = TestCase::findOrFail($id);

        // Fetch related categories if needed
        $categories = Category::all();

        // Pass the test case and categories to the edit view
        return view('testcases.edit', [
            'testCase' => $testCase,
            'categories' => $categories,
        ]);
    }

    // Delete a test case
    public function destroy($id)
    {
        $testCase = TestCase::findOrFail($id);
        $testCase->delete();

        return response()->json(['message' => 'Case deleted successfully.']);
    }

    // Import file
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv|max:2048',
            ]);

            $file = $request->file('file');

            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file uploaded.'], 400);
            }

            Excel::import(new TestCasesImport, $file);

            return response()->json(['message' => 'Import successful']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json(['error' => 'Validation error in the file.', 'details' => $e->failures()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Import Failed', 'details' => $e->getMessage()], 500);
        }
    }



    // Export CSV
    public function exportCSV(Request $request)
    {
        try {
            return Excel::download(new TestCasesExport($request->query('project_id')), 'testcases.csv');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        try {
            return Excel::download(new TestCasesExport($request->query('project_id')), 'testcases.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export PDF
    public function exportPDF(Request $request)
    {
        $projectId = $request->query('project_id'); 

        if (!$projectId) {
            return redirect()->back()->with('error', 'Project ID is required.');
        }

        $testCases = TestCase::where('project_id', $projectId)->get();

        if ($testCases->isEmpty()) {
            return redirect()->back()->with('error', 'No test cases found for the selected project.');
        }

        $pdf = Pdf::loadView('exports.testcases_pdf', compact('testCases', 'projectId'))->setPaper('a4', 'portrait');

        return $pdf->download("testcases_project_{$projectId}.pdf");
    }
}
