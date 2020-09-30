<?php

namespace Tests\Feature\API;

use App\Item;
use App\Menuplan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validItemData = [
            'title' => 'milk',
            'unit' => 'litre',
        ];
    }

    /** @test */
    public function a_user_can_get_all_items_of_from_a_menuplan()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $items = Item::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);
        $otherItems = Item::factory()->count(3)->create();

        $items[0]->update(['title' => 'A']);
        $items[1]->update(['title' => 'B']);
        $items[2]->update(['title' => 'C']);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/items')
            ->assertStatus(200)
            ->assertJson([
                ['title' => $items[0]->title],
                ['title' => $items[1]->title],
                ['title' => $items[2]->title],
            ])->assertJsonMissing([
                ['title' => $otherItems[0]->title],
                ['title' => $otherItems[1]->title],
                ['title' => $otherItems[2]->title],
            ]);
    }

    /** @test */
    public function a_user_can_get_only_items_from_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();
        Item::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/items')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_an_item_for_a_menuplan()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseMissing('items', $this->validItemData);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/items', $this->validItemData)
            ->assertStatus(201)
            ->assertJson($this->validItemData);

        $this->assertDatabaseHas('items', $this->validItemData);
    }

    /** @test */
    public function a_user_can_only_create_items_for_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();

        $this->assertDatabaseMissing('items', $this->validItemData);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/items', $this->validItemData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('items', $this->validItemData);
    }

    /** @test */
    public function a_user_can_update_an_item()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create(['menuplan_id' => $menuplan->id]);

        $this->assertDatabaseMissing('items', $this->validItemData);

        $this->actingAs($user)
            ->put('/api/item/'.$item->id, $this->validItemData)
            ->assertStatus(200)
            ->assertJson($this->validItemData);

        $this->assertDatabaseHas('items', $this->validItemData);
    }

    /** @test */
    public function a_user_can_update_only_items_of_his_menuplans()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->put('/api/item/'.$item->id, $this->validItemData)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_an_item()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create(['menuplan_id' => $menuplan->id]);

        $this->assertDatabaseHas('items', $item->toArray());

        $this->actingAs($user)
            ->delete('/api/item/'.$item->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('items', $item->toArray());
    }

    /** @test */
    public function a_user_can_delete_only_items_of_his_menuplans()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->delete('/api/item/'.$item->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('items', $item->toArray());
    }

    /** @test */
    public function items_of_a_menuplan_are_sorted_by_title()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $items = Item::factory()->count(3)->create(['menuplan_id' => $menuplan->id]);

        $items[0]->update(['title' => 'C']);
        $items[1]->update(['title' => 'A']);
        $items[2]->update(['title' => 'B']);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/items')
            ->assertStatus(200)
            ->assertJson([
                ['title' => $items[1]->title],
                ['title' => $items[2]->title],
                ['title' => $items[0]->title],
            ]);
    }
}
