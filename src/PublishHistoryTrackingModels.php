<?php

namespace Jobful\HistoryTracking;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishHistoryTrackingModels extends Command
{
    protected $signature = 'history-tracking:publish-models';
    protected $description = 'Publish history tracking models';

    public function handle()
    {
        // Define the source and destination for HistoryTrackingEvents model
        $historyTrackingEventsSource = __DIR__ . '/Models/HistoryTrackingEvents.php';
        $historyTrackingEventsDestinationDir = app_path('Models/HistoryTracking');
        $historyTrackingEventsDestination = $historyTrackingEventsDestinationDir . '/HistoryTrackingEvents.php';

        // Define the source and destination for HistoryTrackingCustomEvents model
        $historyTrackingCustomEventsSource = __DIR__ . '/Models/HistoryTrackingCustomEvents.php';
        $historyTrackingCustomEventsDestinationDir = app_path('Models/HistoryTracking');
        $historyTrackingCustomEventsDestination = $historyTrackingCustomEventsDestinationDir . '/HistoryTrackingCustomEvents.php';

        // Ensure the destination directory exists
        if (!File::exists($historyTrackingEventsDestinationDir)) {
            File::makeDirectory($historyTrackingEventsDestinationDir, 0755, true);
        }

        // Publish HistoryTrackingEvents model
        $this->publishModel($historyTrackingEventsSource, $historyTrackingEventsDestination);

        // Publish HistoryTrackingCustomEvents model
        $this->publishModel($historyTrackingCustomEventsSource, $historyTrackingCustomEventsDestination);

        $this->info('History tracking models published and namespaces updated.');
    }

    protected function publishModel($source, $destination)
    {
        $content = File::get($source);
        $content = str_replace('namespace Jobful\HistoryTracking\Models;', 'namespace App\Models\HistoryTracking;', $content);
        File::put($destination, $content);
    }
}
