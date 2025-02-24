<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

Class Project extends Model 
{
    use HasFactory;

      protected $fillable = [
        'id',
        'name',
        'description'
      ];

    public function testCases()
    {
      return $this->hasMany(TestCase::class, 'project_id');
    }
}