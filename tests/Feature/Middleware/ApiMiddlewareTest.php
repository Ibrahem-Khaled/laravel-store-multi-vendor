<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ApiMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ApiAuth middleware with valid token
     */
    public function test_api_auth_middleware_with_valid_token()
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v2/user/profile');

        $response->assertStatus(200);
    }

    /**
     * Test ApiAuth middleware with invalid token
     */
    public function test_api_auth_middleware_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/v2/user/profile');

        $response->assertStatus(401)
                ->assertJson([
                    'status' => false,
                    'code' => 'INVALID_TOKEN'
                ]);
    }

    /**
     * Test ApiAuth middleware without token
     */
    public function test_api_auth_middleware_without_token()
    {
        $response = $this->getJson('/api/v2/user/profile');

        $response->assertStatus(401)
                ->assertJson([
                    'status' => false,
                    'code' => 'UNAUTHENTICATED'
                ]);
    }

    /**
     * Test ApiAuthAndActive middleware with inactive user
     */
    public function test_api_auth_active_middleware_with_inactive_user()
    {
        $user = User::factory()->create([
            'status' => 'inactive',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v2/user/profile');

        $response->assertStatus(403)
                ->assertJson([
                    'status' => false,
                    'code' => 'ACCOUNT_DEACTIVATED'
                ]);
    }

    /**
     * Test ApiAuthAndActive middleware with banned user
     */
    public function test_api_auth_active_middleware_with_banned_user()
    {
        $user = User::factory()->create([
            'status' => 'banned',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v2/user/profile');

        $response->assertStatus(403)
                ->assertJson([
                    'status' => false,
                    'code' => 'ACCOUNT_BANNED'
                ]);
    }

    /**
     * Test ApiAuthAndActive middleware with deleted user
     */
    public function test_api_auth_active_middleware_with_deleted_user()
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $user->delete(); // Soft delete

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v2/user/profile');

        $response->assertStatus(403)
                ->assertJson([
                    'status' => false,
                    'code' => 'ACCOUNT_DELETED'
                ]);
    }

    /**
     * Test ApiAuthAndActive middleware with active user
     */
    public function test_api_auth_active_middleware_with_active_user()
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v2/user/profile');

        $response->assertStatus(200);
    }
}
