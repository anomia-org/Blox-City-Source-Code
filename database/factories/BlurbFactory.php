<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blurb>
 */
class BlurbFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_id' => rand(1,11),
            'author_type' => '1',
            'text' => $this->faker->text('60'),
            'scrubbed' => '0',
        ];
    }
}
