<?php

namespace Tests\Feature\API;

use App\Menuplan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShareMenuplanTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_get_the_invitations_for_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
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
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();
        $menuplan->invitations()->create(['email' => 'john@example.com']);
        $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/invitation')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_invite_someone_new_to_his_menuplan()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
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
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $menuplan->invitations()->create(['email' => 'john@example.com']);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/invitation', [
                'email' => 'john@example.com',
            ])->assertStatus(200);
    }

    /** @test */
    public function a_user_can_not_invite_someone_to_another_users_menuplan()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/invitation', [
                'email' => 'john@example.com',
            ])->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_invitations_from_his_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create(['email' => 'john@example.com']);
        $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user)
            ->delete('/api/invitation/'.$invitation->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function a_user_can_not_delete_invitations_for_another_users_menuplans()
    {
        $user = User::factory()->create();
        $menuplan = Menuplan::factory()->create();
        $invitation = $menuplan->invitations()->create(['email' => 'john@example.com']);
        $menuplan->invitations()->create(['email' => 'jane@example.com']);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user)
            ->delete('/api/invitation/'.$invitation->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('invitations', [
            'menuplan_id' => $menuplan->id,
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function a_user_can_accept_an_invitation_to_a_menuplan()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create(['email' => $anotherUser->email]);

        $this->assertNull($invitation->user);

        $this->actingAs($anotherUser)
            ->post('/api/invitation/'.$invitation->id.'/accept')
            ->assertStatus(200);

        $this->assertNotNull($invitation->fresh()->user);
    }

    /** @test */
    public function a_user_can_not_accept_foreign_invitations()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create(['email' => $anotherUser->email]);

        $this->actingAs($user)
            ->post('/api/invitation/'.$invitation->id.'/accept')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_decline_an_invitation_to_a_menuplan()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create(['email' => $anotherUser->email]);

        $this->actingAs($anotherUser)
            ->delete('/api/invitation/'.$invitation->id.'/decline')
            ->assertStatus(200);

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id,
        ]);
    }

    /** @test */
    public function a_user_can_not_decline_an_foreign_invitations()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create(['email' => $anotherUser->email]);

        $this->actingAs($user)
            ->delete('/api/invitation/'.$invitation->id.'/decline')
            ->assertStatus(403);

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
        ]);
    }

    /** @test */
    public function a_user_can_leave_his_invitations()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create([
            'email' => $anotherUser->email,
            'user_id' => $anotherUser->id,
        ]);

        $this->actingAs($anotherUser)
            ->delete('/api/invitation/'.$invitation->id.'/decline')
            ->assertStatus(200);

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id,
        ]);
    }

    /** @test */
    public function a_user_can_not_leave_foreign_invitations()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $thirdUser = User::factory()->create();
        $menuplan = Menuplan::factory()->create(['user_id' => $user->id]);
        $invitation = $menuplan->invitations()->create([
            'email' => $anotherUser->email,
            'user_id' => $anotherUser->id,
        ]);

        $this->actingAs($thirdUser)
            ->delete('/api/invitation/'.$invitation->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
        ]);
    }

    /** @test */
    public function a_user_can_get_all_his_invitations()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $thirdUser = User::factory()->create();

        $menuplanOne = Menuplan::factory()->create(['user_id' => $anotherUser->id]);
        $invitationOne = $menuplanOne->invitations()->create([
            'email' => $user->email,
        ]);

        $menuplanTwo = Menuplan::factory()->create(['user_id' => $thirdUser->id]);
        $invitationTwo = $menuplanTwo->invitations()->create([
            'email' => $user->email,
        ]);

        $this->actingAs($user)
            ->get('/api/invitation')
            ->assertStatus(200)
            ->assertJson([
                ['menuplan_id' => $invitationOne->menuplan_id],
                ['menuplan_id' => $invitationTwo->menuplan_id],
            ]);
    }
}
