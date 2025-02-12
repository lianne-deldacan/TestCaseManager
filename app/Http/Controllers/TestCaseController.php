<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TestCasesExport;
use App\Imports\TestCasesImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class TestCaseController extends Controller
{
    public function index()
    {
        $testCases = TestCase::all();
        return view('testcases.index', compact('testCases'));
    }

    public function create()
    {
        return view('testcases.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'test_case_no' => 'required',
                'test_environment' => 'required',
                'tester' => 'required',
                'date_of_input' => 'required|date',
                'test_title' => 'required',
                'test_description' => 'required',
                'status' => 'required',
                'priority' => 'required',
                'severity' => 'required',
                'screenshot' => 'nullable|string',
            ]);

            // Store data in database
            TestCase::create($request->all());

            return redirect()->route('testcases.index')->with('success', 'Test Case added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,xlsx|max:2048',
            ]);
    
            // Move the uploaded file to a temporary location
            $file = $request->file('file');
    
            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file uploaded.');
            }
    
            Excel::import(new TestCasesImport, $file);
    
            return back()->with('success', 'Test Cases imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->with('error', 'Import Failed: ' . $failures[0]->errors()[0]);
        } catch (\Exception $e) {
            \Log::error('Import Error: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }    

    // Export CSV
    public function exportCSV()
    {
        try {
            return Excel::download(new TestCasesExport, 'testcases.csv');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export Excel
    public function exportExcel()
    {
        try {
            return Excel::download(new TestCasesExport, 'testcases.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Export Failed: ' . $e->getMessage());
        }
    }

    // Export PDF
    public function exportPDF()
    {
        try {
            $testCases = TestCase::all();
            $pdf = Pdf::loadView('exports.testcases_pdf', compact('testCases'));
            return $pdf->download('testcases.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'PDF Export Failed: ' . $e->getMessage());
        }
    }
}