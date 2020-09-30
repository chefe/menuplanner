<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_navigate_to_the_login_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Register')
                ->assertSee('Register')
                ->assertSee('Name')
                ->assertSee('E-Mail')
                ->assertSee('Password')
                ->assertSee('Confirm');
        });
    }

    /** @test */
    public function a_user_can_register_themself()
    {
        $user = User::factory()->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                ->type('name', $user->name)
                ->type('email', $user->email)
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
                ->assertPathIs('/')
                ->assertSee('Menuplans');
        });
    }
}
