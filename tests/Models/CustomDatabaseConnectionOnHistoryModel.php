<?php

namespace Jobful\HistoryTracking\Test\Models;

use Jobful\HistoryTracking\Models\History;

class CustomDatabaseConnectionOnHistoryModel extends History
{
    protected $connection = 'custom_connection_name';
}
