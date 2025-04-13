<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\TestCase;
use Illuminate\Http\Request;

class IssueController extends Controller
{

    /**
     * Other way of adding issue
     */

    // public function showAddIssueForm()
    // {
    //     $services = Project::select('service')->distinct()->pluck('service');
    //     $projects = Project::all(['id', 'name', 'service']);

    //     // Fetch failed test cases with category name
    //     $failedTestCases = TestCase::where('status', 'Fail')->get(['id', 'test_title', 'test_environment', 'test_step', 'test_case_no', 'category_id', 'tester']);

    //     $developers = ['Dev1', 'Dev2', 'Dev3'];

    //     return view('issue.add', compact('projects', 'failedTestCases', 'developers', 'services'));
    // }

    public function showAddIssueForm(Request $request)
    {
        // Fetch projects, failed test cases, and developers
        $projects = Project::all(['id', 'name', 'service']);
        $failedTestCases = TestCase::where('status', 'Fail')->get(['id', 'test_title', 'test_environment', 'test_step', 'test_case_no', 'category_id', 'tester']);
        $developers = ['Dev1', 'Dev2', 'Dev3'];

        // Fetch the project and service based on the provided project_id
        $project = Project::find($request->input('project_id'));
        $project_name = $project ? $project->name : null;
        $service = $project ? $project->service : null;

        // Return the view with the necessary data
        return view('issue.add', compact('projects', 'failedTestCases', 'developers', 'project_name', 'service'));
    }


    public function saveNewIssue(Request $request)
    {
    

        $validated = $request->validate([
            'test_case_id' => 'required|integer',
            'project_id' => 'required|integer',
            'issue_number' => 'required|string',
            'issue_title' => 'required|string|max:255',
            'issue_description' => 'required|string',
            'date_time_report' => 'required',
            'tester' => 'required',
            'environment' => 'required',
            'status' => 'required',
            'project_name' => 'required|string',
            'screenshot_url' => 'nullable|string',
            'assigned_developer' => 'nullable|string',
        ]);

        Issue::create($validated);

        return redirect()->route('issue.index')->with('success', 'Issue added successfully!');
    }


    public function fetchIssues()
    {
        $issues = Issue::with('project')->get();
        return response()->json($issues);
    }




    public function getIssueCounter(Request $request)
    {
        $projectId = $request->query('projectId');

        // Find the project
        $project = Project::find($projectId);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        // Get the current issue counter (count issues linked to the project)
        $currentCounter = Issue::where('project_id', $projectId)->count();

        return response()->json(['currentCounter' => $currentCounter]);
    }


    /**
     * Display a listing of the issues.
     */
    public function index()
    {
        $issues = Issue::with('project')->latest()->get();
        $projects = Project::all();
        $testers = Issue::select('tester')->distinct()->pluck('tester');

        return view('issue.index', compact('issues', 'projects', 'testers'));
    }

    /**
     * Show the form for creating a new issue.
     */

    public function create(Request $request)
    {
        $testCaseId = $request->input('test_case_id');
        $projectId = $request->input('project_id');

        $project = Project::find($projectId);
        $testCase = TestCase::find($testCaseId);

        if (!$project || !$testCase) {
            return redirect()->back()->with('error', 'Invalid project or test case ID.');
        }

        // Provide dummy developers since there's no Developer model
        $developers = ['Dev1', 'Dev2', 'Dev3']; // Replace with real logic if needed

        $existingIssues = Issue::where('project_id', $projectId)->count();
        // $issueNumber = sprintf('BELL-%d-%03d', $project->id, $existingIssues + 1);
        $issueNumber = generateIssueNumber($project->id);

        return view('issue.create', [
            'testCase' => $testCase,
            'project' => $project,
            'issueNumber' => $issueNumber,
            'developers' => $developers, // Pass dummy developers
        ]);
    }



    /**
     * Store a newly created issue in storage.
     */


    public function store(Request $request)
    {
    

        // Validate input data
        $validated = $request->validate([
            'test_case_id' => 'required|integer',
            'project_id' => 'required|integer',
            'issue_number' => 'required|string',
            'issue_title' => 'required|string|max:255',
            'execution_id' => 'nullable|string',
            'issue_description' => 'required|string',
            'date_time_report' => 'required|date',  
            'tester' => 'required|string',
            'environment' => 'required|string',
            'status' => 'required|string',
            'project_name' => 'required|string',
            'screenshot_url' => 'nullable|string',
            'assigned_developer' => 'nullable|string',
        ]);

        // Create the issue
        $issue = Issue::create($validated);

        // Return JSON response
        return response()->json([
            'success' => (bool) $issue,
            'message' => $issue ? 'Issue added successfully!' : 'Failed to create issue.'
        ], $issue ? 200 : 500);
    }









    /**
     * Update an existing issue.
     */
    public function updateIssue(Request $request)
    {
        $request->validate([
            'status' => 'required|in:In Progress,Resolved,Closed,Reopened',
            'developer_notes' => 'nullable|string',
        ]);

        $issue = Issue::findOrFail($request->issue_id);
        $issue->status = $request->status;

        if ($request->has('developer_notes')) {
            $issue->developer_notes = $request->developer_notes;
        }

        $issue->save();

        return back()->with('success', 'Issue updated successfully.');
    }

    public function getLastIssueNumber($projectId)
    {
        $lastIssue = Issue::where('project_id', $projectId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastIssue) {
            // Extract the last counter value (e.g., from BELL-1-023 → 023)
            preg_match('/BELL-\d+-(\d+)/', $lastIssue->issue_number, $matches);
            return response()->json(['last_issue_number' => (int) ($matches[1] ?? 0)]);
        }

        return response()->json(['last_issue_number' => 0]); // No issues exist
    }
}
