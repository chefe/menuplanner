<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_login_with_valid_credential()
    {
        // Arrange
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        // PreAssert
        $this->assertGuest();

        // Act
        $response = $this->from('/login')->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        // Assert
        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    /** @test */
    public function a_user_can_not_login_with_invalid_credential()
    {
        // Arrange
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        // PreAssert
        $this->assertGuest();

        // Act
        $response = $this->from('/login')->post('/login', [
            'email' => 'john@example.com',
            'password' => 'invalid-password',
        ]);

        // Assert
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function a_user_gets_redirected_if_already_logged_in()
    {
        // Arrange
        $user = factory(User::class)->create();

        // Act
        $response = $this->actingAs($user)->get('/login');

        // Assert
        $response->assertRedirect('/');
    }
}
