<?php

use App\Ingredient;
use App\Item;
use App\Meal;
use Faker\Generator as Faker;

$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'quantity' => $faker->randomFloat(4, 0, 5000),
        'item_id' => function () {
            return factory(Item::class)->create()->id;
        },
        'meal_id' => function () {
            return factory(Meal::class)->create()->id;
        },
    ];
});
