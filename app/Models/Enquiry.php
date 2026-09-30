<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use Auditable;

    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [self::STATUS_NEW, self::STATUS_CONTACTED, self::STATUS_CLOSED];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'service',
        'message',
        'status',
        'ip_address',
    ];

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];
}
