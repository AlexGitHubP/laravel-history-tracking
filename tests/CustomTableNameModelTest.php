<?php

use Jobful\HistoryTracking\Models\HistoryTracking;
use Jobful\HistoryTracking\Test\Models\CustomTableNameOnHistoryTrackingModel;

it('uses the table name from the configuration', function () {
    $model = new HistoryTracking();

    expect(config('historytrack.table_name'))->toEqual($model->getTable());
});

it('uses a custom table name', function () {
    $model = new HistoryTracking();
    $newTableName = 'my_personal_activities';

    $model->setTable($newTableName);

    $this->assertNotEquals($model->getTable(), config('historytrack.table_name'));
    expect($newTableName)->toEqual($model->getTable());
});

it('uses the table name from the model', function () {
    $model = new CustomTableNameOnHistoryTrackingModel();

    $this->assertNotEquals($model->getTable(), config('historytrack.table_name'));
    expect('custom_table_name')->toEqual($model->getTable());
});
