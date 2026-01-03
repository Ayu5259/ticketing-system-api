<?php

namespace App\Application\Tickets;

use App\Domain\Tickets\Enums\TicketStatus;
use App\Domain\Tickets\Models\Ticket;
use App\Domain\Tickets\Models\TicketMessage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use DomainException;

class ReplyToTicketAction
{
    public function execute(Ticket $ticket, int $userId, string $message): Ticket
    {
        if ($ticket->isClosed()) {
            throw new DomainException('Ticket is closed.');
        }

        // دسترسی ساده: فقط owner یا assignee حق reply دارند
        $isOwner = $ticket->owner_id === $userId;
        $isAssignee = (int) $ticket->assigned_to === $userId;

        if (! $isOwner && ! $isAssignee) {
            throw new AuthorizationException('You are not allowed to reply to this ticket.');
        }

        return DB::transaction(function () use ($ticket, $userId, $message, $isOwner) {

            TicketMessage::query()->create([
                'ticket_id' => $ticket->id,
                'author_id' => $userId,
                'body'      => $message,
            ]);

            // قانون وضعیت خودکار
            $ticket->setStatus(
                $isOwner
                    ? TicketStatus::PENDING->value
                    : TicketStatus::ANSWERED->value
            );

            $ticket->save();

            return $ticket->load('messages');
        });
    }
}
