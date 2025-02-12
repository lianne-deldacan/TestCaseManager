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
    return TestCase::select('test_case_no', 'test_environment', 'tester', 'date_of_input', 'test_title', 'test_description', 'status', 'priority', 'severity', 'screenshot')->get();
  }

  /**
   * @return array
   */
  public function headings(): array
  {
    return ['Test Case No.', 'Environment', 'Tester', 'Date', 'Title', 'Description', 'Pass/Fail', 'Priority', 'Severity', 'Screenshot'];
  }
}
