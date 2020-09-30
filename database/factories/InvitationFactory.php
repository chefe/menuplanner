<?php

namespace Database\Factories;

use App\Invitation;
use App\Menuplan;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'menuplan_id' => function () {
                return Menuplan::factory()->create()->id;
            },
            'user_id' => null,
        ];
    }
}
