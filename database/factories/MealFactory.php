<?php

namespace Database\Factories;

use App\Models\Meal;
use App\Models\Menuplan;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $times = [
            $this->faker->time('H:i'),
            $this->faker->time('H:i'),
        ];

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'start' => min($times),
            'end' => max($times),
            'people' => $this->faker->randomElement([
                null,
                $this->faker->numberBetween(2, 50),
            ]),
            'menuplan_id' => function () {
                return Menuplan::factory()->create()->id;
            },
            'date' => function (array $meal) {
                $plan = Menuplan::find($meal['menuplan_id']);

                if ($plan->start->diff($plan->end)->days == 0) {
                    return $plan->start;
                }

                return $this->faker->dateTimeBetween($plan->start, $plan->end);
            },
        ];
    }
}
