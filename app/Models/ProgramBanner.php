<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramBanner extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'program_banners';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'program_id',
        'file_id',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function program(): BelongsTo
    {
        return $this->belongsTo(InternshipProgram::class, 'program_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
