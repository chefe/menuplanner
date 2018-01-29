<?php

namespace Tests\Feature\API;

use App\Item;
use App\User;
use App\Menuplan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
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
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $items = factory(Item::class, 3)->create(['menuplan_id' => $menuplan->id]);
        $otherItems = factory(Item::class, 3)->create();

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
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();
        $items = factory(Item::class, 3)->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/items')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_an_item_for_a_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);

        $this->assertDatabaseMissing('items', $this->validItemData);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/items', $this->validItemData)
            ->assertStatus(200)
            ->assertJson($this->validItemData);

        $this->assertDatabaseHas('items', $this->validItemData);
    }

    /** @test */
    public function a_user_can_only_create_items_for_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();

        $this->assertDatabaseMissing('items', $this->validItemData);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/items', $this->validItemData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('items', $this->validItemData);
    }

    /** @test */
    public function a_user_can_update_an_item()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $item = factory(Item::class)->create(['menuplan_id' => $menuplan->id]);

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
        $user = factory(User::class)->create();
        $item = factory(Item::class)->create();

        $this->actingAs($user)
            ->put('/api/item/'.$item->id, $this->validItemData)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_an_item()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $item = factory(Item::class)->create(['menuplan_id' => $menuplan->id]);

        $this->assertDatabaseHas('items', $item->toArray());

        $this->actingAs($user)
            ->delete('/api/item/'.$item->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('items', $item->toArray());
    }

    /** @test */
    public function a_user_can_delete_only_items_of_his_menuplans()
    {
        $user = factory(User::class)->create();
        $item = factory(Item::class)->create();

        $this->actingAs($user)
            ->delete('/api/item/'.$item->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('items', $item->toArray());
    }
}