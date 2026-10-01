<?php

namespace App\Models\Concerns;

/**
 * New records go to the end of their list unless a position was chosen.
 * The create forms send 0 when the field is left alone, which used to put
 * every new record first.
 */
trait AppendsToOrder
{
    protected static function bootAppendsToOrder(): void
    {
        static::creating(function (self $model) {
            if ((int) $model->sort_order < 1) {
                $model->sort_order = (int) static::query()->max('sort_order') + 1;
            }
        });
    }
}
