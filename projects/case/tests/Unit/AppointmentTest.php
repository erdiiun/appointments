<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppointmentTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testAppointment(): void
    {
        $user = User::factory()->create();
        $token = JwtAuth::fromUser($user);

        $appointment =
            '{
                "company_id": "1",
                "service_ids": "1,2,4",
                "customer_name": "ahmet",
                "customer_surname": "yılmaz",
                "appointment_date": "2023-06-21 10:25",
                "customer_phone": "02165042503"
            }';

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('api/v1/appointments', json_decode($appointment, true));
        $response->assertStatus(200);
    }
}
