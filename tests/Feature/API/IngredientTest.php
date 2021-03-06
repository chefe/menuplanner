<?php

namespace Tests\Feature\API;

use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Meal;
use App\Models\Menuplan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->menuplan = Menuplan::factory()
            ->create(['user_id' => $this->user->id]);
        $this->item = Item::factory()
            ->create(['menuplan_id' => $this->menuplan->id]);
        $this->validIngredientData = [
            'quantity' => 12.001,
            'item_id' => $this->item->id,
        ];
    }

    /** @test */
    public function a_user_can_get_the_ingredients_of_a_meal()
    {
        $meal = Meal::factory()->create(['menuplan_id' => $this->menuplan->id]);
        $ingredients = Ingredient::factory()->count(2)->create(['meal_id' => $meal->id]);
        $otherIngredients = Ingredient::factory()->count(2)->create();

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
        $meal = Meal::factory()->create();
        Ingredient::factory()->create(['meal_id' => $meal->id]);

        $this->actingAs($this->user)
            ->get('/api/meal/'.$meal->id.'/ingredients')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_an_ingredient_for_a_meal()
    {
        $meal = Meal::factory()->create(['menuplan_id' => $this->menuplan->id]);

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
        $meal = Meal::factory()->create();

        $this->actingAs($this->user)
            ->post('/api/meal/'.$meal->id.'/ingredients', $this->validIngredientData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('ingredients', $this->validIngredientData);
    }

    /** @test */
    public function a_user_can_create_an_ingredient_only_if_the_referenced_item_is_in_one_of_his_menuplans()
    {
        $meal = Meal::factory()->create();
        $otherItem = Item::factory()->create();

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
        $meal = Meal::factory()->create(['menuplan_id' => $this->menuplan->id]);
        $ingredient = Ingredient::factory()->create([
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
        $ingredient = Ingredient::factory()->create();

        $this->actingAs($this->user)
            ->put('/api/ingredient/'.$ingredient->id, $this->validIngredientData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('ingredients', $this->validIngredientData);
    }

    /** @test */
    public function a_user_can_delete_an_ingredient()
    {
        $meal = Meal::factory()->create(['menuplan_id' => $this->menuplan->id]);
        $ingredient = Ingredient::factory()->create([
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
        $ingredient = Ingredient::factory()->create();

        $this->actingAs($this->user)
            ->delete('/api/ingredient/'.$ingredient->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('ingredients', $ingredient->toArray());
    }
}
