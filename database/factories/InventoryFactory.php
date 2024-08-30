<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,11),
            'item_id' => rand(1,500),
            'type' => rand(1,5),
            'collection_number' => rand(1,100),
            'can_trade' => rand(0,1),
        ];
    }
}
