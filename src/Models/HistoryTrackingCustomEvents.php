<?php

namespace Jobful\HistoryTracking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class HistoryTrackingCustomEvents extends Model
{
    protected $table = 'history_tracking_custom_events';

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(HistoryTrackingEvents::class, 'type');
    }
}
