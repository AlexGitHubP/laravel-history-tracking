<?php

use Jobful\HistoryTracking\Models\History;
use Jobful\HistoryTracking\Test\Models\CustomDatabaseConnectionOnHistoryModel;

it('uses the database connection from the configuration', function () {
    $model = new History();

    expect(config('history-tracking.database_connection'))->toEqual($model->getConnectionName());
});

it('uses a custom database connection', function () {
    $model = new History();

    $model->setConnection('custom_sqlite');

    $this->assertNotEquals($model->getConnectionName(), config('history-tracking.database_connection'));
    expect('custom_sqlite')->toEqual($model->getConnectionName());
});

it('uses the default database connection when the one from configuration is null', function () {
    app()['config']->set('history-tracking.database_connection', null);

    $model = new History();

    expect($model->getConnection())->toBeInstanceOf('Illuminate\Database\SQLiteConnection');
});

it('uses the database connection from model', function () {
    $model = new CustomDatabaseConnectionOnHistoryModel();

    $this->assertNotEquals($model->getConnectionName(), config('history-tracking.database_connection'));
    expect('custom_connection_name')->toEqual($model->getConnectionName());
});
