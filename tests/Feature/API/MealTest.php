<?php

namespace Tests\Feature\API;

use App\Meal;
use App\User;
use App\Menuplan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use phpDocumentor\Reflection\DocBlock\Description;

class MealTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->validMealData = [
            'title' => 'First Meal',
            'description' => 'And here is the description!',
            'date' => '2018-01-01',
            'start' => '12:00:00',
            'end' => '13:00:00',
            'people' => 8
        ];
    }

    /** @test */
    public function a_user_can_get_the_meals_of_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $ownMeals = factory(Meal::class, 3)->create(['menuplan_id' => $menuplan->id]);
        $otherMeals = factory(Meal::class, 2)->create();

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/meals')
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson([
                ['title' => $ownMeals[0]->title],
                ['title' => $ownMeals[1]->title],
                ['title' => $ownMeals[2]->title],
            ])->assertJsonMissing([
                ['title' => $otherMeals[0]->title],
                ['title' => $otherMeals[1]->title],
            ]);
    }

    /** @test */
    public function a_user_can_only_get_the_meals_of_his_menuplans()
    {
        $mainUser = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $mainUser->id]);
        $meals = factory(Meal::class, 3)->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($anotherUser)
            ->get('/api/menuplan/'.$menuplan->id.'/meals')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_a_meal()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/meals', $this->validMealData)
            ->assertStatus(200)
            ->assertJson($this->validMealData);

        $this->assertDatabaseHas('meals', $this->validMealData);
    }

    /** @test */
    public function a_user_can_only_add_a_meals_to_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/meals', $this->validMealData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('meals', $this->validMealData);
    }

    /** @test */
    public function a_user_can_update_a_meal()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $meal = factory(Meal::class)->create(['menuplan_id' => $menuplan->id]);

        $this->assertDatabaseMissing('meals', $this->validMealData);

        $this->actingAs($user)
            ->put('/api/meal/'.$meal->id, $this->validMealData)
            ->assertStatus(200)
            ->assertJson($this->validMealData);

        $this->assertDatabaseHas('meals', $this->validMealData);
    }

    /** @test */
    public function a_user_can_update_only_his_meals()
    {
        $user = factory(User::class)->create();
        $meal = factory(Meal::class)->create();

        $this->actingAs($user)
            ->put('/api/meal/'.$meal->id, $this->validMealData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('meals', $this->validMealData);
    }

    /** @test */
    public function a_user_can_delete_a_meal()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $meal = factory(Meal::class)->create([
            'menuplan_id' => $menuplan->id,
            'title' => 'Meal Number One'
        ]);

        $this->assertDatabaseHas('meals', [
            'title' => 'Meal Number One'
        ]);

        $this->actingAs($user)
            ->delete('/api/meal/'.$meal->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('meals', [
            'title' => 'Meal Number One'
        ]);
    }

    /** @test */
    public function a_user_can_delete_only_his_meals()
    {
        $user = factory(User::class)->create();
        $meal = factory(Meal::class)->create([
            'title' => 'Meal Number Two'
        ]);

        $this->actingAs($user)
            ->delete('/api/meal/'.$meal->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('meals', [
            'title' => 'Meal Number Two'
        ]);
    }
}
