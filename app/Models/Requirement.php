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
        'requirement_title',
        'category_id',
        'requirement_type',
        'requirement_number',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot() {
        parent::boot();
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}
}
