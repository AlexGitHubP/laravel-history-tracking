<?php

namespace Jobful\HistoryTracking;

use Illuminate\Database\Eloquent\Model;
use Jobful\HistoryTracking\Contracts\Activity;
use Jobful\HistoryTracking\Contracts\Activity as ActivityContract;
use Jobful\HistoryTracking\Exceptions\InvalidConfiguration;
use Jobful\HistoryTracking\Models\HistoryTracking as ActivityModel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HistoryTrackingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
        ->name('laravel-activitylog')
        ->hasConfigFile('activitylog')
        ->hasMigrations([
            'create_activity_log_table',
            'add_event_column_to_activity_log_table',
            'add_batch_uuid_column_to_activity_log_table',
            'add_history_tracking_support_to_activity_log_table'
        ])
        ->hasCommand(CleanHistoryTrackingCommand::class);

        $this->publishes([
            __DIR__.'/Models/PredefinedEvents.php' => app_path('Models/TestModel.php'),
        ], 'history-tracking-models');
    }

    public function registeringPackage()
    {
        $this->app->bind(HistoryTracking::class);

        $this->app->scoped(LogBatch::class);

        $this->app->scoped(CauserResolver::class);

        $this->app->scoped(HistoryTrackingStatus::class);
    }

    public static function determineActivityModel(): string
    {
        $activityModel = config('activitylog.activity_model') ?? ActivityModel::class;

        if (! is_a($activityModel, Activity::class, true)
            || ! is_a($activityModel, Model::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }

        return $activityModel;
    }

    public static function getActivityModelInstance(): ActivityContract
    {
        $activityModelClassName = self::determineActivityModel();

        return new $activityModelClassName();
    }
}
