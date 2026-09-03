<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'notifications';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'created_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Constants
    const TYPE_APPLICATION_ACCEPTED = 'APPLICATION_ACCEPTED';
    const TYPE_APPLICATION_REJECTED = 'APPLICATION_REJECTED';
    const TYPE_PROGRAM_UPDATE = 'PROGRAM_UPDATE';
    const TYPE_CERTIFICATE_AVAILABLE = 'CERTIFICATE_AVAILABLE';
    const TYPE_PROFILE_UPDATE = 'PROFILE_UPDATE';
    const TYPE_SYSTEM = 'SYSTEM';
    const TYPE_TASK_ASSIGNED = 'TASK_ASSIGNED';
    const TYPE_TASK_COMPLETED = 'TASK_COMPLETED';

    // Helper
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
