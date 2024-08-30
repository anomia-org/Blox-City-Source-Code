<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, 11),
            'topic_id' => rand(1, 5),
            'thread_id' => rand(1, 15),
            'body' => $this->faker->text('1500'),
            'scrubbed' => '0',
        ];
    }
}
