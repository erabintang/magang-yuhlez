<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusHistory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'application_status_histories';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'application_id',
        'old_status',
        'new_status',
        'reason',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(InternshipApplication::class, 'application_id');
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'changed_by');
    }
}
