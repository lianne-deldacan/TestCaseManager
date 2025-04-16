<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'service'
    ];

    public function service(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => config('global.services')[$value],
        );
    }

    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }
}
