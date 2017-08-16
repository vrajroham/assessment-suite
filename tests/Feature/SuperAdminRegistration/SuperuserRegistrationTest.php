<?php

namespace Tests\Feature\SuperAdminRegistration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\AdminProfile;
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
            'designation' => 'designation',
            'mobile' => 'mobile',
        ];
        $response = $this->call('POST', '/superuser/setup', $user);
        $this->assertEquals(302, $response->getStatusCode());
        $this_user = User::first();
        $this->assertEquals($user['name'], $this_user->name);
        $this->assertEquals($user['email'], $this_user->email);
        $this->assertTrue(Hash::check($user['password'], $this_user->password));

        $same_user = AdminProfile::first();
        $this->assertEquals($user['designation'], $same_user->designation);
        $this->assertEquals($user['email'], $same_user->email);
        $this->assertEquals($user['mobile'], $same_user->mobile);
        $this->assertEquals($this_user->id, $same_user->id);
    }
}
