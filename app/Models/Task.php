<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tasks';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'program_id',
        'title',
        'description',
        'instructions',
        'deadline',
        'priority',
        'status',
        'is_mandatory',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'is_mandatory' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(InternshipProgram::class, 'program_id');
    }

    public function interns(): HasMany
    {
        return $this->hasMany(TaskIntern::class, 'task_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'CLOSED');
    }

    // Constants
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_CLOSED = 'CLOSED';

    const PRIORITY_LOW = 'LOW';
    const PRIORITY_NORMAL = 'NORMAL';
    const PRIORITY_HIGH = 'HIGH';
    const PRIORITY_URGENT = 'URGENT';

    // Helpers
    public function getCompletionPercentage(): float
    {
        $total = $this->interns()->count();
        if ($total === 0) return 0;
        $completed = $this->interns()->where('status', 'COMPLETED')->count();
        return round(($completed / $total) * 100, 1);
    }

    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast() && $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if deadline is approaching (within 1 week)
     */
    public function isDeadlineApproaching(): bool
    {
        if (!$this->deadline) return false;
        return now()->diffInDays($this->deadline, false) <= 7;
    }

    /**
     * Get days until deadline
     */
    public function daysUntilDeadline(): ?int
    {
        if (!$this->deadline) return null;
        return max(0, now()->diffInDays($this->deadline, false));
    }
}
