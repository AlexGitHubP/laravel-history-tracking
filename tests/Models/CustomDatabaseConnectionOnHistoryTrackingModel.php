<?php

namespace Jobful\HistoryTracking\Test\Models;

use Jobful\HistoryTracking\Models\HistoryTracking;

class CustomDatabaseConnectionOnHistoryTrackingModel extends HistoryTracking
{
    protected $connection = 'custom_connection_name';
}
