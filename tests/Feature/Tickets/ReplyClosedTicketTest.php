<?php

namespace Tests\Feature\Tickets;

use App\Domain\Tickets\Enums\TicketStatus;
use App\Domain\Tickets\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplyClosedTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_reply_to_closed_ticket(): void
    {
        $owner = User::factory()->create();

        $ticket = Ticket::query()->create([
            'owner_id' => $owner->id,
            'subject'  => 'Test',
            'status'   => TicketStatus::CLOSED->value,
        ]);

        Sanctum::actingAs($owner);

        $res = $this->postJson("/api/v1/tickets/{$ticket->id}/reply", [
            'message' => 'Hello',
        ]);

        $res->assertStatus(422)
            ->assertJsonPath('success', false);
    }
}
