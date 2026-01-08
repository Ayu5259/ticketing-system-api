<?php

namespace App\Application\Tickets;

use App\Domain\Tickets\Enums\TicketStatus;
use App\Domain\Tickets\Models\Ticket;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;

class CloseTicketAction
{
    public function execute(Ticket $ticket, int $userId): Ticket
    {
        $isOwner = (int) $ticket->owner_id === $userId;
        $isAssignee = (int) $ticket->assigned_to === $userId;

        if (! $isOwner && ! $isAssignee) {
            throw new AuthorizationException('You are not allowed to close this ticket.');
        }

        if ($ticket->status === TicketStatus::CLOSED->value) {
            throw new DomainException('Ticket is already closed.');
        }

        $ticket->status = TicketStatus::CLOSED->value;
        $ticket->save();

        return $ticket;
    }
}
