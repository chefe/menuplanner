<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register_himself()
    {
        // PreAssert
        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com',
        ]);

        // Act
        $response = $this->from('/register')->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }
}
