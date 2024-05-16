<?php

use Carbon\Carbon;
use Jobful\HistoryTracking\Models\History;

beforeEach(function () {
    Carbon::setTestNow(Carbon::create(2016, 1, 1, 00, 00, 00));

    app()['config']->set('history-tracking.delete_records_older_than_days', 31);
});

it('can clean the activity log', function () {
    collect(range(1, 60))->each(function (int $index) {
        History::create([
            'description' => "item {$index}",
            'created_at' => Carbon::now()->subDays($index)->startOfDay(),
        ]);
    });

    expect(History::all())->toHaveCount(60);

    Artisan::call('activitylog:clean');

    expect(History::all())->toHaveCount(31);

    $cutOffDate = Carbon::now()->subDays(31)->format('Y-m-d H:i:s');

    expect(History::where('created_at', '<', $cutOffDate)->get())->toHaveCount(0);
});

it('can accept days as option to override config setting', function () {
    collect(range(1, 60))->each(function (int $index) {
        History::create([
            'description' => "item {$index}",
            'created_at' => Carbon::now()->subDays($index)->startOfDay(),
        ]);
    });

    expect(History::all())->toHaveCount(60);

    Artisan::call('activitylog:clean', ['--days' => 7]);

    expect(History::all())->toHaveCount(7);

    $cutOffDate = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');

    expect(History::where('created_at', '<', $cutOffDate)->get())->toHaveCount(0);
});
