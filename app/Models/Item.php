<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'uri',
        'cover_path',
        'sort_order'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Item::class, 'parent_id');
    }

    public function coverImageUrl(): string {
        if (empty($this->cover_path)) {
            return '/storage/default.png';
        }

        if(str_starts_with($this->cover_path, 'http')) {
            return $this->cover_path;
        }

        return asset('covers/' . $this->cover_path);
    }
}
