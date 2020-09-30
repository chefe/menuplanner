<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Menuplan;

class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'notes' => $this->faker->paragraph(),
            'menuplan_id' => function () {
                return Menuplan::factory()->create()->id;
            },
            'time' => function (array $purchase) use ($faker) {
                $plan = Menuplan::find($purchase['menuplan_id']);

                return $this->faker->dateTimeBetween($plan->start, $plan->end->endOfDay());
            },
        ];
    }
}
