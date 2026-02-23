<?php

namespace App\Http\Services;

use App\Models\FAQ;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FAQService
{
    /**
     * @return LengthAwarePaginator|Collection<int, FAQ>
     */
    public function index(Request $request, bool $pagination = true): LengthAwarePaginator|Collection
    {
        $query = FAQ::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->integer('status'));
        }

        $query = $query->orderBy('sort_order')->orderBy('id');

        if ($pagination) {
            return $query->paginate(config('app.settings.pagination.per_page'))->withQueryString();
        }

        return $query->get();
    }
}
