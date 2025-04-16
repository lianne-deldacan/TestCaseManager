<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\TestCase;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('en_PH');

        // Get all testers
        $testers = User::where('role', 'Tester')->get();


        if ($testers->isEmpty()) {
            $this->command->warn('❌ No testers found. Skipping test case seeding.');
            return;
        }

        // Get all categories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->warn('❌ No categories found. Skipping test case seeding.');
            return;
        }

        // Get all projects
        $projects = Project::all();
        if ($projects->isEmpty()) {
            $this->command->warn('❌ No projects found. Skipping test case seeding.');
            return;
        }

        $statuses = TestCase::STATUSES;
        $priorities = TestCase::PRIORITIES;
        $environments = ['SIT', 'UAT'];

        for ($i = 1; $i <= rand(20, 30); $i++) {
            $project = $projects->random();
            $category = $categories->random();
            $tester = $testers->random();

            $project->test_cases()->create([
                'category_id' => $category->id,
                'tester_id' => $tester->id,
                'test_case_no' => $faker->numerify('######'),
                'test_title' => implode(' ', $faker->words(rand(1, 3))),
                'test_step' => implode(' ', $faker->words(rand(1, 3))),
                'date_of_input' => $faker->date,
                'status' => array_rand($statuses),
                'priority' => array_rand($priorities),
                'test_environment' => $faker->randomElement($environments),
            ]);
        }

        $this->command->info('✅ Test cases seeded successfully.');
    }
}
