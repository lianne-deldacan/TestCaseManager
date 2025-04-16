<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('en_PH');

        for ($i = 1; $i <= rand(5, 10); $i++) {
            Category::create([
                'name' => implode(' ', $faker->words(rand(1, 3))),
                'description' => implode(' ', $faker->sentences(rand(1, 3))),
                'service' => rand(0, 1),
            ]);
        }
    }
}
