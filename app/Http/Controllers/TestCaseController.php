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
            $testCase = TestCase::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Test Case added successfully.',
                'test_case' => $testCase
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    //Import file (MODIFIED)
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv|max:2048',
            ]);

            $file = $request->file('file');

            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file uploaded.');
            }

            // Debugging: Check file content before importing
            $data = \Maatwebsite\Excel\Facades\Excel::toCollection(new TestCasesImport, $file);

            // Access the first collection (index 0)
            $rows = $data->first();

            // Now loop through each row correctly
            foreach ($rows as $row) {
                TestCase::create([
                    'test_case_no'     => $row['test_case_no'],
                    'test_environment' => $row['test_environment'],
                    'tester'           => $row['tester'],
                    'date_of_input'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_input']),
                    'test_title'       => $row['test_title'],
                    'test_description' => $row['test_description'],
                    'status'           => $row['status'],
                    'priority'         => $row['priority'],
                    'severity'         => $row['severity'],
                    'screenshot'       => $row['screenshot'],
                ]);
            }

            return response()->json(['message' => 'Import successful']);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->with('error', 'Import Failed: Validation error in the file.');
        } catch (\Exception $e) {
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
            $pdf = Pdf::loadView('exports.testcases_pdf', compact('testCases'))->setPaper('A4', 'portrait');
            return $pdf->download('testcases.pdf');
     }   catch (\Exception $e) {
            return back()->with('error', 'PDF Export Failed: ' . $e->getMessage());
         }
    }

}