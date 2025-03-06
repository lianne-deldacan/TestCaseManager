<?php

namespace App\Http\Controllers;

use App\Models\Execution;
use App\Models\Project;
use Illuminate\Http\Request;

class ExecutionController extends Controller
{
    public function execute($projectId)
    {
        $project = Project::find($projectId);

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        return view('execute.execute', [
            'project' => $project,
            'tester' => auth()->user()->name ?? 'Tester Name',
        ]);
    }

    public function updateStatus(Request $request, $projectId)
    {
        $execution = Execution::firstOrCreate(
            ['project_id' => $projectId],
            [
                'environment' => $request->environment,
                'tester_name' => $request->tester_name,
            ]
        );

        $execution->status = $request->status;
        $execution->save();

        return response()->json([
            'success' => true,
            'message' => 'Execution status updated successfully.',
            'execution' => $execution,
        ]);
    }
}
