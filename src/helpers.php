<?php

use Jobful\HistoryTracking\HistoryTracking;
use Jobful\HistoryTracking\HistoryTrackingStatus;

if (! function_exists('historyTracking')) {
    function historyTracking(string $trackerType = null): HistoryTracking
    {
        $defaultTrackerType = config('history-tracking.default_tracker_type');

        $logStatus = app(HistoryTrackingStatus::class);

        return app(HistoryTracking::class)
            ->useTrackerType($trackerType ?? $defaultTrackerType)
            ->setLogStatus($logStatus);
    }
}
