<?php

namespace Tests\Feature;

use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    /** @test */
    public function a_user_can_set_the_locale_to_german()
    {
        $response = $this->get('/locale/de');
        $response->assertSessionHas('locale', 'de');
        $response->assertRedirect('/');
    }

    /** @test */
    public function a_user_can_set_the_locale_to_english()
    {
        $response = $this->get('/locale/en');
        $response->assertSessionHas('locale', 'en');
        $response->assertRedirect('/');
    }
}
