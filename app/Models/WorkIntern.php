<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkIntern extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'work_interns';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'work_id',
        'intern_id',
        'added_at',
        'removed_at',
    ];

    protected function casts(): array
    {
        return [
            'added_at' => 'datetime',
            'removed_at' => 'datetime',
            'created_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function intern(): BelongsTo
    {
        return $this->belongsTo(InternProfile::class, 'intern_id');
    }
}
