<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\ToolResultMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

/**
 * @property string $id
 * @property string $chat_id
 * @property string $role
 * @property array<string, string>|null $parts
 * @property array<array-key, mixed> $attachments
 * @property int|null $is_upvoted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Chat $chat
 *
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereIsUpvoted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereParts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
final class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;

    use HasUuids;

    protected $guarded = [];

    /**
     * Get the user that the OAuth connection belongs to.
     *
     * @return BelongsTo<Chat, covariant $this>
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function toPrism(): \Prism\Prism\Contracts\Message
    {
        return match ($this->role) {
            'user' => new UserMessage($this->parts['text'] ?? ''),
            'assistant' => new AssistantMessage($this->parts['text'] ?? '', $this->parts['tool_calls'] ?? []),
            'tool_result' => new ToolResultMessage($this->parts['tool_results'] ?? []),
        };
    }

    protected function casts(): array
    {
        return [
            'parts' => 'array',
            'attachments' => 'array',
            'is_upvoted' => 'boolean',
        ];
    }
}
