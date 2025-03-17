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

    public function execute($id)
    {
        $testCase = TestCase::findOrFail($id);
        $project = $testCase->project;

        $executions = Execution::where('project_id', $project->id)->get();

        return view('execute.execute', compact('project', 'executions'));
    }


    public function executeTest($id, Request $request)
    {
        $project = Project::findOrFail($id);
        $environment = $request->input('environment'); 

        if (!$environment) {
            return back()->withErrors(['environment' => 'Test environment is required.']);
        }

        $testCases = TestCase::where('project_id', $project->id)->get();

        if ($testCases->isEmpty()) {
            return back()->withErrors(['test_case' => 'No test cases found for this project.']);
        }

        $testCase = $testCases->first();

        $execution = Execution::firstOrCreate(
            [
                'project_id' => $project->id,
                'test_case_id' => $testCase->id, 
                'environment' => $environment,
            ],
            [
                'tester_name' => auth()->user()->name ?? 'Unknown',
                'status' => 'Not Started', 
            ]
        );

        return view('execute.executeTest', compact('project', 'environment', 'testCases', 'execution'));
    }


    public function view(Request $request)
    {
        $project = Project::find($request->query('project_id'));

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found');
        }

        $testCases = TestCase::where('project_id', $project->id)->get(); 
        $service = $project->service ?? 'Default Service'; 

        return view('testcases.view', compact('testCases', 'project', 'service'));
    }

    public function showLanding(Request $request)
    {
        return view('landing');
    }


    public function index(Request $request)
    {
        
        $projectId = $request->query('project_id');  
        $project = Project::with('testCases')->find($projectId);

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        $testCases = $project->testCases()->with('category')->get();

        $categories = Category::all();

        return view('testcases.index', [
            'testCases' => $testCases,
            'projectId' => $project->id,
            'projectName' => $project->name,
            'service' => $project->service ?? 'Default Service',
            'categories' => $categories, 
        ]);
    }

    public function create(Request $request)
    {
        $project = Project::find($request->query('project_id'));
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        $categories = Category::all();

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
