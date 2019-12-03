<?php

namespace Tests\Feature\API;

use App\Ingredient;
use App\Item;
use App\Meal;
use App\Menuplan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->menuplan = factory(Menuplan::class)
            ->create(['user_id' => $this->user->id]);
        $this->item = factory(Item::class)
            ->create(['menuplan_id' => $this->menuplan->id]);
        $this->validIngredientData = [
            'quantity' => 12.001,
            'item_id' => $this->item->id,
        ];
    }

    /** @test */
    public function a_user_can_get_the_ingredients_of_a_meal()
    {
        $meal = factory(Meal::class)->create(['menuplan_id' => $this->menuplan->id]);
        $ingredients = factory(Ingredient::class, 2)->create(['meal_id' => $meal->id]);
        $otherIngredients = factory(Ingredient::class, 2)->create();

        $this->actingAs($this->user)
            ->get('/api/meal/'.$meal->id.'/ingredients')
            ->assertStatus(200)
            ->assertJson([
                ['quantity' => $ingredients[0]->quantity],
                ['quantity' => $ingredients[1]->quantity],
            ])->assertJsonMissing([
                ['quantity' => $otherIngredients[0]->quantity],
                ['quantity' => $otherIngredients[1]->quantity],
            ]);
    }

    /** @test */
    public function a_user_can_only_get_the_ingredients_of_a_meal_from_his_menuplans()
    {
        $meal = factory(Meal::class)->create();
        factory(Ingredient::class)->create(['meal_id' => $meal->id]);

        $this->actingAs($this->user)
            ->get('/api/meal/'.$meal->id.'/ingredients')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_an_ingredient_for_a_meal()
    {
        $meal = factory(Meal::class)->create(['menuplan_id' => $this->menuplan->id]);

        $this->assertDatabaseMissing('ingredients', $this->validIngredientData);

        $this->actingAs($this->user)
            ->post('/api/meal/'.$meal->id.'/ingredients', $this->validIngredientData)
            ->assertStatus(201)
            ->assertJson($this->validIngredientData);

        $this->assertDatabaseHas('ingredients', $this->validIngredientData);
    }

    /** @test */
    public function a_user_can_create_ingredients_only_for_a_meal_of_his_menuplans()
    {
        $meal = factory(Meal::class)->create();

        $this->actingAs($this->user)
            ->post('/api/meal/'.$meal->id.'/ingredients', $this->validIngredientData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('ingredients', $this->validIngredientData);
    }

    /** @test */
    public function a_user_can_create_an_ingredient_only_if_the_referenced_item_is_in_one_of_his_menuplans()
    {
        $meal = factory(Meal::class)->create();
        $otherItem = factory(Item::class)->create();

        $ingredientData = $this->validIngredientData;
        $ingredientData['item_id'] = $otherItem->id;

        $this->actingAs($this->user)
            ->post('/api/meal/'.$meal->id.'/ingredients', $ingredientData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('ingredients', $ingredientData);
    }

    /** @test */
    public function a_user_can_update_an_ingredient()
    {
        $meal = factory(Meal::class)->create(['menuplan_id' => $this->menuplan->id]);
        $ingredient = factory(Ingredient::class)->create([
            'meal_id' => $meal->id,
            'item_id' => $this->item->id,
        ]);

        $this->assertDatabaseMissing('ingredients', $this->validIngredientData);

        $this->actingAs($this->user)
            ->put('/api/ingredient/'.$ingredient->id, $this->validIngredientData)
            ->assertStatus(200)
            ->assertJson($this->validIngredientData);

        $this->assertDatabaseHas('ingredients', $this->validIngredientData);
    }

    /** @test */
    public function a_user_can_update_only_the_ingredients_he_has_acces_to()
    {
        $ingredient = factory(Ingredient::class)->create();

        $this->actingAs($this->user)
            ->put('/api/ingredient/'.$ingredient->id, $this->validIngredientData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('ingredients', $this->validIngredientData);
    }

    /** @test */
    public function a_user_can_delete_an_ingredient()
    {
        $meal = factory(Meal::class)->create(['menuplan_id' => $this->menuplan->id]);
        $ingredient = factory(Ingredient::class)->create([
            'meal_id' => $meal->id,
            'item_id' => $this->item->id,
        ]);

        $this->assertDatabaseHas('ingredients', $ingredient->toArray());

        $this->actingAs($this->user)
            ->delete('/api/ingredient/'.$ingredient->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('ingredients', $ingredient->toArray());
    }

    /** @test */
    public function a_user_can_only_delete_ingredients_he_has_access_to()
    {
        $ingredient = factory(Ingredient::class)->create();

        $this->actingAs($this->user)
            ->delete('/api/ingredient/'.$ingredient->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('ingredients', $ingredient->toArray());
    }
}
