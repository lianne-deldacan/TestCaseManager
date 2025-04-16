<?php

use Illuminate\Support\Facades\Route;
use App\Exports\IssuesExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExecutionController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => ['guest']], function () {
    // login, forgot password, email verification
    Route::get('/login', function () {
        //login page
        dd('login page');
    })->name('login');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    //Home page
    // Route::get('/', function () {
    //     return view('index');
    // });
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    // Project Routes
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
    });
    
    //Route for getting the list of projects based on service. (ajax)
    Route::get('/get-projects/{service}', [ProjectController::class, 'getProjects']);

    //Route for Requirements
    Route::prefix('requirements')->group(function () {
        Route::get('/', [RequirementController::class, 'index'])->name('requirements.index');
        Route::get('/create', [RequirementController::class, 'create'])->name('requirements.create');
        Route::post('/', [RequirementController::class, 'store'])->name('requirements.store');
        Route::get('/{requirement}', [RequirementController::class, 'edit'])->name('requirements.edit');
        Route::put('/{requirement}', [RequirementController::class, 'update'])->name('requirements.update');
        Route::delete('/{id}', [RequirementController::class, 'destroy'])->name('requirements.destroy');
        Route::get('/export/csv', [RequirementController::class, 'exportCSV'])->name('requirements.export.csv');
        Route::get('/export/excel', [RequirementController::class, 'exportExcel'])->name('requirements.export.excel');
        Route::get('/export/pdf', [RequirementController::class, 'exportPDF'])->name('requirements.export.pdf');
    });

    //User routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Test case routes
    Route::get('/testcases', [TestCaseController::class, 'index'])->name('testcases.index');
    Route::get('/testcases/create', [TestCaseController::class, 'create'])->name('testcases.create'); // Show create form
    Route::post('/testcases', [TestCaseController::class, 'store'])->name('testcases.store'); // Store a new test case
    Route::get('/testcases/view', [TestCaseController::class, 'view'])->name('testcases.view'); // View specific test case
    Route::get('/testcases/{id}/edit', [TestCaseController::class, 'edit'])->name('testcases.edit'); // Edit a test case
    Route::put('/testcases/{id}', [TestCaseController::class, 'update'])->name('testcases.update'); // Update test case
    Route::delete('/testcases/{id}', [TestCaseController::class, 'destroy'])->name('testcases.destroy'); // Delete test case
    Route::post('/testcases/import', [TestCaseController::class, 'import'])->name('testcases.import');
    Route::get('/testcases/export/csv', [TestCaseController::class, 'exportCSV'])->name('testcases.export.csv');
    Route::get('/testcases/export/excel', [TestCaseController::class, 'exportExcel'])->name('testcases.export.excel');
    Route::get('/testcases/export/pdf', [TestCaseController::class, 'exportPDF'])->name('testcases.export.pdf');

    //issue
    Route::prefix('issues')->group(function () {
        Route::get('/', [IssueController::class, 'index'])->name('issues'); // For displaying the list of issues
        Route::get('/add/{project_id?}/{test_case_id?}', [IssueController::class, 'showAddIssueForm'])->name('issue.add');
    });
    Route::get('/issue/add', [IssueController::class, 'add'])->name('issue.add');

    // Issue routes
    Route::post('/store-issue', [IssueController::class, 'store'])->name('issue.store');
    Route::get('/create-issue', [IssueController::class, 'create'])->name('issue.create'); // For showing the issue creation form
    
    // Route::post('/store-issue', [IssueController::class, 'store'])->name('issue.store'); // For storing the new issue
    // Route::put('/issues/update', [IssueController::class, 'updateIssue'])->name('issue.update');
    Route::get('/issues/list', [IssueController::class, 'getIssues'])->name('issue.list');
    Route::get('/api/issue-counter', [IssueController::class, 'getIssueCounter']);
    
    Route::post('/issue/save', [IssueController::class, 'saveNewIssue'])->name('issue.save');
    Route::get('/issues/fetch', [IssueController::class, 'fetchIssues'])->name('issue.fetch');

    //Test case to get project details (ajax)
    Route::get('/get-project-details/{id}', [TestCaseController::class, 'getProjectDetails']);
    //Execute a test case
    Route::get('/execute-testcases', [TestCaseController::class, 'executeTest'])->name('executeTestcases');
    // Route::get('/execute/{id}', [ExecuteController::class, 'index'])->name('execute.index'); // Execute test case

    Route::get('/select', [TestCaseController::class, 'showLanding'])->name('landing');

    //Auth routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
// Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
// Route::post('/users', [UserController::class, 'store'])->name('users.store');
// // Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Removed temporarily for test 
// Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
// Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
// Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::post('/get-testcases', [TestCaseController::class, 'getTestCasesByProject'])->name('testcases.getByProject'); //need pa ba?

//Route for edit, delete, get
Route::put('/testcases/{id}', [TestCaseController::class, 'update'])->name('testcases.update');
Route::delete('/delete-case/{id}', [TestCaseController::class, 'destroy'])->name('delete-case');
// Routes for Test Cases
Route::get('/testcases/create', [TestCaseController::class, 'create'])->name('testcases.create');
Route::get('/testcases', [TestCaseController::class, 'index'])->name('testcases.index');
// Fetch projects by service
Route::post('/projects/getByService', [TestCaseController::class, 'getProjectsByService'])->name('projects.getByService');
// Fetch test case data for a specific project
Route::post('/testcases/getTestCaseData', [TestCaseController::class, 'getTestCaseData'])->name('testcases.getTestCaseData');
// Fetch project details
Route::get('/testcases/project-details/{id}', [TestCaseController::class, 'getProjectDetails'])->name('testcases.getDetails');
// Execute test cases
// Route::get('/execute-testcases', [TestCaseController::class, 'executeTest'])->name('executeTestcases');

//Route for Categories
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::get('/get-services-from-categories', [CategoryController::class, 'getUniqueServices']);
Route::get('/get-categories-by-service/{service}', [CategoryController::class, 'getCategoriesByService']);

// Landing page for selecting service + project
Route::get('categories/landing', [CategoryController::class, 'landing'])->name('categories.landing');
Route::get('/api/projects/by-service/{serviceKey}', [\App\Http\Controllers\CategoryController::class, 'getProjectsByService']);




//execute
Route::post('/execute/update-status', [ExecutionController::class, 'updateStatus'])->name('execute.updateStatus');
Route::get('/execute/create-issue', [ExecutionController::class, 'createIssue'])->name('execute.createIssue');
Route::post('/execute/test-case', [ExecutionController::class, 'executeTestCase'])->name('execute.testCase');
Route::get('/testcases/{projectId}', [TestCaseController::class, 'showTestCases'])->name('testcases.show');
Route::get('/execute/test/{projectId}', [ExecutionController::class, 'showExecutionPage'])->name('executeTest');

//auto issue
Route::get('/execute/generate-issue-number', [ExecutionController::class, 'generateIssueNumber'])->name('execute.generateIssueNumber');



// Edit an issue (show form)
Route::get('/issue/edit/{id}', [IssueController::class, 'edit'])->name('issue.edit');

// Update an issue (process form submission)
Route::put('/issue/update/{id}', [IssueController::class, 'update'])->name('issue.updateById');
Route::put('/issue/update/{id}', [IssueController::class, 'update'])->name('issue.update');

// Delete an issue
Route::delete('/issues/{id}', [IssueController::class, 'destroy'])->name('issue.destroy');



// routes/web.php
Route::post('/update-status', [TestCaseController::class, 'updateStatus'])->name('update.status');
Route::post('/create-issue', [IssueController::class, 'createIssue'])->name('create.issue');
Route::get('/issue-audit-trail/{issue_number}', [IssueController::class, 'getAuditTrail'])->name('issue.audittrail');
Route::get('/issues/last/{projectId}', [IssueController::class, 'getLastIssueNumber']);
Route::get('/issues/export/excel', function () {
    return Excel::download(new IssuesExport, 'issues.xlsx');
})->name('issues.export.excel');
Route::get('/issues/export/csv', function () {
    return Excel::download(new IssuesExport, 'issues.csv');
})->name('issues.export.csv');
