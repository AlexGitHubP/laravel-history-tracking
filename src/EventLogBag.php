<?php

namespace Jobful\HistoryTracking;

use Illuminate\Database\Eloquent\Model;

class EventLogBag
{
    public function __construct(
        public string $event,
        public Model $model,
        public array $changes,
        public ?HistoryTrackingOptions $options = null
    ) {
        $this->options ??= $model->getActivitylogOptions();
    }
}
