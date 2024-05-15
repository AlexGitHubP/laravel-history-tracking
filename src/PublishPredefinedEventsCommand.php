<?php

namespace Jobful\HistoryTracking;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishPredefinedEventsCommand extends Command
{

    protected $signature = 'jobful:publish-model';
    protected $description = 'Publish predefined history tracking events';

    public function handle()
    {
        $source = __DIR__ . '/Models/PredefinedEvents.php';
        $destinationDir = app_path('Models/HistoryTracking');
        $destination = $destinationDir . '/PredefinedEvents.php';

        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        $content = File::get($source);
        $content = str_replace('namespace Jobful\HistoryTracking\Models;', 'namespace App\Models\HistoryTracking;', $content);

        File::put($destination, $content);

        $this->info('Model published and namespace updated successfully.');
    }
}
