<?php

namespace Jobful\HistoryTracking\Contracts;

use Closure;
use Jobful\HistoryTracking\EventLogBag;

interface LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag;
}
