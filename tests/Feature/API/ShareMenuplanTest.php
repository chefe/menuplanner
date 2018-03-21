<?php

namespace Tests\Feature\API;

use App\User;
use App\Menuplan;
use App\Invitation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShareMenuplanTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_get_the_invitations_for_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $menuplan->invitations()->create(['email' => 'john@example.com']);
        $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/invitation')
            ->assertStatus(200)
            ->assertJson([
                ['email' => 'john@example.com'],
                ['email' => 'jane@example.com'],
            ]);
    }

    /** @test */
    public function a_user_can_not_get_the_invitations_for_another_users_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();
        $menuplan->invitations()->create(['email' => 'john@example.com']);
        $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/invitation')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_invite_someone_new_to_his_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $menuplan->invitations()->create(['email' => 'john@example.com']);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/invitation', [
                'email' => 'jane@example.com',
            ])->assertStatus(201);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'jane@example.com',
        ]);
    }

    /** @test */
    public function a_user_can_invite_someone_again_to_his_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $menuplan->invitations()->create(['email' => 'john@example.com']);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/invitation', [
                'email' => 'john@example.com',
            ])->assertStatus(200);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function a_user_can_not_invite_someone_to_another_users_menuplan()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/invitation', [
                'email' => 'john@example.com',
            ])->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_invitations_from_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $invitationOne = $menuplan->invitations()->create(['email' => 'john@example.com']);
        $invitationTwo = $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user)
            ->delete('/api/invitation/'.$invitationOne->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function a_user_can_not_delete_invitations_for_another_users_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();
        $invitationOne = $menuplan->invitations()->create(['email' => 'john@example.com']);
        $invitationTwo = $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user)
            ->delete('/api/invitation/'.$invitationOne->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);
    }
}
