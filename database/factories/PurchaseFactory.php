<?php

namespace Database\Factories;

use App\Models\Menuplan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Purchase::class;

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
            'time' => function (array $purchase) {
                $plan = Menuplan::find($purchase['menuplan_id']);

                return $this->faker->dateTimeBetween($plan->start, $plan->end->endOfDay());
            },
        ];
    }
}
