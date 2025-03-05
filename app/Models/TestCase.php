<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',         
        'project_name',       // Optional, but if stored, should be fillable
        'test_case_no',
        'test_title',
        'test_step',
        'category',
        'priority',
        'tester',
        'status',
        'date_of_input',
    ];

    /**
     * Define relationship with Project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
