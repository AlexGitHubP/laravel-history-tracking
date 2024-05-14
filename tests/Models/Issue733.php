<?php

namespace Jobful\HistoryTracking\Test\Models;

use Jobful\HistoryTracking\HistoryTrackingOptions;
use Jobful\HistoryTracking\Traits\LogsActivity;

class Issue733 extends Article
{
    use LogsActivity;

    protected static $recordEvents = [
        'retrieved',
    ];

    public function getActivitylogOptions(): HistoryTrackingOptions
    {
        return HistoryTrackingOptions::defaults()
        ->dontSubmitEmptyLogs()
        ->logOnly(['name']);
    }
}
