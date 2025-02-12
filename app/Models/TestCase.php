<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_case_no',
        'test_environment',
        'tester',
        'date_of_input',
        'test_title',
        'test_description',
        'status',
        'screenshot',
        'priority',
        'severity'
    ];
}

