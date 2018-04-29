<?php

use App\Menuplan;
use Faker\Generator as Faker;

$factory->define(App\Purchase::class, function (Faker $faker) {
    return [
        'notes' => $faker->paragraph(),
        'menuplan_id' => function () {
            return factory(Menuplan::class)->create()->id;
        },
        'time' => function (array $purchase) use ($faker) {
            $plan = Menuplan::find($purchase['menuplan_id']);

            return $faker->dateTimeBetween($plan->start, $plan->end->endOfDay());
        },
    ];
});
