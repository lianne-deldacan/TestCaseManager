<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
