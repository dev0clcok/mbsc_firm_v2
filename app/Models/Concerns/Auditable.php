<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            self::writeAudit('model.created', $model, null, $model->getAttributes());
        });

        static::updated(function (Model $model) {
            $dirty = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $dirty);
            self::writeAudit('model.updated', $model, $original, $dirty);
        });

        static::deleted(function (Model $model) {
            self::writeAudit('model.deleted', $model, $model->getOriginal(), null);
        });
    }

    protected static function writeAudit(string $event, Model $model, ?array $oldValues, ?array $newValues): void
    {
        // Avoid flooding logs during seeding/migrations.
        if (app()->runningInConsole() && ! config('audit.console', false)) {
            return;
        }

        // Don't recurse if we ever audit AuditLog itself.
        if ($model instanceof AuditLog) {
            return;
        }

        // The entry carries the request that caused the change, so one row
        // tells the whole story and no separate "request" row is needed.
        $request = request();
        $route = $request->route()?->getName();

        $log = AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'action' => $route,
            'route' => $route,
            'method' => strtoupper($request->getMethod()),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);

        // Read by the AuditRequest middleware once the response is known.
        $request->attributes->set('audit.model_rows', [...$request->attributes->get('audit.model_rows', []), $log->id]);
    }
}

