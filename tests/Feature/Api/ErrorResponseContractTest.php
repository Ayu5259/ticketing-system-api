<?php

namespace Tests\Feature\Api;

use App\Domain\Tickets\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ErrorResponseContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_returns_standard_shape(): void
    {
        // بدون لاگین باید 401 استاندارد بده
        $res = $this->postJson('/api/v1/tickets', [
            'subject' => 'Login issue',
            'message' => 'I cannot login since yesterday.',
        ]);

        $res->assertStatus(401)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    public function test_validation_error_returns_standard_shape(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // subject را عمدا نمی‌فرستیم تا validation fail شود
        $res = $this->postJson('/api/v1/tickets', [
            'message' => 'Hello',
        ]);

        $res->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'subject',
                ],
            ]);
    }

    public function test_not_found_returns_standard_shape(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // ticket وجود ندارد
        $res = $this->postJson('/api/v1/tickets/999999/reply', [
            'message' => 'Hello',
        ]);

        $res->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Not Found',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }
}
