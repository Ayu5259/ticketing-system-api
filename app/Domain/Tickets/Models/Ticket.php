<?php

namespace App\Domain\Tickets\Models;

use App\Domain\Tickets\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'owner_id',
        'assigned_to',
        'subject',
        'status',
    ];

    protected $casts = [
        // status رو string نگه می‌داریم برای سادگی MVP
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public static function defaultStatus(): string
    {
        return TicketStatus::default()->value;
    }

    public function isClosed(): bool
    {
        return $this->status === TicketStatus::CLOSED->value;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
