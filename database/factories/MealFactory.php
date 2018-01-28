<?php

use App\Meal;
use App\Menuplan;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    $times = [
        $faker->time(),
        $faker->time()
    ];

    return [
        'title' => $faker->sentence(4),
        'description' => $faker->paragraph(),
        'start' => min($times),
        'end' => max($times),
        'people' => $faker->randomElement([
            null,
            $faker->numberBetween(2, 50)
        ]),
        'menuplan_id' => function () {
            return factory(Menuplan::class)->create()->id;
        },
        'date' => function (array $meal) use ($faker) {
            $plan = Menuplan::find($meal['menuplan_id']);
            return $faker->dateTimeBetween($plan->start, $plan->end);
        }
    ];
});
