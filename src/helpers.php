<?php

use Jobful\HistoryTracking\HistoryTracking;
use Jobful\HistoryTracking\HistoryTrackingStatus;

if (! function_exists('historyTracking')) {
    function historyTracking(): HistoryTracking
    {
        $logStatus = app(HistoryTrackingStatus::class);

        return app(HistoryTracking::class)
            ->setLogStatus($logStatus);
    }
}
