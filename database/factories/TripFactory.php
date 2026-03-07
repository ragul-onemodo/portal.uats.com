<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = \App\Models\Trip::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 week', 'now');
        $end = (clone $start)->modify('+'.rand(1,5).' hours'); // always set end_time

        return [
            'vehicle_name' => $this->faker->bothify('TN## ?? ####'),
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'status' => $this->faker->randomElement(['Pending','Ongoing','Completed']),
        ];
    }
}
