<?php

namespace Tests\Feature\Tickets;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_ticket(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $res = $this->postJson('/api/v1/tickets', [
            'subject' => 'Login issue',
            'message' => 'I cannot login since yesterday.',
        ]);

        $res->assertCreated()
            ->assertJsonPath('data.subject', 'Login issue')
            ->assertJsonPath('data.status', 'open');
    }
}
