<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{

    /**
     * Test for user register
     */

    public function testUserRegister(): void
    {
        $user =
            '{
                "email": "tesssst@gmail.com",
                "password": "password",
                "name": "name",
                "password_confirmation": "password"
            }';

        $user = json_decode($user, true);
        $response = $this->post('/api/auth/register', $user);
        $response->assertStatus(201);
    }

    /**
     * Test for user login
     */

    public function testUserLogin(): void
    {
        $user =
            '{
                "email": "tesssst@gmail.com",
                "password": "password"
            }';

        $user = json_decode($user, true);
        $response = $this->post('/api/auth/login', $user);
        $response->assertStatus(200);
    }

    public function testUserInfo()
    {
        $user = User::factory()->create();
        $token = JwtAuth::fromUser($user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('api/v1/users');
        $response->assertStatus(200);
    }

    public function testUserUpdate()
    {
        $user = User::factory()->create();
        $token = JwtAuth::fromUser($user);

        $new_user=
             '{
                "name": "name",
                "password": "passwordd",
                "password_confirmation": "passwordd"
             }';


        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('api/v1/users', json_decode($new_user, true));
        $response->assertStatus(200);

    }

}
