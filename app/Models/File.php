<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'files';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; // files table only has created_at, no updated_at

    protected $fillable = [
        'bucket_name',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function uploader()
    {
        return $this->belongsTo(Profile::class, 'uploaded_by');
    }
}
