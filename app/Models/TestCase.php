<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'category_id',
        'tester_id',
        'test_case_no',
        'test_title',
        'test_step',
        'priority',
        // 'tester',
        'status',
        'date_of_input',
        'test_environment',
    ];

    protected $casts = [
        'date_of_input' => 'date',
    ];

    const STATUSES = [
        'Pending',
        'Ongoing',
        'Failed',
        'Not Run',
        'Completed',
    ];
    
    

    const PRIORITIES = [
        'Low',
        'Medium',
        'High'
    ];

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::STATUSES[(int) $value] ?? 'Unknown',
            set: fn($value) => array_search($value, self::STATUSES), // Optional if you're also saving status using label
        );
    }
    
    
    public function priority(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => self::PRIORITIES[$value],
        );
    }
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

    public function tester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tester_id');
    }

    public function issue()
    {
        return $this->hasOne(Issue::class, 'test_case_id');
    }

    // public function issues(): HasMany
    // {
    //     return $this->hasMany(Issue::class, 'test_case_id');
    // }
}
