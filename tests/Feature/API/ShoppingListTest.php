<?php

namespace Tests\Feature\API;

use App\Models\Item;
use App\Models\Meal;
use App\Models\Menuplan;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_retrive_the_shopping_list_for_a_menuplan()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id, 'people' => 8]);
        $meals = Meal::factory()->count(2)->create(['menuplan_id' => $menuplan->id, 'people' => null, 'ingredients_for' => 4]);
        $items = Item::factory()->count(4)->create(['menuplan_id' => $menuplan->id]);

        $items[0]->update(['title' => 'A']);
        $items[1]->update(['title' => 'B']);
        $items[2]->update(['title' => 'C']);
        $items[2]->update(['title' => 'D']);

        $meals[0]->ingredients()->create(['quantity' => 1.25, 'item_id' => $items[0]->id]);
        $meals[0]->ingredients()->create(['quantity' => 200, 'item_id' => $items[1]->id]);
        $meals[0]->ingredients()->create(['quantity' => 2, 'item_id' => $items[2]->id]);
        $meals[0]->ingredients()->create(['quantity' => 400, 'item_id' => $items[1]->id]);

        $meals[1]->ingredients()->create(['quantity' => 2.25, 'item_id' => $items[0]->id]);
        $meals[1]->ingredients()->create(['quantity' => 1, 'item_id' => $items[2]->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/shopping-list')
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson([
                ['item_id' => $items[0]->id, 'quantity' => 7, 'meals' => [
                    ['id' => $meals[0]->id, 'quantity' => 2.5],
                    ['id' => $meals[1]->id, 'quantity' => 4.5],
                ]],
                ['item_id' => $items[1]->id, 'quantity' => 1200, 'meals' => [
                    ['id' => $meals[0]->id, 'quantity' => 400],
                    ['id' => $meals[0]->id, 'quantity' => 800],
                ]],
                ['item_id' => $items[2]->id, 'quantity' => 6, 'meals' => [
                    ['id' => $meals[0]->id, 'quantity' => 4],
                    ['id' => $meals[1]->id, 'quantity' => 2],
                ]],
            ]);
    }

    /** @test */
    public function a_user_can_not_retrive_the_shopping_list_for_a_menuplan_from_another_user()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/shopping-list')
            ->assertStatus(403);
    }

    /** @test */
    public function the_shopping_list_is_sorted_alphabetiically_by_the_item_title()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id, 'people' => 4]);
        $meal = Meal::factory()->create(['menuplan_id' => $menuplan->id, 'people' => null, 'ingredients_for' => 4]);
        $items = Item::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);

        $items[0]->update(['title' => 'C']);
        $items[1]->update(['title' => 'A']);
        $items[2]->update(['title' => 'B']);

        $meal->ingredients()->create(['quantity' => 1, 'item_id' => $items[2]->id]);
        $meal->ingredients()->create(['quantity' => 1, 'item_id' => $items[1]->id]);
        $meal->ingredients()->create(['quantity' => 1, 'item_id' => $items[0]->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/shopping-list')
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson([
                ['item_id' => $items[1]->id],
                ['item_id' => $items[2]->id],
                ['item_id' => $items[0]->id],
            ]);
    }

    /** @test */
    public function quantity_of_shopping_list_items_is_rounded_up_to_three_decimal_digits()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id, 'people' => 4]);
        $meal = Meal::factory()->create(['menuplan_id' => $menuplan->id, 'people' => null, 'ingredients_for' => 4]);
        $item = Item::factory()->create(['menuplan_id' => $menuplan->id]);
        $meal->ingredients()->create(['quantity' => 1.000001, 'item_id' => $item->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/shopping-list')
            ->assertStatus(200)
            ->assertJson([
                ['quantity' => 1.001],
            ]);
    }

    /** @test */
    public function a_user_can_retrive_the_shopping_list_for_a_purchase()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $menuplan = Menuplan::factory()->create([
            'user_id' => $user->id,
            'people' => 8,
            'start' => Carbon::parse('2020-03-20'),
            'end' => Carbon::parse('2020-03-22'),
        ]);

        $createMeal = function ($date) use ($menuplan) {
            return Meal::factory()->create([
                'menuplan_id' => $menuplan->id,
                'people' => null,
                'ingredients_for' => 4,
                'date' => Carbon::parse($date),
                'start' => Carbon::parse($date),
                'end' => Carbon::parse($date)->addHour(),
            ]);
        };

        $purchase = Purchase::factory()->create([
            'menuplan_id' => $menuplan->id,
            'time' => Carbon::parse('2020-03-21 10:00:00'),
        ]);

        $itemA = Item::factory()->create([
            'menuplan_id' => $menuplan->id,
            'title' => 'Item A',
        ]);

        $itemB = Item::factory()->create([
            'menuplan_id' => $menuplan->id,
            'title' => 'Item B',
        ]);

        $mealOne = $createMeal('2020-03-21 12:00:00');
        $mealOne->ingredients()->create(['quantity' => 1.25, 'item_id' => $itemA->id]);
        $mealOne->ingredients()->create(['quantity' => 200, 'item_id' => $itemB->id]);
        $mealOne->ingredients()->create(['quantity' => 15, 'item_id' => $itemA->id]);

        $mealTwo = $createMeal('2020-03-20 10:00:00');
        $mealTwo->ingredients()->create(['quantity' => 2.25, 'item_id' => $itemB->id]);

        $mealThree = $createMeal('2020-03-21 18:00:00');
        $mealThree->ingredients()->create(['quantity' => 1, 'item_id' => $itemA->id]);

        $this->actingAs($user)
            ->get('/api/purchase/'.$purchase->id.'/shopping-list')
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['item_id' => $itemA->id, 'quantity' => 34.5, 'meals' => [
                    ['id' => $mealOne->id, 'quantity' => 2.5],
                    ['id' => $mealOne->id, 'quantity' => 30],
                    ['id' => $mealThree->id, 'quantity' => 2],
                ]],
                ['item_id' => $itemB->id, 'quantity' => 400, 'meals' => [
                    ['id' => $mealOne->id, 'quantity' => 400],
                ]],
            ])->assertJsonMissing([
                ['item_id' => $itemB->id, 'meals' => [
                    ['id' => $mealTwo->id],
                ]],
            ]);
    }
}
