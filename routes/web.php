<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExecutionController;
use App\Http\Controllers\RequirementController;
Route::get('/', function () {
    return view('welcome');
});

// Test case routes
Route::get('/testcases', [TestCaseController::class, 'index'])->name('testcases.index');
Route::get('/testcases/create', [TestCaseController::class, 'create'])->name('testcases.create'); // Show create form
Route::post('/testcases', [TestCaseController::class, 'store'])->name('testcases.store'); // Store a new test case
Route::get('/testcases/view', [TestCaseController::class, 'view'])->name('testcases.view'); // View specific test case
Route::get('/testcases/{id}/edit', [TestCaseController::class, 'edit'])->name('testcases.edit'); // Edit a test case
Route::put('/testcases/{id}', [TestCaseController::class, 'update'])->name('testcases.update'); // Update test case
Route::delete('/testcases/{id}', [TestCaseController::class, 'destroy'])->name('testcases.destroy'); // Delete test case
// Route::get('/execute/{id}', [ExecuteController::class, 'index'])->name('execute.index'); // Execute test case

Route::post('/testcases/import', [TestCaseController::class, 'import'])->name('testcases.import');
Route::get('/testcases/export/csv', [TestCaseController::class, 'exportCSV'])->name('testcases.export.csv');
Route::get('/testcases/export/excel', [TestCaseController::class, 'exportExcel'])->name('testcases.export.excel');
Route::get('/testcases/export/pdf', [TestCaseController::class, 'exportPDF'])->name('testcases.export.pdf');

// Project Routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');


//Route for getting the list of projects based on service.
Route::get('/get-projects/{service}', [ProjectController::class, 'getProjects']);
Route::get('/select', [TestCaseController::class, 'showLanding'])->name('landing');

//Home page
Route::get('/', function () { return view('index');});

//Route for edit, delete, get
Route::put('/testcases/{id}', [TestCaseController::class, 'update'])->name('testcases.update');
Route::delete('/delete-case/{id}', [TestCaseController::class, 'destroy'])->name('delete-case');
Route::get('/execute/{id}', [TestCaseController::class, 'execute'])->name('execute');

//Route for Categories
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::get('/get-services-from-categories', [CategoryController::class, 'getUniqueServices']);
Route::get('/get-categories-by-service/{service}', [CategoryController::class, 'getCategoriesByService']);

//Route for execute 
Route::get('/projects/{id}/execute', [ExecutionController::class, 'execute'])->name('execute.project');
Route::post('/projects/{id}/execute', [ExecutionController::class, 'updateStatus'])->name('execute.update');

//Route for Requirements
Route::get('/requirements', [RequirementController::class, 'index'])->name('requirements.index');
Route::get('/requirements/create', [RequirementController::class, 'create'])->name('requirements.create'); 
Route::post('/requirements', [RequirementController::class, 'store'])->name('requirements.store'); 
Route::get('/requirements/{id}/edit', [RequirementController::class, 'edit'])->name('requirements.edit'); 
Route::put('/requirements/{id}', [RequirementController::class, 'update'])->name('requirements.update'); 
Route::delete('/requirements/{id}', [RequirementController::class, 'destroy'])->name('requirements.destroy'); 
