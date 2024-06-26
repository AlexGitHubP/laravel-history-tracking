<?php

namespace Jobful\HistoryTracking;

use Illuminate\Contracts\Config\Repository;

class HistoryTrackingStatus
{
    protected $enabled = true;

    public function __construct(Repository $config)
    {
        $this->enabled = $config['history-tracking.enabled'];
    }

    public function enable(): bool
    {
        return $this->enabled = true;
    }

    public function disable(): bool
    {
        return $this->enabled = false;
    }

    public function disabled(): bool
    {
        return $this->enabled === false;
    }
}
