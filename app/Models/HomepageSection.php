<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomepageSection extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'homepage_sections';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'section_key',
        'title',
        'content',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ── Scopes ──────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    // ── Static Helpers ──────────────────────────────────
    public static function getSection(string $key): ?self
    {
        return static::where('section_key', $key)
            ->whereNull('deleted_at')
            ->first();
    }

    public static function getContent(string $key, $default = null)
    {
        $section = static::getSection($key);
        return $section?->content ?? $default;
    }

    // ── Content Accessors ───────────────────────────────
    public function get(string $key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }

    public function items(): array
    {
        return $this->get('items', []);
    }
}
