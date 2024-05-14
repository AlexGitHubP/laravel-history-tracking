<?php

namespace Jobful\HistoryTracking\Test\Models;

use Jobful\HistoryTracking\Models\HistoryTracking;

class CustomTableNameOnHistoryTrackingModel extends HistoryTracking
{
    protected $table = 'custom_table_name';
}
