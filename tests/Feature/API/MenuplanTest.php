<?php

namespace Tests\Feature\API;

use App\User;
use App\Menuplan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuplanTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_get_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplans = factory(Menuplan::class, 4)
            ->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/api/menuplan')
            ->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJson([
                ['title' => $menuplans[0]->title],
                ['title' => $menuplans[1]->title],
                ['title' => $menuplans[2]->title],
                ['title' => $menuplans[3]->title],
            ]);
    }

    /** @test */
    public function a_user_can_create_a_menuplan()
    {
        $user = factory(User::class)->create();
        $validMenuplanData = [
            'title' => 'Week 01 - 2018',
            'start' => '2018-01-01',
            'end' => '2018-01-07',
            'people' => 4,
        ];

        $this->actingAs($user)
            ->post('/api/menuplan', $validMenuplanData)
            ->assertStatus(200)
            ->assertJson($validMenuplanData);

        $this->assertDatabaseHas('menuplans', $validMenuplanData);
    }

    /** @test */
    public function a_user_can_update_a_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create([
            'user_id' => $user->id,
            'title' => 'old title',
            'start' => '2011-11-11',
            'end' => '2011-11-11',
            'people' => 1,
        ]);
        $newMenuplanData = [
            'title' => 'Week 01 - 2018',
            'start' => '2018-01-01',
            'end' => '2018-01-07',
            'people' => 4,
        ];

        $this->actingAs($user)
            ->put('/api/menuplan/'.$menuplan->id, $newMenuplanData)
            ->assertStatus(200)
            ->assertJson($newMenuplanData);
    }

    /** @test */
    public function a_user_can_delete_a_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('menuplans', ['id' => $menuplan->id]);

        $this->actingAs($user)
            ->delete('/api/menuplan/'.$menuplan->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('menuplans', ['id' => $menuplan->id]);
    }

    /** @test */
    public function a_user_can_not_delete_a_menuplan_from_another_user()
    {
        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create([
            'user_id' => $userOne->id,
        ]);

        $this->actingAs($userTwo)
            ->delete('/api/menuplan/'.$menuplan->id)
            ->assertStatus(403);
    }
}
