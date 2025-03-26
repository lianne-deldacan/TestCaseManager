<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\TestCase;
use Illuminate\Http\Request;

class IssueController extends Controller
{

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
        // Retrieve project and test case IDs from request
        $testCaseId = $request->input('test_case_id');
        $projectId = $request->input('project_id');

        // Fetch project and test case
        $project = Project::find($projectId);
        $testCase = TestCase::find($testCaseId);

        if (!$project || !$testCase) {
            return redirect()->back()->with('error', 'Invalid project or test case ID.');
        }

        // Generate issue number for the selected test case and project
        $existingIssues = Issue::where('project_id', $projectId)->count();
        $issueNumber = sprintf('BELL-%d-%03d', $project->id, $existingIssues + 1);

        // Pass data to the view
        return view('issue.create', [
            'testCase' => $testCase,
            'project' => $project,
            'issueNumber' => $issueNumber,
            'developers' => ['Dev1', 'Dev2', 'Dev3'], // Replace with dynamic developer data
        ]);
    }



    /**
     * Store a newly created issue in storage.
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'test_case_id' => 'required|integer',
        //     'project_name' => 'required|string',
        //     'project_service' => 'nullable|string',
        //     'tester' => 'required|string',
        //     'date_time_report' => 'required|date',
        //     'environment' => 'required|string',
        //     'status' => 'required|string',
        //     'failed_test_case_no' => 'nullable|string',
        //     'category' => 'nullable|string',
        //     'assigned_developer' => 'nullable|string',
        //     'issue_title' => 'required|string|max:255',
        //     'issue_description' => 'required|string', // Test step
        //     'screenshot_url' => 'nullable|url',
        // ]);
        dd($request->all());

        // Handle project ID and other dependent fields if needed
        $validated['project_id'] = $request->input('project_id') ?? null; // Handle missing project ID gracefully

        Issue::create($validated);

        return redirect()->route('issue.index')->with('success', 'Issue added successfully!');
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
