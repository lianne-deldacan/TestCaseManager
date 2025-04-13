<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'MyEcommerce'
        ];

        foreach($projects as $project)
        {
            $managers = get_users_with_role('Manager');
            $manager = $managers->random();
            $manager->projects()->create([
                'service' => rand(0,1),
                'name' => $project,
            ]);
        }
    }
}
