<?php

namespace App\Exports;

use App\Models\TestCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TestCasesExport implements FromCollection, WithHeadings
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return TestCase::with(['project', 'category']) // Eager load relationships
      ->get()
      ->map(function ($case) {
        return [
          'Project Name' => $case->project->name ?? 'N/A',
          'Service' => $case->project->service ?? 'N/A',
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

  /**
   * @return array
   */
  public function headings(): array
  {
    return ['Project Name', 'Service', 'Tester', 'Test Case No.', 'Test Title', 'Test Step', 'Category', 'Date', 'Priority', 'Status'];
  }
}
