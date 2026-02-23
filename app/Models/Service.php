<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Auditable;

    protected $table = 'services';

    protected $fillable = [
        'slug',
        'title',
        'short_description',
        'description',
        'icon_svg',
        'features',
        'image_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

