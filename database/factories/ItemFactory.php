<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(30),
            'desc' => $this->faker->text(120),
            'creator_id' => rand(1, 11),
            'updated_real' => Carbon::now()->subMinutes(rand(1,60)),
            'cash' => rand(0,1000),
            'coins' => rand(0,10000),
            'source' => 'avatar.png',
            'hash' => '1',
            'stock_limit' => '100',
            'special' => rand(0,1),
            'type' => rand(1,5),
        ];
    }
}
