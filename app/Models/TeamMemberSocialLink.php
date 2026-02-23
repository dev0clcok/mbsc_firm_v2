<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMemberSocialLink extends Model
{
    protected $table = 'team_member_social_links';

    protected $fillable = [
        'team_member_id',
        'platform',
        'url',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }

    public static function platforms(): array
    {
        return [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'twitter' => 'X (Twitter)',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'email' => 'Email',
        ];
    }
}
