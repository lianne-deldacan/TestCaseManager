<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Project;

class ChartController extends Controller
{
    public function showChart()
    {
        $issuesByStatus = Issue::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        $issuesByStatusData = $issuesByStatus->map(function ($item) {
            return [$item->status, $item->total];
        });

        $projectDistribution = Project::withCount('issues')
            ->get();

        $projectDistributionData = $projectDistribution->map(function ($project) {
            return [$project->name, $project->issues_count];
        });

        return view('charts.index', compact('issuesByStatusData', 'projectDistributionData'));
    }
}
