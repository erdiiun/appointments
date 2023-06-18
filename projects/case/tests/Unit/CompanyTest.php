<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testCompanyList(): void
    {
        $user = User::factory()->create();
        $token = JwtAuth::fromUser($user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('api/v1/companies');
        $response->assertStatus(200);
    }

    public function testCompanyService()
    {
        $user = User::factory()->create();
        $token = JwtAuth::fromUser($user);

        $company_id =
            '{
                "company_id": 1
            }';

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('api/v1/companies/services', json_decode($company_id, true));
        $response->assertStatus(200);
    }
}
