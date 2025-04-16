<?php

namespace App\Exports;

use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IssuesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Issue::with('execution.testCase') 
            ->get(['id', 'project_id', 'status', 'issue_title as title', 'issue_description as description', 'screenshot_url', 'created_at'])
            ->map(function ($issue) {
            
                $testerName = $issue->execution && $issue->execution->testCase ? $issue->execution->testCase->tester : 'N/A';

                return collect([
                    'Issue No.' => $issue->id,
                    'Project ID' => $issue->project_id,
                    'Tester' => $testerName, 
                    'Status' => $issue->status,
                    'Title' => $issue->title,
                    'Description' => $issue->description,
                    'Screenshot URL' => $issue->screenshot_url,
                    'Date Reported' => $issue->created_at->format('Y-m-d H:i:s'),
                ]);
            });
    }

    public function headings(): array
    {
        return ['Issue No.', 'Project ID', 'Tester', 'Status', 'Title', 'Description', 'Screenshot URL', 'Date Reported'];
    }
}
