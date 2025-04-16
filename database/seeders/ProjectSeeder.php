<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            'Bell',
            'Invoker',
            'MyHR',
            'MyVet',
            'MyEcommerce',
        ];

        // Fix: Use query() to get users with "Manager" role
        $managers = User::where('role', 'Manager')->get();

        

        if ($managers->isEmpty()) {
            $this->command->warn('No managers found. Skipping project seeding.');
            return;
        }

        foreach ($projects as $projectName) {
            $manager = $managers->random();

            $manager->projects()->create([
                'service' => rand(0, 1),
                'name' => $projectName,
            ]);
        }
    }
}
