<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Support\Phone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

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
        'assigned_to',
        'ip_address',
    ];

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

    protected static function booted(): void
    {
        // Kept in step with the phone number however the enquiry is saved.
        static::saving(function (Enquiry $enquiry) {
            $enquiry->phone_normalized = Phone::normalize($enquiry->phone);
        });
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(EnquiryNote::class)->latest();
    }

    /**
     * Search, date range and sort order from the list page. Status is
     * applied separately so the tab counts can ignore it.
     */
    public function scopeFiltered(Builder $query, Request $request): Builder
    {
        if ($request->filled('search')) {
            $search = $request->string('search');
            // A search that is a phone number matches however it was typed.
            $digits = preg_match('/^[\d\s+\-().০-৯]{3,}$/u', $search) ? Phone::normalize($search) : null;

            $query->where(function (Builder $q) use ($search, $digits) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->when($digits, fn (Builder $q) => $q->orWhere('phone_normalized', 'like', "%{$digits}%"))
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('service', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        return $query;
    }

    /**
     * A wa.me link to the enquirer, or null when the number cannot be
     * used. Local numbers (01…) get Bangladesh's country code.
     */
    public function whatsappNumber(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) Phone::toAscii($this->phone));

        if (strlen($digits) < 10) {
            return null;
        }

        return str_starts_with($digits, '0') ? '88'.$digits : $digits;
    }
}
