<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestCase;

class ExecuteController extends Controller
{
    public function execute($id)
    {
        $testCase = TestCase::with('project')->findOrFail($id);
        return view('testcases.execute', compact('testCase'));
    }
}
