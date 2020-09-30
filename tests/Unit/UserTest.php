<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function the_dates_of_a_user_are_serialized_with_the_correct_format()
    {
        $userData = User::factory()->make([
            'created_at' => Carbon::parse('2019-02-24'),
            'updated_at' => Carbon::parse('2020-03-27 09:00'),
        ])->toArray();

        $this->assertEquals($userData['created_at'], '2019-02-24 00:00:00');
        $this->assertEquals($userData['updated_at'], '2020-03-27 09:00:00');
    }
}
