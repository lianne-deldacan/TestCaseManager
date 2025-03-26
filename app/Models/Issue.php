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
        'project_name',
        'environment',
        'test_case_id',
        'date_time_report',
        'status',
        'issue_title',
        'tester',
        'issue_description',
        'screenshot_url',
        'assigned_developer',
        'developer_notes',
    ];

    /**
     * Relationship with Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Human-readable formatted date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : 'N/A';
    }
}
