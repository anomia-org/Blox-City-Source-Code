<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
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
            'title' => $this->faker->text('45'),
            'body' => $this->faker->text('1500'),
            'last_reply' => Carbon::now(),
            'views' => rand(0, 50),
            'scrubbed' => '0',
            'pinned' => rand(0, 1),
            'locked' => rand(0, 1),
            'stuck' => '0',
        ];
    }
}
