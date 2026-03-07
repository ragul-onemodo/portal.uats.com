<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Application::updateOrCreate(
            [
                'code' => 'MODO2026',
            ],
            [
                'name' => 'ModoMines',
                'description' => 'Software for quarry and crusher',
                'webhook_url' => null,
                'is_active' => true,
            ]
        );
    }
}
