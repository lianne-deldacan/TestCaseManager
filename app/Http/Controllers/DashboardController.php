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
        // Count data for projects, test cases, categories, and requirements
        $projectsCount = Project::count();
        $testCasesCount = TestCase::count();
        $categoriesCount = Category::count();
        $requirementsCount = Requirement::count();

        // Count data for issues: pass, fail, N/A, N/R, and total
        $issuesCount = Issue::count();
        $passCount = Issue::where('status', 'pass')->count();
        $failCount = Issue::where('status', 'fail')->count();
        $naCount = Issue::where('status', 'N/A')->count();
        $nrCount = Issue::where('status', 'N/R')->count();

        // Debugging output to check the counts
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
