<?php

namespace Jobful\HistoryTracking\Test\Models;

use Jobful\HistoryTracking\HistoryTrackingOptions;
use Jobful\HistoryTracking\Traits\LogsActivity;

class ArticleWithLogDescriptionClosure extends Article
{
    use LogsActivity;

    public function getActivitylogOptions(): HistoryTrackingOptions
    {
        return HistoryTrackingOptions::defaults()
            ->setDescriptionForEvent(function ($eventName) {
                return $eventName;
            });
    }
}
