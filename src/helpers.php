<?php

use Jobful\HistoryTracking\HistoryTracking;
use Jobful\HistoryTracking\HistoryTrackingStatus;

if (! function_exists('historyTracking')) {
    function historyTracking(string $logName = null): HistoryTracking
    {
        $defaultLogName = config('activitylog.default_log_name');

        $logStatus = app(HistoryTrackingStatus::class);

        return app(HistoryTracking::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }
}
