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


    public function create()
    {
        
        $execution = Execution::latest()->first();
        $project = $execution ? Project::find($execution->project_id) : null;
        $tester = $execution ? TestCase::where('id', $execution->test_case_id)->value('tester') : null;
        $failedCases = TestCase::whereIn('id', Execution::pluck('test_case_id'))->where('status', 'Failed')->pluck('id')->toArray();
        $developers = ['Dev1', 'Dev2', 'Dev3']; 
       
        $failedCases = TestCase::whereIn('id', Execution::where('status', 'Failed')->pluck('test_case_id'))->pluck('id')->toArray();
        
        return view('issue.create', compact('execution', 'project', 'tester', 'failedCases', 'developers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'issue_title' => 'required|string|max:255',
            'issue_description' => 'required|string',
            'project_id' => 'required',
            'execution_id' => 'required',
            'tester' => 'required',
            'environment' => 'required',
            'status' => 'required',
            'project_name' => 'required|string',
            'assigned_developer' => 'nullable|string', // Allow nullable value
        ]);

        $validated['issue_number'] = uniqid('ISSUE_');
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
}
