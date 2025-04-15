<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fake = fake('en_PH');

        $admin = User::create([
            'name' => 'Lyn Ola',
            'email' => 'lyn@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $admin->assignRole('Admin');

        $tester = User::create([
            'name' => "$fake->name (Tester)",
            'email' => 'tester@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $tester->assignRole('Tester');

        $tester = User::create([
            'name' => "$fake->name (Tester)",
            'email' => 'tester2@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $tester->assignRole('Tester');

        $manager = User::create([
            'name' => "$fake->name (Manager)",
            'email' => 'manager@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $manager->assignRole('Manager');

        $manager = User::create([
            'name' => "$fake->name (Manager)",
            'email' => 'manager2@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $manager->assignRole('Manager');

        $dev = User::create([
            'name' => "$fake->name (Developer)",
            'email' => 'developer@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $dev->assignRole('Developer');

        $dev = User::create([
            'name' => "$fake->name (Developer)",
            'email' => 'developer2@chimesconsulting.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Demo123!?'),
            'status' => 1,
        ]);
        $dev->assignRole('Developer');

        //for testing pirposes
        $lianne = User::create([
            'name' => 'Lianne',
            'email' => 'lianne@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('liannedeldacan'),
            'status' => 1,
        ]);
        $lianne->assignRole('Developer');
    }
}
