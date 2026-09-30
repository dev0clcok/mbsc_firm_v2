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
        'image_width',
        'image_height',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'image_width' => 'integer',
        'image_height' => 'integer',
    ];

    /**
     * The service picture in the shape the public site expects, or null.
     *
     * @return array{url: string, width: int|null, height: int|null}|null
     */
    public function image(): ?array
    {
        return $this->image_url
            ? ['url' => $this->image_url, 'width' => $this->image_width, 'height' => $this->image_height]
            : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

