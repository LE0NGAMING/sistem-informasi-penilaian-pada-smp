<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_bisa_login_via_api_dan_mendapatkan_sanctum_token(): void
    {
        $user = User::factory()->create([
            'email' => 'siswa@smpn1.sch.id',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'siswa@smpn1.sch.id',
            'password' => 'password123',
            'device_name' => 'Mobile_Test_Device'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'user']
            ]);
    }
}
