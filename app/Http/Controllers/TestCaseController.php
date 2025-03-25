<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestCase;
use App\Models\Project;
use App\Models\Category;
use App\Models\Execution;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TestCasesExport;
use App\Imports\TestCasesImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class TestCaseController extends Controller
{
    // Method to fetch project details
    public function getProjectDetails($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $testCases = TestCase::where('project_id', $id)->get();

        return response()->json([
            'project' => [
                'test_case_no' => $project->test_case_no,
                'test_title' => $project->test_title,
                'test_step' => $project->test_step,
                'priority' => $project->priority,
                'date_of_input' => $project->date_of_input,
            ],
            'testCases' => $testCases,
        ]);
    }



    public function getTestCaseData(Request $request)
    {
        $request->validate(['project_id' => 'required|integer']);

        $testCases = TestCase::where('project_id', $request->project_id)->get();

        return response()->json(['testCases' => $testCases]);
    }

    public function getProjectsByService(Request $request)
    {
        $service = $request->input('service');

        if (!$service) {
            return response()->json(['error' => 'Service is required'], 400);
        }

        $projects = Project::where('service', $service)->pluck('name', 'id'); // Get project names and IDs

        if ($projects->isEmpty()) {
            return response()->json(['error' => 'No projects found for the selected service.'], 404);
        }

        return response()->json($projects); // Return projects as JSON
    }








    public function executeTest(Request $request)
    {
        // Fetch services
        $services = Project::select('service')->distinct()->pluck('service');

        // Fetch projects if service is selected
        $projects = [];
        if ($request->filled('service')) {
            $projects = Project::where('service', $request->input('service'))
                ->with([
                    'testCases' => function ($query) {
                        $query->select('id', 'test_case_no', 'test_title', 'test_step', 'test_environment', 'tester', 'date_of_input', 'category_id', 'priority');
                    }
                ])
                ->get(['id', 'name']);
        }

        // Fetch test cases if project_id is selected
        $testCases = [];
        if ($request->filled('project_id')) {
            $testCases = TestCase::where('project_id', $request->input('project_id'))
                ->with('category')  // Assuming there’s a category relationship
                ->get();
        }

        // Fetch categories (whether active or disabled)
        $categories = Category::all();

        return view('testcases.executeTestcase', compact('services', 'projects', 'testCases', 'categories'));
    }



    public function view(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            return redirect()->back()->with('error', 'Project ID is required');
        }

        $project = Project::find($projectId);
        if (!$project) {
            return redirect()->back()->with('error', 'Project not found');
        }

        $testCases = TestCase::where('project_id', $project->id)->get();
        $service = $project->service ?? 'Default Service';

        return view('testcases.view', [
            'testCases' => $testCases,
            'project' => $project, // Pass the project variable
            'service' => $service, // Pass the service variable
        ]);
    }





    public function showLanding(Request $request)
    {
        return view('landing');
    }


    public function index(Request $request)
    {
        $projectId = $request->query('project_id');

        if (!$projectId) {
            return redirect()->route('projects.index')->with('error', 'Project ID is required.');
        }

        $project = Project::find($projectId);

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        $testCases = $project->testCases()->get(); // Fetch test cases associated with the project

        return view('testcases.index', [
            'project' => $project,  // Pass the project to the view
            'testCases' => $testCases,
        ]);
    }






    public function create(Request $request)
    {
        // Get project ID from the request
        $projectId = $request->query('project_id');

        if (!$projectId) {
            return redirect()->route('projects.index')->with('error', 'Project ID is required.');
        }

        // Fetch the project based on the ID
        $project = Project::find($projectId);

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        // Fetch all categories
        $categories = Category::all();

        // Fetch test cases associated with the project
        $testCases = TestCase::where('project_id', $project->id)->get();

        // Fetch distinct services for the dropdown
        $services = Project::select('service')->distinct()->pluck('service');

        // Return view with required variables
        return view('testcases.create', [
            'project' => $project,           // Pass the project details
            'projectId' => $project->id,     // Pass project ID
            'projectName' => $project->name, // Pass project name
            'service' => $project->service ?? 'Default Service', // Pass selected service
            'services' => $services,         // Pass list of services
            'categories' => $categories,     // Pass categories
            'testCases' => $testCases,       // Pass test cases
        ]);
    }







    public function store(Request $request)
    {
        $validated = $request->validate([
            'service' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'test_case_no' => 'required|string|max:255',
            'test_title' => 'required|string|max:255',
            'test_step' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'tester' => 'required|string|max:255',
            'date_of_input' => 'required|date',
            'status' => 'required|string|max:255',
            'priority' => 'required|string|max:255',
            'test_environment' => 'required|string|in:SIT,UAT',
        ]);

        $testCase = TestCase::create($validated);

        return redirect()->route('testcases.index', ['project_id' => $request->project_id])
            ->with('success', 'Test case created successfully.');
    }






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

        $testCase = TestCase::findOrFail($id);

        $categories = Category::all();
        return view('testcases.edit', [
            'testCase' => $testCase,
            'categories' => $categories,
        ]);
    }

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
        $projectId = $request->query('project_id');
        if (!$projectId) {
            return back()->with('error', 'Project ID is required.');
        }

        $project = Project::find($projectId);
        if (!$project) {
            return back()->with('error', 'Project not found.');
        }

        try {
            return Excel::download(new TestCasesExport($projectId), 'testcases.csv');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            return back()->with('error', 'Project ID is required.');
        }

        $project = Project::find($projectId);
        if (!$project) {
            return back()->with('error', 'Project not found.');
        }

        try {
            return Excel::download(new TestCasesExport($projectId), 'testcases.xlsx');
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

        $project = Project::find($projectId);
        if (!$project) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        $testCases = TestCase::where('project_id', $projectId)->get();

        if ($testCases->isEmpty()) {
            return redirect()->back()->with('error', 'No test cases found for the selected project.');
        }

        try {
            $pdf = Pdf::loadView('exports.testcases_pdf', compact('testCases', 'project'))->setPaper('a4', 'portrait');
            return $pdf->download("testcases_project_{$projectId}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'PDF Export Failed: ' . $e->getMessage());
        }
    }
}


