<?php

namespace Jobful\HistoryTracking\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Jobful\HistoryTracking\HistoryTrackingServiceProvider;

trait CausesActivity
{
    public function actions(): MorphMany
    {
        return $this->morphMany(
            HistoryTrackingServiceProvider::determineActivityModel(),
            'causer'
        );
    }
}
