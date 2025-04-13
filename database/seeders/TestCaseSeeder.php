<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\TestCase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'project_id',
        // 'test_case_no',
        // 'test_title',
        // 'test_step',
        // 'category_id',
        // 'tester',
        // 'date_of_input',
        // 'status',
        // 'priority',
        // 'test_environment', // Ensure this is included
        $faker = fake('en_PH');
        for ($i = 1; $i <= rand(20, 30); $i++) {
            $project = Project::inRandomOrder()->first();
            $category = Category::inRandomOrder()->first();
            $tester = get_users_with_role('Tester')->random();
            $statuses = TestCase::STATUSES;
            $priorities = TestCase::PRIORITIES;
            $environments = [
                'SIT',
                'UAT'
            ];

            $project->test_cases()->create([
                'category_id' => $category->id,
                'tester_id' => $tester->id,
                'test_case_no' => $faker->numerify('######'),
                'test_title' => implode(' ', $faker->words(rand(1, 3))),
                'test_step' => implode(' ', $faker->words(rand(1, 3))),
                'date_of_input' => $faker->date,
                'status' => array_search($statuses[rand(0, count($statuses) - 1)], $statuses),
                'priority' => array_search($priorities[rand(0, count($priorities) - 1)], $priorities),
                'test_environment' => $environments[rand(0,1)], // Ensure this is included
            ]);
        }
    }
}
