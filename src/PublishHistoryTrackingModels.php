<?php

namespace Jobful\HistoryTracking;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishHistoryTrackingModels extends Command
{
    protected $signature = 'laravel-history-tracking:publish-models';
    protected $description = 'Publish history tracking models';

    public function handle()
    {
        // Define the source and destination for HistoryTrackingEvents model
        $historySource = __DIR__ . '/Models/History.php';
        $historyDestinationDir = app_path('Models/HistoryTracking');
        $historyDestination = $historyDestinationDir . '/History.php';

        // Define the source and destination for HistoryTrackingEvents model
        $historyTrackingEventsSource = __DIR__ . '/Models/HistoryTrackingEvents.php';
        $historyTrackingEventsDestinationDir = app_path('Models/HistoryTracking');
        $historyTrackingEventsDestination = $historyTrackingEventsDestinationDir . '/HistoryTrackingEvents.php';

        // Define the source and destination for JobfulSystem model
        $historyTrackingCustomEventsSource = __DIR__ . '/Models/HistoryTrackingCustomEvents.php';
        $historyTrackingCustomEventsDestinationDir = app_path('Models/HistoryTracking');
        $historyTrackingCustomEventsDestination = $historyTrackingCustomEventsDestinationDir . '/HistoryTrackingCustomEvents.php';

        // Ensure the destination directory exists
        if (!File::exists($historyDestinationDir)) {
            File::makeDirectory($historyDestinationDir, 0755, true);
        }

        // Publish History model
        $this->publishModel($historySource, $historyDestination);

        // Publish HistoryTrackingEvents model
        $this->publishModel($historyTrackingEventsSource, $historyTrackingEventsDestination);

        // Publish HistoryTrackingCustomEvents model
        $this->publishModel($historyTrackingCustomEventsSource, $historyTrackingCustomEventsDestination);

        $this->info('History tracking modules published and namespaces updated.');
    }

    protected function publishModel($source, $destination)
    {
        $content = File::get($source);
        $content = str_replace('namespace Jobful\HistoryTracking\Models;', 'namespace App\Models\HistoryTracking;', $content);
        File::put($destination, $content);
    }
}
