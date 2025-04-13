<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
  use HasFactory;

  protected $fillable = [
    'manager_id',
    'service',
    'name',
    'manager'
  ];

  protected $casts = [
    'created_at',
    'updated_at'
  ];

  public function service(): Attribute
  {
    return Attribute::make(
      get: fn(string $value) => config('global.services')[$value],
    );
  }

  public function testCases()
  {
    return $this->hasMany(TestCase::class, 'project_id');
  }

  public function test_cases(): HasMany
  {
    return $this->hasMany(TestCase::class, 'project_id');
  }

  public function manager(): BelongsTo
  {
    return $this->belongsTo(User::class, 'manager_id');
  }
}
