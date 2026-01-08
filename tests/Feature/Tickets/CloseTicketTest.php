<?php

namespace Tests\Feature\Tickets;

use App\Domain\Tickets\Enums\TicketStatus;
use App\Domain\Tickets\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CloseTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_close_ticket(): void
    {
        $owner = User::factory()->create();

        $ticket = Ticket::query()->create([
            'owner_id' => $owner->id,
            'subject'  => 'Test',
            'status'   => TicketStatus::OPEN->value,
        ]);

        Sanctum::actingAs($owner);

        $res = $this->postJson("/api/v1/tickets/{$ticket->id}/close");

        $res->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', TicketStatus::CLOSED->value);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => TicketStatus::CLOSED->value,
        ]);
    }

    public function test_non_owner_non_assignee_cannot_close_ticket(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $ticket = Ticket::query()->create([
            'owner_id' => $owner->id,
            'subject'  => 'Test',
            'status'   => TicketStatus::OPEN->value,
        ]);

        Sanctum::actingAs($intruder);

        $res = $this->postJson("/api/v1/tickets/{$ticket->id}/close");

        $res->assertStatus(403)
            ->assertJsonPath('success', false);
    }
}
