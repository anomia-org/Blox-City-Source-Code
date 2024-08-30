<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //type 2 is game comments
            'user_id' => rand(1,11),
            'text' => $this->faker->text(120),
            'target_id' => rand(1,500),
        ];
    }
}
