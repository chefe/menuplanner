<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Menuplan;
use App\User;
use Carbon\Carbon;

class MenuplanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Menuplan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeThisYear();

        return [
            'title' => $this->faker->sentence(4),
            'start' => $start,
            'end' => Carbon::instance($start)->addDays(
                $this->faker->numberBetween(0, 31)
            )->format('Y-m-d'),
            'people' => $this->faker->numberBetween(2, 50),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
