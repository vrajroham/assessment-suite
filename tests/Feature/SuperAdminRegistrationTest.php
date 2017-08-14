<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperAdminRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function testRegistrationFormIfSuperUserNotFound()
    {
        $this->visit('/register')
         ->type('Taylor', 'name')
         ->check('terms')
         ->press('Register')
         ->seePageIs('/dashboard');
    }

    public function testRegistrationFormIfSuperUserFound()
    {
        // $user = User::getUserFromUsertype('superadmin');
        // $response = $this->call('GET', '/');
        // $this->assertEquals(302, $response->getStatusCode());
        // $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
        // $this->assertEquals($this->baseUrl.'/auth/login', $response->getTargetUrl());
    }
}
