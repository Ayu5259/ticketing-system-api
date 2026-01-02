<?php

namespace App\Domain\Tickets\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case PENDING = 'pending';
    case ANSWERED = 'answered';
    case CLOSED = 'closed';

    public static function default(): self
    {
        return self::OPEN;
    }
}
