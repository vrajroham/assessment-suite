<?php

namespace Tests\Feature\SuperAdminRegistration;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperAdminRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function testRegistrationFormIfSuperUserNotFound()
    {
        $user = User::getUserFromUsertype('nameWhichWillFailTheTest');
        $this->assertNull($user);
        $response = $this->call('GET', '/superuser/setup');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegistrationFormIfSuperUserFound()
    {
        factory(User::class)->create(['usertype' => 'superadmin']);
        $user = User::getUserFromUsertype('superadmin');
        $response = $this->call('GET', '/superuser/setup');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(url('/'), $response->getTargetUrl());
    }
}
