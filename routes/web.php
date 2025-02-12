<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\TestCaseController;

Route::get('/', function () {
    return view('welcome'); // or your custom view
});

Route::get('/testcases', [TestCaseController::class, 'index'])->name('testcases.index');
Route::get('/testcases/create', [TestCaseController::class, 'create'])->name('testcases.create');
Route::post('/testcases', [TestCaseController::class, 'store'])->name('testcases.store');

Route::post('/testcases/import', [TestCaseController::class, 'import'])->name('testcases.import');
Route::get('/testcases/export/csv', [TestCaseController::class, 'exportCSV'])->name('testcases.export.csv');
Route::get('/testcases/export/excel', [TestCaseController::class, 'exportExcel'])->name('testcases.export.excel');
Route::get('/testcases/export/pdf', [TestCaseController::class, 'exportPDF'])->name('testcases.export.pdf');