<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:audit.list', only: ['index']),
        ];
    }

    public function index(Request $request): Response
    {
        $search = (string) $request->query('search', '');
        $event = (string) $request->query('event', '');
        $method = (string) $request->query('method', '');

        $auditLogs = AuditLog::query()
            ->with(['user:id,name,email'])
            ->when($event !== '', fn (Builder $q) => $q->where('event', $event))
            ->when($method !== '', fn (Builder $q) => $q->where('method', strtoupper($method)))
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('event', 'like', "%{$search}%")
                        ->orWhere('action', 'like', "%{$search}%")
                        ->orWhere('route', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%")
                        ->orWhere('ip', 'like', "%{$search}%")
                        ->orWhere('model_type', 'like', "%{$search}%")
                        ->orWhereRaw('CAST(model_id AS CHAR) like ?', ["%{$search}%"])
                        ->orWhereHas('user', function (Builder $uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();

        $names = $this->recordNames($auditLogs->getCollection());

        $auditLogs->through(fn (AuditLog $log) => [
            ...$log->toArray(),
            'subject' => $log->model_type ? [
                'type' => class_basename($log->model_type),
                'name' => $names[$log->model_type][$log->model_id] ?? $this->nameFrom($log->new_values ?? $log->old_values ?? []),
                'changes' => $log->event === 'model.updated'
                    ? array_values(array_diff(array_keys($log->new_values ?? []), ['updated_at', 'created_at', 'phone_normalized']))
                    : [],
            ] : null,
        ]);

        return Inertia::render('admin/AuditLogs/Index', [
            'auditLogs' => $auditLogs,
            'filters' => [
                'search' => $search,
                'event' => $event,
                'method' => $method,
            ],
            'eventOptions' => [
                'request',
                'model.created',
                'model.updated',
                'model.deleted',
                'auth.login',
                'auth.logout',
                'auth.failed',
            ],
        ]);
    }

    /** Attributes that name a record, in order of preference. */
    private const NAME_ATTRIBUTES = ['name', 'title', 'question', 'key', 'slug', 'email'];

    /**
     * Names of the records on this page that still exist, fetched with one
     * query per kind of record.
     *
     * @param  Collection<int, AuditLog>  $logs
     * @return array<class-string, array<int|string, string>>
     */
    private function recordNames(Collection $logs): array
    {
        return $logs->whereNotNull('model_type')->groupBy('model_type')
            ->map(function (Collection $group, string $type) {
                if (! is_subclass_of($type, Model::class)) {
                    return [];
                }

                return $type::query()->whereKey($group->pluck('model_id')->unique())->get()
                    ->mapWithKeys(fn (Model $record) => [$record->getKey() => $this->nameFrom($record->getAttributes())])
                    ->filter()
                    ->all();
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function nameFrom(array $attributes): ?string
    {
        foreach (self::NAME_ATTRIBUTES as $attribute) {
            if (filled($attributes[$attribute] ?? null) && is_scalar($attributes[$attribute])) {
                return str((string) $attributes[$attribute])->limit(80)->toString();
            }
        }

        return null;
    }
}

