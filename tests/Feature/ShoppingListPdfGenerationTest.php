<?php

namespace Tests\Feature;

use App\Ingredient;
use App\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListPdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_download_the_shoppinglist_of_a_menuplan()
    {
        $ingredient = Ingredient::factory()->create();
        $menuplan = $ingredient->meal->menuplan;

        $response = $this
            ->actingAs($menuplan->owner)
            ->get('/menuplan/'.$menuplan->id.'/shopping-list/pdf');

        $response->assertStatus(200);
    }

    public function a_user_can_download_the_shoppinglist_of_a_purchase()
    {
        $ingredient = Ingredient::factory()->create();
        $menuplan = $ingredient->meal->menuplan;
        $purchase = Purchase::factory()->create([
            'time' => $menuplan->start,
            'menuplan_id' => $menuplan->id,
        ]);

        $response = $this
            ->actingAs($menuplan->owner)
            ->get('/purchase/'.$purchase->id.'/shopping-list/pdf');

        $response->assertStatus(200);
    }
}
