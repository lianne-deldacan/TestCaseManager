<?php

namespace App\Imports;

use App\Models\TestCase;
use App\Models\Project;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class TestCasesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Find the project by name and service (do not create if not found)
        $project = Project::where('name', $row['project_name'])
            ->where('service', $row['service'])
            ->first();

        // Find category
        $category = Category::where('name', $row['category'])->first();

        // If either the project or category is missing, skip this row
        if (!$project || !$category) {
            return null;
        }

        return new TestCase([
            'project_id' => $project->id,
            'category_id' => $category->id,
            'test_case_no' => $row['test_case_no'],
            'test_title' => $row['test_title'],
            'test_step' => $row['test_step'],
            'tester' => $row['tester'],
            'date_of_input' => isset($row['date']) ? Carbon::parse($row['date'])->format('Y-m-d') : null,
            'priority' => $row['priority'],
            'status' => $row['status'],
        ]);
    }
}
