<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Tickets\CloseTicketAction;
use App\Application\Tickets\CreateTicketAction;
use App\Application\Tickets\ReplyToTicketAction;
use App\Domain\Tickets\Models\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\CreateTicketRequest;
use App\Http\Requests\Tickets\ReplyToTicketRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function store(CreateTicketRequest $request, CreateTicketAction $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $ticket = $action->execute(
            $userId,
            $request->string('subject')->toString(),
            $request->string('message')->toString()
        );

        // اگر action پیام اول را ساخته باشد و رابطه load شده باشد، همین کار می‌کند.
        // اگر messages load نیست، می‌توانی داخل action بعد از create، $ticket->load('messages') کنی.
        return ApiResponse::success([
            'id'         => $ticket->id,
            'subject'    => $ticket->subject,
            'status'     => $ticket->status,
            'owner_id'   => $ticket->owner_id,
            'created_at' => $ticket->created_at,
            'messages'   => $ticket->messages->map(fn($m) => [
                'id'         => $m->id,
                'author_id'  => $m->author_id,
                'body'       => $m->body,
                'created_at' => $m->created_at,
            ]),
        ], 'Ticket created', 201);
    }

    public function reply(
        ReplyToTicketRequest $request,
        Ticket $ticket,
        ReplyToTicketAction $action
    ): JsonResponse {
        $userId = (int) $request->user()->id;

        $ticket = $action->execute(
            $ticket,
            $userId,
            $request->string('message')->toString()
        );

        return ApiResponse::success([
            'id'             => $ticket->id,
            'subject'        => $ticket->subject,
            'status'         => $ticket->status,
            'messages_count' => $ticket->messages->count(),
        ], 'Reply added');
    }

    public function close(
        \Illuminate\Http\Request $request,
        Ticket $ticket,
        CloseTicketAction $action
    ): JsonResponse {
        $userId = (int) $request->user()->id;

        $ticket = $action->execute($ticket, $userId);

        return ApiResponse::success([
            'id'     => $ticket->id,
            'status' => $ticket->status,
        ], 'Ticket closed');
    }
}
