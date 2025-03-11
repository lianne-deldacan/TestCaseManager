<?php

namespace App\Exports;

use App\Models\Requirement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RequirementsExport implements FromCollection, WithHeadings
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function collection()
    {
        return Requirement::where('project_id', $this->project_id)
            ->with('project', 'category')
            ->get()
            ->map(function ($requirement) {
                return [
                    'Project Name' => $requirement->project->name ?? 'N/A',
                    'User' => $requirement->user,
                    'Requirement No.' => $requirement->requirement_number,
                    'Requirement Title' => $requirement->requirement_title,
                    'Category' => $requirement->category->name ?? 'N/A',
                    'Requirement Type' => $requirement->requirement_type,
                ];
            });
    }

    public function headings(): array
    {
        return ['Project Name', 'User', 'Requirement No.', 'Requirement Title', 'Category', 'Requirement Type'];
    }
}
