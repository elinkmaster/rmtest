<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /*
    * @test
    */
    public function testLoginFormIsAccessable()
    {
        $response = $this->get(route('login'));
        $response->assertViewIs('auth.login');
        $response->assertStatus(200);
    }

    public function testUserAuthenticateFailWithoutPassword()
    {
        $user = User::factory()->create();
        $response = $this->post(route('authenticate'),[
            'email' => $user->email
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('email');
    }
}
