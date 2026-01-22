<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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
}

