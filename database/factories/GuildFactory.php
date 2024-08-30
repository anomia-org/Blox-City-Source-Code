<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guild>
 */
class GuildFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => rand(1,11),
            'name' => $this->faker->text(30),
            'desc' => $this->faker->text(120),
            'cash' => rand(0,150),
            'coins' => rand(0,2500),
            'thumbnail_url' => '1',
        ];
    }
}
