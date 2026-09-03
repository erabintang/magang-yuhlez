<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskIntern extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'task_interns';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'task_id',
        'intern_id',
        'status',
        'note',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function intern(): BelongsTo
    {
        return $this->belongsTo(InternProfile::class, 'intern_id');
    }

    // Constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_COMPLETED = 'COMPLETED';

    // Legacy alias for backward compatibility
    const STATUS_NEW = 'NEW';
}
