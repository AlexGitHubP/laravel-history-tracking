<?php

namespace Jobful\HistoryTracking;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Jobful\HistoryTracking\Contracts\Activity as ActivityContract;

class HistoryTracking
{
    use Conditionable;
    use Macroable;

    protected ?int $defaultTrackerType = null;

    protected CauserResolver $causerResolver;

    protected HistoryTrackingStatus $logStatus;

    protected ?ActivityContract $activity = null;

    protected LogBatch $batch;

    public function __construct(Repository $config, HistoryTrackingStatus $logStatus, LogBatch $batch, CauserResolver $causerResolver)
    {
        $this->causerResolver = $causerResolver;

        $this->batch = $batch;

        $this->defaultTrackerType = $config['history-tracking']['default_tracker_type'];

        $this->logStatus = $logStatus;
    }

    public function setLogStatus(HistoryTrackingStatus $logStatus): static
    {
        $this->logStatus = $logStatus;

        return $this;
    }

    public function performedOn(Model $model): static
    {
        $this->getActivity()->subject()->associate($model);

        return $this;
    }

    public function on(Model $model): static
    {
        return $this->performedOn($model);
    }

    public function causedBy(Model | int | string | null $modelOrId): static
    {
        if ($modelOrId === null) {
            return $this;
        }

        $model = $this->causerResolver->resolve($modelOrId);

        $this->getActivity()->causer()->associate($model);

        return $this;
    }

    public function trackedBy(Model | int | string | null $modelOrId): static
    {
        if ($modelOrId === null) {
            return $this;
        }

        $model = $this->causerResolver->resolve($modelOrId);

        $this->getActivity()->trackable()->associate($model);

        return $this;
    }

    public function by(Model | int | string | null $modelOrId): static
    {
        return $this->causedBy($modelOrId);
    }

    public function causedByAnonymous(): static
    {
        $this->activity->causer_id = null;
        $this->activity->causer_type = null;

        return $this;
    }

    public function byAnonymous(): static
    {
        return $this->causedByAnonymous();
    }

    public function event(string $event): static
    {
        return $this->setEvent($event);
    }

    public function setEvent(string $event): static
    {
        $this->activity->event = $event;

        return $this;
    }

    public function withProperties(mixed $properties): static
    {
        $this->getActivity()->properties = collect($properties);

        return $this;
    }

    public function withProperty(string $key, mixed $value): static
    {
        $this->getActivity()->properties = $this->getActivity()->properties->put($key, $value);

        return $this;
    }

    public function createdAt(DateTimeInterface $dateTime): static
    {
        $this->getActivity()->created_at = Carbon::instance($dateTime);

        return $this;
    }

    public function useTrackerType(?string $trackerType): static
    {
        $this->getActivity()->tracker_type = $trackerType;

        return $this;
    }

    public function ownedBy($owner = null): static
    {
        $this->activity->owner = ($owner) ? $owner->getKey() : null;

        return $this;
    }

    public function type($type = null): static
    {
        $this->activity->type = $type;

        return $this;
    }

    public function performedAt(DateTimeInterface $dateTime): static
    {
        $this->getActivity()->performed_at = Carbon::instance($dateTime);

        return $this;
    }

    public function inTrackerType(?string $trackerType): static
    {
        return $this->useTrackerType($trackerType);
    }

    public function tap(callable $callback, string $eventName = null): static
    {
        call_user_func($callback, $this->getActivity(), $eventName);

        return $this;
    }

    public function enableLogging(): static
    {
        $this->logStatus->enable();

        return $this;
    }

    public function disableLogging(): static
    {
        $this->logStatus->disable();

        return $this;
    }

    public function log(string $description): ?ActivityContract
    {
        if ($this->logStatus->disabled()) {
            return null;
        }

        $activity = $this->activity;

        $activity->description = $this->replacePlaceholders(
            $activity->description ?? $description,
            $activity
        );

        if (isset($activity->subject) && method_exists($activity->subject, 'tapActivity')) {
            $this->tap([$activity->subject, 'tapActivity'], $activity->event ?? '');
        }

        $activity->save();

        $this->activity = null;

        return $activity;
    }

    public function withoutLogs(Closure $callback): mixed
    {
        if ($this->logStatus->disabled()) {
            return $callback();
        }

        $this->logStatus->disable();

        try {
            return $callback();
        } finally {
            $this->logStatus->enable();
        }
    }

    protected function replacePlaceholders(string $description, ActivityContract $activity): string
    {
        return preg_replace_callback('/:[a-z0-9._-]+(?<![.])/i', function ($match) use ($activity) {
            $match = $match[0];

            $attribute = Str::before(Str::after($match, ':'), '.');

            if (! in_array($attribute, ['subject', 'causer', 'properties'])) {
                return $match;
            }

            $propertyName = substr($match, strpos($match, '.') + 1);

            $attributeValue = $activity->$attribute;

            if (is_null($attributeValue)) {
                return $match;
            }

            return data_get($attributeValue, $propertyName, $match);
        }, $description);
    }

    protected function getActivity(): ActivityContract
    {
        if (! $this->activity instanceof ActivityContract) {
            $this->activity = HistoryTrackingServiceProvider::getActivityModelInstance();
            $this
                ->useTrackerType($this->defaultTrackerType)
                ->withProperties([])
                ->causedBy($this->causerResolver->resolve());
        }

        return $this->activity;
    }
}
