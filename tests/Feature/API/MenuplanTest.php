<?php

namespace Tests\Feature\API;

use App\User;
use App\Menuplan;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuplanTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_get_his_menuplans()
    {
        $user = factory(User::class)->create();
        $ownMenuplans = factory(Menuplan::class, 4)
            ->create(['user_id' => $user->id]);
        $otherMenuplans = factory(Menuplan::class, 10);

        $this->actingAs($user)
            ->get('/api/menuplan')
            ->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJson([
                [
                    'title' => $ownMenuplans[0]->title,
                    'start' => $ownMenuplans[0]->start->format('Y-m-d'),
                ], [
                    'title' => $ownMenuplans[1]->title,
                    'start' => $ownMenuplans[1]->start->format('Y-m-d'),
                ], [
                    'title' => $ownMenuplans[2]->title,
                    'start' => $ownMenuplans[2]->start->format('Y-m-d'),
                ], [
                    'title' => $ownMenuplans[3]->title,
                    'start' => $ownMenuplans[3]->start->format('Y-m-d'),
                ],
            ]);
    }

    /** @test */
    public function a_user_can_shared_and_own_menuplans()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $ownMenuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);

        $anotherUser = factory(User::class)->create();
        $anotherMenuplan = factory(Menuplan::class)->create(['user_id' => $anotherUser->id]);
        $anotherMenuplan->invitations()->create(['email' => $user->email, 'user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/api/menuplan')
            ->assertStatus(200)
            ->assertJson([
                ['id' => $ownMenuplan->id, 'is_shared' => false],
                ['id' => $anotherMenuplan->id, 'is_shared' => true],
            ]);
    }

    /** @test */
    public function a_user_can_get_data_from_a_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $menuplan->title,
                'start' => $menuplan->start->format('Y-m-d'),
                'end' => $menuplan->end->format('Y-m-d'),
                'people' => $menuplan->people,
            ]);
    }

    /** @test */
    public function a_user_can_only_get_data_from_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id)
            ->assertStatus(403);
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
            ->assertStatus(201)
            ->assertJson($validMenuplanData);

        $this->assertDatabaseHas('menuplans', [
            'title' => 'Week 01 - 2018',
            'start' => '2018-01-01 00:00:00',
            'end' => '2018-01-07 00:00:00',
            'people' => 4,
        ]);
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
    public function a_user_can_not_update_a_menuplan_from_another_user()
    {
        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create([
            'user_id' => $userOne->id,
        ]);

        $this->actingAs($userTwo)
            ->put('/api/menuplan/'.$menuplan->id, [
                'title' => 'Changed',
            ])->assertStatus(403);
    }

    /** @test */
    public function a_menuplan_with_end_date_before_start_date_can_not_be_saved()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $newMenuplanData = [
            'title' => 'Week 01 - 2018',
            'start' => '2018-01-07',
            'end' => '2018-01-01',
            'people' => 4,
        ];

        $this->actingAs($user)->put('/api/menuplan/'.$menuplan->id, $newMenuplanData);
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
