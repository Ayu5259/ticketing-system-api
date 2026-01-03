<?php

namespace Tests\Feature\Tickets;

use App\Domain\Tickets\Enums\TicketStatus;
use App\Domain\Tickets\Models\Ticket;
use App\Domain\Tickets\Models\TicketMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplyToTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_reply_sets_status_to_pending(): void
    {
        $owner = User::factory()->create();

        $ticket = Ticket::query()->create([
            'owner_id' => $owner->id,
            'subject'  => 'Test',
            'status'   => TicketStatus::OPEN->value,
        ]);

        Sanctum::actingAs($owner);

        $res = $this->postJson("/api/v1/tickets/{$ticket->id}/reply", [
            'message' => 'Owner reply',
        ]);

        $res->assertOk()
            ->assertJsonPath('data.status', TicketStatus::PENDING->value);

        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'author_id' => $owner->id,
            'body' => 'Owner reply',
        ]);
    }

    public function test_assignee_reply_sets_status_to_answered(): void
    {
        $owner = User::factory()->create();
        $agent = User::factory()->create();

        $ticket = Ticket::query()->create([
            'owner_id' => $owner->id,
            'assigned_to' => $agent->id,
            'subject'  => 'Test',
            'status'   => TicketStatus::PENDING->value,
        ]);

        Sanctum::actingAs($agent);

        $res = $this->postJson("/api/v1/tickets/{$ticket->id}/reply", [
            'message' => 'Agent reply',
        ]);

        $res->assertOk()
            ->assertJsonPath('data.status', TicketStatus::ANSWERED->value);

        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'author_id' => $agent->id,
            'body' => 'Agent reply',
        ]);
    }
}
