<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_number',
        'project_id',
        'execution_id',
        'project_name',
        'environment',
        'date_time_report',
        'status',
        'issue_title',
        'tester',
        'issue_description',
        'screenshot_url',
        'assigned_developer'
    ];

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Relationship with Execution
    public function execution()
    {
        return $this->belongsTo(Execution::class, 'execution_id');
    }
}
