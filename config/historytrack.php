<?php

return [

    /*
     * If set to false, no activities will be saved to the database.
     */
    'enabled' => env('HISTORY_LOGGER_ENABLED', true),

    /*
     * If set to true, you can define custom events in admin.
     */
    'custom_tracking' => env('HISTORY_CUSTOM_TRACKING_ENABLED', false),

    /*
     * When the clean-command is executed, all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * If no log name is passed to the activity() helper
     * we use this default log name.
     */
    'default_tracker_type' => \Jobful\HistoryTracking\Models\History::TYPE_AUTOMATED,

    /*
     * You can specify an auth driver here that gets user models.
     * If this is null we'll use the current Laravel auth driver.
     */
    'default_auth_driver' => null,

    /*
     * If set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    /*
     * This model will be used to log activity.
     * It should implement the Jobful\History\Contracts\History interface
     * and extend Illuminate\Database\Eloquent\Model.
     */
    'history_model' => \Jobful\HistoryTracking\Models\History::class,

    /*
     * This is the name of the table that will be created by the migration and
     * used by the History model shipped with this package.
     */
    'table_name' => 'history_tracking',

    /*
     * This is the database connection that will be used by the migration and
     * the History model shipped with this package. In case it's not set
     * Laravel's database.default will be used instead.
     */
    'database_connection' => env('HISTORY_LOGGER_DB_CONNECTION'),
];
