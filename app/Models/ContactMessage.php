<?php

namespace App\Models;

use App\Enums\ContactMessageStatus;
use App\Enums\ContactWebhookStatus;
use Carbon\CarbonImmutable;
use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Message submitted through the public contact form (Contact module). The
 * record is the source of truth for delivery; the mail is sent by the
 * `SendContactMessage` job.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property string $locale
 * @property ContactMessageStatus $status
 * @property int $attempts
 * @property string|null $last_error
 * @property CarbonImmutable|null $sent_at
 * @property ContactWebhookStatus|null $webhook_status Null when no webhook is configured.
 * @property int $webhook_attempts
 * @property CarbonImmutable|null $webhook_delivered_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['name', 'email', 'message', 'locale', 'status', 'attempts', 'last_error', 'sent_at', 'webhook_status'])]
class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'pending',
        'attempts' => 0,
        'webhook_attempts' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ContactMessageStatus::class,
            'attempts' => 'integer',
            'sent_at' => 'datetime',
            'webhook_status' => ContactWebhookStatus::class,
            'webhook_attempts' => 'integer',
            'webhook_delivered_at' => 'datetime',
        ];
    }
}
