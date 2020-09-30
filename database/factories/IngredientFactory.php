<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Ingredient;
use App\Item;
use App\Meal;

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
