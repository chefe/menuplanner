<?php

use App\User;
use App\Menuplan;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Menuplan::class, function (Faker $faker) {
    $start = $faker->dateTimeThisYear();

    return [
        'title' => $faker->sentence(4),
        'start' => $start,
        'end' => Carbon::instance($start)->addDays(
            $faker->numberBetween(0, 31)
        )->format('Y-m-d'),
        'people' => $faker->numberBetween(2, 50),
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
