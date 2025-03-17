<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'start_at' => now(),
            'end_at' => now()->addMinutes(60),
            'organization' => 'TSMS',
            'room_link' => 'https://www.youtube.com/watch?v=SZ1OTOzX1TE&list=RDGMEMQ1dJ7wXfLlqCjwV0xfSNbAVMSZ1OTOzX1TE&start_radio=1',
            'workload' => 60
        ];
    }
}
