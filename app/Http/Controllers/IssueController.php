<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Execution;
use App\Models\TestCase;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::with(['project', 'execution'])->latest()->get();
        $projects = Project::all();
        $testers = Issue::select('tester')->distinct()->pluck('tester');

        return view('issue.index', compact('issues', 'projects', 'testers'));
    }


    public function create(Request $request)
    {
        $projectId = $request->input('project_id');
        $testCaseId = $request->input('test_case_id');
        $testerName = $request->input('tester', 'N/A'); // Default to 'N/A' if not provided

        // Fetch project if project_id is provided
        $project = $projectId ? Project::find($projectId) : Project::latest()->first();

        // Fetch the entire test case if test_case_id is provided
        $testCase = $testCaseId ? TestCase::find($testCaseId) : null;

        $developers = ['Dev1', 'Dev2', 'Dev3'];

        return view('issue.create', compact('project', 'testerName', 'testCase', 'developers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'test_case_id' => 'required|integer',
            'project_id' => 'required|integer',
            'issue_number' => 'required|string',
            'issue_title' => 'required|string|max:255',
            'execution_id' => 'nullable',
            'issue_description' => 'required|string',
            'date_time_report' => 'required',
            'project_id' => 'required',
            'tester' => 'required',
            'environment' => 'required',
            'status' => 'required',
            'project_name' => 'required|string',
            'assigned_developer' => 'nullable|string',
        ]);
        $validated['project_name'] = $validated['project_name'] ?? $request->input('project_name');
        Issue::create($validated);
        return redirect()->route('issue.index')->with('success', 'Issue added successfully!');
    }


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
