<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friendship>
 */
class FriendshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_type' => 'App\Models\User',
            'sender_id' => rand(1,11),
            'recipient_type' => 'App\Models\User',
            'recipient_id' => rand(1,11),
            'status' => rand(0,1),
        ];
    }
}
