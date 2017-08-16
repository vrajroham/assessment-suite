<?php

namespace Tests\Feature\SuperAdminRegistration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Hash;

class SuperuserRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatingSuperUseraccount()
    {
        $this->seed('TestingDatabaseSeeder');
        $this->disableExceptionHandling();
        $response = $this->call('GET', '/superuser/setup');
        $this->assertEquals(200, $response->getStatusCode());
        $user = [
            'name' => 'Super Admin',
            'email' => 'superadmin@sxample.com',
            'password' => 'mysecretpassword',
            'usertype' => 'superadmin',
        ];
        $response = $this->call('POST', '/superuser/setup', $user);
        $this->assertEquals(302, $response->getStatusCode());
        $this_user = User::first();
        $this->assertEquals($user['name'], $this_user->name);
        $this->assertEquals($user['email'], $this_user->email);
        $this->assertEquals($user['usertype'], $this_user->usertype);
        $this->assertTrue(Hash::check($user['password'], $this_user->password));

        // $user = factory(User::class)->create(['usertype' => 'superadmin']);
    }
}
