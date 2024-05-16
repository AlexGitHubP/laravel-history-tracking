<?php

use Jobful\HistoryTracking\Models\History;
use Jobful\HistoryTracking\Test\Models\CustomTableNameOnHistoryModel;

it('uses the table name from the configuration', function () {
    $model = new History();

    expect(config('history-tracking.table_name'))->toEqual($model->getTable());
});

it('uses a custom table name', function () {
    $model = new History();
    $newTableName = 'my_personal_activities';

    $model->setTable($newTableName);

    $this->assertNotEquals($model->getTable(), config('history-tracking.table_name'));
    expect($newTableName)->toEqual($model->getTable());
});

it('uses the table name from the model', function () {
    $model = new CustomTableNameOnHistoryModel();

    $this->assertNotEquals($model->getTable(), config('history-tracking.table_name'));
    expect('custom_table_name')->toEqual($model->getTable());
});
