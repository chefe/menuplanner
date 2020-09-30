<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->randomFloat(4, 0, 5000),
            'item_id' => function () {
                return Item::factory()->create()->id;
            },
            'meal_id' => function () {
                return Meal::factory()->create()->id;
            },
        ];
    }
}
