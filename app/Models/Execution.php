<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_case_id', 
        'project_id',
        'environment', 
        'tester_name',
        'status',
    ];

    /**
     * Define relationship with Project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Define relationship with TestCase model.
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }
}
