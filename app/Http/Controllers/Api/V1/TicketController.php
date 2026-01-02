<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Tickets\CreateTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\CreateTicketRequest;
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

        return response()->json([
            'data' => [
                'id' => $ticket->id,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'owner_id' => $ticket->owner_id,
                'created_at' => $ticket->created_at,
                'messages' => $ticket->messages->map(fn($m) => [
                    'id' => $m->id,
                    'author_id' => $m->author_id,
                    'body' => $m->body,
                    'created_at' => $m->created_at,
                ]),
            ],
        ], 201);
    }
}
