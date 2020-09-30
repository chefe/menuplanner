<?php

namespace Tests\Feature\API;

use App\Menuplan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MenuplanTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_get_his_menuplans()
    {
        $user = User::factory()->create();
        $ownMenuplans = Menuplan::factory()->count(4)
            ->create(['user_id' => $user->id]);
        Menuplan::factory()->count(10);

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
    public function a_user_can_get_his_own_and_shared_menuplans()
    {
        $user = User::factory()->create();
        $ownMenuplan = Menuplan::factory()->create(['user_id' => $user->id]);

        $anotherUser = User::factory()->create();
        $anotherMenuplan = Menuplan::factory()->create(['user_id' => $anotherUser->id]);
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
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);

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
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_a_menuplan()
    {
        $user = User::factory()->create();
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
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create([
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
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();
        $menuplan = Menuplan::factory()->create([
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

        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
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
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create([
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
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();
        $menuplan = Menuplan::factory()->create([
            'user_id' => $userOne->id,
        ]);

        $this->actingAs($userTwo)
            ->delete('/api/menuplan/'.$menuplan->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_download_a_menuplan()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(url('/menuplan/'.$menuplan->id.'/pdf'))
            ->assertStatus(200)
            ->assertHeader('content-disposition', 'attachment; filename="menuplan.pdf"');
    }

    /** @test */
    public function a_user_can_only_download_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();

        $this->actingAs($user)
            ->get(url('/menuplan/'.$menuplan->id.'/pdf'))
            ->assertStatus(403);
    }
}
