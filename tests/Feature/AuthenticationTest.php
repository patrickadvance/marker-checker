<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature that an admin can login using email and password.
     *
     * @return void
     */
    public function test_an_admin_can_login_using_email_and_password()
    {
        $user = User::FindOrFail(1);

        $response = $this->post(route('login'),[
            "email" => $user->email,
            "password" => 'password',
            'device_name' => "Glover"
        ]);

        $response->assertSee('accessToken');

        $response->assertStatus(200);
    }

    /**
     * A basic feature that an admin can logout.
     *
     * @return void
     */
    public function test_an_admin_can_logout()
    {
        $user = User::FindOrFail(1);

        Sanctum::actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertStatus(201);
    }
}
