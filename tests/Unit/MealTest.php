<?php

namespace Tests\Unit;

use App\Meal;
use Tests\TestCase;

class MealTest extends TestCase
{
    /** @test */
    public function a_meal_has_a_duration()
    {
        $meal = new Meal();

        $meal->start = '10:00:00';
        $meal->end = '12:00:00';
        $this->assertEquals('10:00 - 12:00', $meal->duration);

        $meal->start = '15:39:10';
        $meal->end = '19:59:99';
        $this->assertEquals('15:39 - 19:59', $meal->duration);
    }
}
