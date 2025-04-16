<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user',
        'title',
        'category',
        'type',
        'description',
        'number',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
