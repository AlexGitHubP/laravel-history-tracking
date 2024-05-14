<?php

use Jobful\HistoryTracking\Models\HistoryTracking;
use Jobful\HistoryTracking\Test\Models\CustomDatabaseConnectionOnHistoryTrackingModel;

it('uses the database connection from the configuration', function () {
    $model = new HistoryTracking();

    expect(config('historytrack.database_connection'))->toEqual($model->getConnectionName());
});

it('uses a custom database connection', function () {
    $model = new HistoryTracking();

    $model->setConnection('custom_sqlite');

    $this->assertNotEquals($model->getConnectionName(), config('historytrack.database_connection'));
    expect('custom_sqlite')->toEqual($model->getConnectionName());
});

it('uses the default database connection when the one from configuration is null', function () {
    app()['config']->set('historytrack.database_connection', null);

    $model = new HistoryTracking();

    expect($model->getConnection())->toBeInstanceOf('Illuminate\Database\SQLiteConnection');
});

it('uses the database connection from model', function () {
    $model = new CustomDatabaseConnectionOnHistoryTrackingModel();

    $this->assertNotEquals($model->getConnectionName(), config('historytrack.database_connection'));
    expect('custom_connection_name')->toEqual($model->getConnectionName());
});
