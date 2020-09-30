<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_navigate_to_the_login_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Login')
                ->assertSee('Login')
                ->assertSee('E-Mail')
                ->assertSee('Password');
        });
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/')
                ->assertSee('Menuplans');
        });
    }
}
