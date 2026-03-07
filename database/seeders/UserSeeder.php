<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@onemodo.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Success@123'),
                'status' => true,
            ]
        );

        // Assign role
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        /**
         * IMPORTANT:
         * Super Admin does NOT belong to any entity
         * So we intentionally DO NOT insert into entity_users
         */
    }
}
