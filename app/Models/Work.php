<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Work extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'works';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'work_type',
        'slug',
        'title',
        'short_description',
        'description',
        'is_published',
        'published_at',
        'poster_file_id',
        'media_file_id',
        'category',
        'year',
        'source_code_url',
        'deploy_url',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'year' => 'integer',
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

    public function poster(): BelongsTo
    {
        return $this->belongsTo(File::class, 'poster_file_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(File::class, 'media_file_id');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(WorkGallery::class, 'work_id');
    }

    public function interns(): HasMany
    {
        return $this->hasMany(WorkIntern::class, 'work_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(WorkSubmission::class, 'work_id');
    }

    // Constants
    const TYPE_PROGRAM_WORK = 'PROGRAM_WORK';
    const TYPE_PUBLIC_WORK = 'PUBLIC_WORK';
}
