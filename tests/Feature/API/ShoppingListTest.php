<?php

namespace Tests\Feature\API;

use App\User;
use App\Meal;
use App\Menuplan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShoppingListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function testExample()
    {
        //$this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $meals = factory(Meal::class, 2)->create(['menuplan_id' => $menuplan->id]);
        $items = factory(Meal::class, 4)->create(['menuplan_id' => $menuplan->id]);

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
                ['item_id' => $items[0]->id, 'quantity' => 3.5],
                ['item_id' => $items[1]->id, 'quantity' => 600],
                ['item_id' => $items[2]->id, 'quantity' => 3],
            ]);
    }
}
