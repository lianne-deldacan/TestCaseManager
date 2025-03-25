<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'test_case_no',
        'test_title',
        'test_step',
        'category_id',
        'priority',
        'tester',
        'status',
        'date_of_input',
        'test_environment',
    ];

    /**
     * Define relationship with Project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id'); // Define relationship to Project
    }

    /**
     * Define relationship with Category model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class); // Define relationship to Category
    }
}
