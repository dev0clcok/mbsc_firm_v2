<?php

namespace App\Models;

use App\Models\Concerns\AppendsToOrder;
use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQ extends Model
{
    use AppendsToOrder, Auditable;

    protected $table = 'faqs';

    protected $fillable = [
        'service_id',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * The answer as safe HTML for the public site. Answers come from the
     * admin's rich text editor; only basic formatting is kept, and a plain
     * text answer keeps its line breaks.
     */
    public function answerHtml(): string
    {
        $answer = (string) $this->answer;

        if ($answer === strip_tags($answer)) {
            return nl2br(e($answer));
        }

        $clean = strip_tags($answer, '<p><br><strong><b><em><i><u><ul><ol><li><a>');

        // Drop every attribute except a link's http(s) address.
        return preg_replace_callback('/<(\w+)\b[^>]*>/', function (array $m) {
            if (strtolower($m[1]) === 'a' && preg_match('/href\s*=\s*"(https?:\/\/[^"]*)"/i', $m[0], $href)) {
                return '<a href="'.e($href[1]).'" rel="noopener">';
            }

            return '<'.$m[1].'>';
        }, $clean) ?? '';
    }

    /** The answer as plain text, for search engines. */
    public function answerText(): string
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '<br>', '</li>'], ' ', (string) $this->answer)))) ?? '');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
