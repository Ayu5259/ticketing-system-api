<?php

namespace App\Application\Tickets;

use App\Domain\Tickets\Models\Ticket;
use App\Domain\Tickets\Models\TicketMessage;
use Illuminate\Support\Facades\DB;

class CreateTicketAction
{
    public function execute(int $userId, string $subject, string $message): Ticket
    {
        return DB::transaction(function () use ($userId, $subject, $message) {

            $ticket = Ticket::query()->create([
                'owner_id' => $userId,
                'subject'  => $subject,
                'status'   => Ticket::defaultStatus(),
            ]);

            TicketMessage::query()->create([
                'ticket_id' => $ticket->id,
                'author_id' => $userId,
                'body'      => $message,
            ]);

            return $ticket->load('messages');
        });
    }
}
