<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

// Test routes
Route::get('/testcases', [TestCaseController::class, 'index'])->name('testcases.index');
Route::get('/testcases/create', [TestCaseController::class, 'create'])->name('testcases.create');
Route::post('/testcases', [TestCaseController::class, 'store'])->name('testcases.store');

Route::get('/testcases/view', [TestCaseController::class, 'view'])->name('testcases.view');

Route::post('/testcases/import', [TestCaseController::class, 'import'])->name('testcases.import');
Route::get('/testcases/export/csv', [TestCaseController::class, 'exportCSV'])->name('testcases.export.csv');
Route::get('/testcases/export/excel', [TestCaseController::class, 'exportExcel'])->name('testcases.export.excel');
Route::get('/testcases/export/pdf', [TestCaseController::class, 'exportPDF'])->name('testcases.export.pdf');

// Project routes
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



