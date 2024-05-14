<?php

namespace Jobful\HistoryTracking\Facades;

use Illuminate\Support\Facades\Facade;
use Jobful\HistoryTracking\CauserResolver as ActivitylogCauserResolver;

/**
 * @method static \Illuminate\Database\Eloquent\Model|null resolve(\Illuminate\Database\Eloquent\Model|int|string|null $subject = null)
 * @method static \Jobful\HistoryTracking\CauserResolver resolveUsing(\Closure $callback)
 * @method static \Jobful\HistoryTracking\CauserResolver setCauser(\Illuminate\Database\Eloquent\Model|null $causer)
 *
 * @see \Jobful\HistoryTracking\CauserResolver
 */
class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivitylogCauserResolver::class;
    }
}
