<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GetUserFromUsertypeTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserCanFoundByUsertype()
    {
        $createdUser = factory(User::class)->create(['usertype' => 'superadmin']);
        $foundUser = User::getUserFromUsertype('superadmin');
        $this->assertEquals($createdUser->id, $foundUser->id);
        $this->assertEquals($createdUser->usertype, $foundUser->usertype);
    }
}
