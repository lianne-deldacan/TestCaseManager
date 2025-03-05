<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestCase;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TestCasesExport;
use App\Imports\TestCasesImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class TestCaseController extends Controller
{
    public function view(Request $request)
    {
        $project = Project::find($request->project_id);

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found');
        }

        $testCases = TestCase::with('project')->where('project_id', $project->id)->get();

        return view('testcases.view', compact('testCases', 'project'));
    }

    // Show landing page
    public function showLanding(Request $request)
    {
        return view('landing');
    }

    public function index()
    {
        $testCases = TestCase::with('project')->get(); // Retrieve all test cases with their projects
        $projectName = $testCases->isNotEmpty() ? $testCases->first()->project->name : 'Default Project Name';
        $projectId = $testCases->isNotEmpty() ? $testCases->first()->project->id : null;

        return view('testcases.index', compact('testCases', 'projectName', 'projectId'));
    }

    public function create(Request $request)
    {
        $project = Project::find($request->query('project_id'));

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        $testCases = TestCase::where('project_id', $project->id)->get(); // Fetch test cases for the project

        return view('testcases.index', [
            'projectId' => $project->id,
            'projectName' => $project->name,
            'service' => $project->service,
            'testCases' => $testCases, // Pass test cases to the view
        ]);
    }

    public function update(Request $request, $id)
    {
        $testCase = TestCase::findOrFail($id);

        $testCase->update($request->only([
            'test_title',
            'category',
            'date_of_input',
            'test_step',
            'priority',
        ]));

        return redirect()->route('testcases.index')->with('success', 'Test case updated successfully!');
    }

    public function destroy($id)
    {
        $case = TestCase::findOrFail($id);
        $case->delete();

        return response()->json(['message' => 'Case deleted successfully.']);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'project_id' => 'required|exists:projects,id',
                'test_case_no' => 'required|string|max:255',
                'test_title' => 'required|string|max:255',
                'test_step' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'tester' => 'required|string|max:255',
                'date_of_input' => 'required|date',
                'status' => 'required|string|max:255',
                'priority' => 'required|string|max:255',
            ]);

            // Retrieve the project
            $project = Project::findOrFail($request->project_id);

            // Create test case
            $testCase = TestCase::create([
                'project_id' => $request->project_id,
                'test_case_no' => $request->test_case_no,
                'test_title' => $request->test_title,
                'test_step' => $request->test_step,
                'category' => $request->category,
                'tester' => $request->tester,
                'date_of_input' => $request->date_of_input,
                'status' => $request->status,
                'priority' => $request->priority,
            ]);

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

    // Import file
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv|max:2048',
            ]);

            $file = $request->file('file');

            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file uploaded.');
            }

            $data = Excel::toCollection(new TestCasesImport, $file);

            foreach ($data->first() as $row) {
                TestCase::create([
                    'test_case_no'     => $row['test_case_no'],
                    'test_environment' => $row['test_environment'],
                    'tester'           => $row['tester'],
                    'date_of_input'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_input']),
                    'test_title'       => $row['test_title'],
                    'test_description' => $row['test_description'],
                    'status'           => $row['status'],
                    'priority'         => $row['priority'],
                    'severity'         => $row['severity'],
                    'screenshot'       => $row['screenshot'],
                ]);
            }

            return response()->json(['message' => 'Import successful']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->with('error', 'Import Failed: Validation error in the file.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

    // Export CSV
    public function exportCSV()
    {
        try {
            return Excel::download(new TestCasesExport, 'testcases.csv');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export Excel
    public function exportExcel()
    {
        try {
            return Excel::download(new TestCasesExport, 'testcases.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export PDF
    public function exportPDF()
    {
        try {
            $testCases = TestCase::all();
            $pdf = Pdf::loadView('exports.testcases_pdf', compact('testCases'))->setPaper('A4', 'portrait');
            return $pdf->download('testcases.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'PDF Export Failed: ' . $e->getMessage());
        }
    }
}
