<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestCase;
use App\Models\Project;
use App\Models\Issue;

class ExecutionController extends Controller
{

    public function create()
    {
        return view('issue.create');
    }


    public function generateIssueNumber()
    {
        $randomNumber = rand(1000, 9999);
        $issueNumber = "BELL-" . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
        return response()->json(['issue_number' => $issueNumber]);
    }

    public function showExecutionPage($projectId)
    {
        $project = Project::findOrFail($projectId);
        $testCases = TestCase::where('project_id', $projectId)->get();

        return view('execute.executeTest', compact('project', 'testCases'));
    }

    public function showTestCases($projectId)
    {
        $project = Project::findOrFail($projectId);
        $testCases = TestCase::where('project_id', $projectId)->get();

        return view('testcases.executeTestCase', compact('project', 'testCases'));
    }


    public function executeTestCase(Request $request)
    {
        $request->validate([
            'test_case_id' => 'required|exists:test_cases,id',
        ]);

        $testCase = TestCase::findOrFail($request->test_case_id);
        $testCase->status = 'Executed'; // Example logic
        $testCase->execution_date = now();
        $testCase->save();

        // Redirect to execution page with the project ID
        return redirect()->route('executeTest', ['projectId' => $testCase->project_id])
            ->with('success', "Test Case {$testCase->id} executed successfully.");
    }




    public function updateStatus(Request $request)
    {
        $request->validate([
            'test_case_id' => 'required|exists:test_cases,id',
            'status' => 'required|string|in:Pass,Fail,N/A',
        ]);

        $testCase = TestCase::find($request->test_case_id);
        $testCase->status = $request->status;
        $testCase->save();

        return response()->json([
            'message' => 'Test case status updated successfully.',
            'test_case' => $testCase,
        ]);
    }

    public function createIssue(Request $request)
    {
        $request->validate([
            'testcase_id' => 'required|exists:test_cases,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $lastIssue = Issue::where('project_id', $request->project_id)->latest()->first();
        $issueNumber = $lastIssue ? ((int) substr($lastIssue->issue_number, -4)) + 1 : 1;
        $formattedIssueNumber = "BELL-" . $request->project_id . "-" . str_pad($issueNumber, 4, '0', STR_PAD_LEFT);

        $issue = Issue::create([
            'testcase_id' => $request->testcase_id,
            'project_id' => $request->project_id,
            'issue_number' => $formattedIssueNumber
        ]);

        return response()->json([
            'success' => true,
            'issue_number' => $formattedIssueNumber
        ]);
    }
}
