<?php

use App\Menuplan;
use App\Invitation;
use Faker\Generator as Faker;

$factory->define(Invitation::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'menuplan_id' => function () {
            return factory(Menuplan::class)->create()->id;
        },
        'user_id' => null,
    ];
});
