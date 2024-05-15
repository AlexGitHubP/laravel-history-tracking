<?php

namespace Jobful\HistoryTracking\Test\Models;

use Jobful\HistoryTracking\Models\History;

class CustomTableNameOnHistoryModel extends History
{
    protected $table = 'custom_table_name';
}
