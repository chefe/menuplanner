<?php

namespace Tests\Feature\API;

use App\Models\Meal;
use App\Models\Menuplan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MealTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validMealData = [
            'title' => 'First Meal',
            'description' => 'And here is the description!',
            'date' => '2018-01-01',
            'start' => '12:00',
            'end' => '13:00',
            'people' => 8,
            'ingredients_for' => 4,
        ];
    }

    private function getValidMealData(Menuplan $menuplan)
    {
        return array_merge($this->validMealData, [
            'date' => $menuplan->start->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function a_user_can_get_the_meals_of_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $ownMeals = Meal::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);
        $otherMeals = Meal::factory()->count(2)->create();

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
    public function a_user_can_get_the_meals_of_a_shared_menuplans()
    {
        $user = User::factory()->create();

        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $anotherUser->id]);
        $menuplan->invitations()->create(['email' => $user->email, 'user_id' => $user->id]);
        $meals = Meal::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/meals')
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson([
                ['title' => $meals[0]->title],
                ['title' => $meals[1]->title],
                ['title' => $meals[2]->title],
            ]);
    }

    /** @test */
    public function a_user_can_only_get_the_meals_of_his_menuplans()
    {
        $mainUser = User::factory()->create();
        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $mainUser->id]);
        Meal::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($anotherUser)
            ->get('/api/menuplan/'.$menuplan->id.'/meals')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_get_data_from_a_meal()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $meal = Meal::factory()->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($user)
            ->get('/api/meal/'.$meal->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $meal->title,
                'description' => $meal->description,
                'date' => $meal->date->format('Y-m-d'),
                'start' => $meal->start,
                'end' => $meal->end,
                'people' => $meal->people,
            ]);
    }

    /** @test */
    public function a_user_can_only_get_data_from_his_meals()
    {
        $user = User::factory()->create();
        $meal = Meal::factory()->create();

        $this->actingAs($user)
            ->get('/api/meal/'.$meal->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_a_meal()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);

        $validMealData = $this->getValidMealData($menuplan);
        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/meals', $validMealData)
            ->assertStatus(201)
            ->assertJson($validMealData);

        $validMealData['date'] .= ' 00:00:00';
        $this->assertDatabaseHas('meals', $validMealData);
    }

    /** @test */
    public function a_user_can_only_add_a_meals_to_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/meals', $this->validMealData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('meals', $this->validMealData);
    }

    /** @test */
    public function a_user_can_update_a_meal()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $meal = Meal::factory()->create(['menuplan_id' => $menuplan->id]);
        $validMealData = $this->getValidMealData($menuplan);

        $this->assertDatabaseMissing('meals', $validMealData);

        $this->actingAs($user)
            ->put('/api/meal/'.$meal->id, $validMealData)
            ->assertStatus(200)
            ->assertJson($validMealData);

        $validMealData['date'] .= ' 00:00:00';
        $this->assertDatabaseHas('meals', $validMealData);
    }

    /** @test */
    public function a_user_can_update_only_his_meals()
    {
        $user = User::factory()->create();
        $meal = Meal::factory()->create();

        $this->actingAs($user)
            ->put('/api/meal/'.$meal->id, $this->validMealData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('meals', $this->validMealData);
    }

    /** @test */
    public function date_of_a_meal_has_to_be_in_menuplan_period()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create([
            'user_id' => $user->id,
            'start' => '2018-01-01',
            'end' => '2018-01-07',
        ]);
        $meal = Meal::factory()->create(['menuplan_id' => $menuplan->id]);

        $validMealData = $this->validMealData;
        $validMealData['date'] = '2018-02-03';

        $this->actingAs($user)
            ->put('/api/meal/'.$meal->id, $validMealData);
    }

    /** @test */
    public function a_user_can_delete_a_meal()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $meal = Meal::factory()->create([
            'menuplan_id' => $menuplan->id,
            'title' => 'Meal Number One',
        ]);

        $this->assertDatabaseHas('meals', [
            'title' => 'Meal Number One',
        ]);

        $this->actingAs($user)
            ->delete('/api/meal/'.$meal->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('meals', [
            'title' => 'Meal Number One',
        ]);
    }

    /** @test */
    public function a_user_can_delete_only_his_meals()
    {
        $user = User::factory()->create();
        $meal = Meal::factory()->create([
            'title' => 'Meal Number Two',
        ]);

        $this->actingAs($user)
            ->delete('/api/meal/'.$meal->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('meals', [
            'title' => 'Meal Number Two',
        ]);
    }
}
