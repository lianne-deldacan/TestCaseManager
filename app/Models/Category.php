<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'service',
        'project_id',
    ];


    public function service(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => Arr::get(config('global.services'), $value, $value),
        );
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }

    public function getServiceKeyAttribute()
    {
        return $this->attributes['service'];
    }

    
}
