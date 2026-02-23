<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use Auditable;

    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'position',
        'company',
        'text',
        'rating',
        'avatar_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
