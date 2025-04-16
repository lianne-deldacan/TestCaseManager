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
        // Fetch Projects and Services for dropdowns
        $projects = Project::all();
        $services = config('global.services'); // Assuming services are in a global config

        // Get filter values
        $selectedProject = request('project');
        $selectedService = request('service');

        // Get the filtered counts
        $query = Issue::query();

        if ($selectedProject) {
            $query->where('project_id', $selectedProject);
        }

        if ($selectedService) {
            $query->where('service', $selectedService);
        }

        $issuesCount = $query->count();
        $passCount = $query->where('status', 'pass')->count();
        $failCount = $query->where('status', 'fail')->count();
        $naCount = $query->where('status', 'N/A')->count();
        $nrCount = $query->where('status', 'N/R')->count();

        $projectsCount = Project::count();
        $testCasesCount = TestCase::count();
        $categoriesCount = Category::count();
        $requirementsCount = Requirement::count();

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
            'requirementsCount',
            'projects',
            'services'
        ));
    }
}
