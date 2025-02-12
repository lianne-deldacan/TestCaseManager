<?php

namespace App\Imports;

use App\Models\TestCase;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestCasesImport implements ToModel, WithHeadingRow
{
  /**
   * @param array $row
   * 
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    return new TestCase([
      'test_case_no'     => $row['test_case_no'],    
      'test_environment' => $row['test_environment'],
      'tester'           => $row['tester'],
      'date_of_input'    => $row['date'],             
      'test_title'       => $row['test_title'],
      'test_description' => $row['test_description'],
      'status'           => $row['status'],
      'priority'         => $row['priority'],
      'severity'         => $row['severity'],
      'screenshot'       => $row['screenshot'],
    ]);
  }
}
