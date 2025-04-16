<?php

namespace App\Exports;

use App\Models\TestCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TestCasesExport implements FromCollection, WithHeadings
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function collection()
    {
        return TestCase::where('project_id', $this->project_id)->with(['project', 'category'])->get()->map(function ($case) {
            return [
                'Project Name' => $case->project->name ?? 'N/A',
                'Tester' => $case->tester,
                'Test Case No.' => $case->test_case_no,
                'Test Title' => $case->test_title,
                'Test Step' => $case->test_step,
                'Category' => $case->category->name ?? 'N/A',
                'Date' => $case->date_of_input,
                'Priority' => $case->priority,
                'Status' => $case->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Project', 'Tester', 'Test Case No.', 'Test Title', 'Test Step', 'Category', 'Date', 'Priority', 'Status'];
    }
}
