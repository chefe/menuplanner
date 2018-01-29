<?php

use App\Item;
use App\Menuplan;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'unit' => $faker->word,
        'menuplan_id' => function () {
            return factory(Menuplan::class)->create()->id;
        },
    ];
});
