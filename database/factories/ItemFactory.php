<?php

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'unit' => $faker->word,
    ];
});
