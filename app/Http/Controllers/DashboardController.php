<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TestCase;
use App\Models\Category;
use App\Models\Requirement;
use App\Models\Issue;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $projectsCount = Project::count();
        $testCasesCount = TestCase::count();
        $categoriesCount = Category::count();
        $requirementsCount = Requirement::count();

        $issuesCount = Issue::count();
        $passCount = Issue::where('status', 'pass')->count();
        $failCount = Issue::where('status', 'fail')->count();
        $naCount = Issue::where('status', 'N/A')->count();
        $nrCount = Issue::where('status', 'N/R')->count();

        Log::debug('Pass Count: ' . $passCount);
        Log::debug('Fail Count: ' . $failCount);
        Log::debug('N/A Count: ' . $naCount);
        Log::debug('N/R Count: ' . $nrCount);
        Log::debug('Total Issues Count: ' . $issuesCount);

        return view('dashboard.index', compact(
            'issuesCount',
            'passCount',
            'failCount',
            'naCount',
            'nrCount',
            'projectsCount',
            'testCasesCount',
            'categoriesCount',
            'requirementsCount'
        ));
    }
}
