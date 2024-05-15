<?php

namespace Jobful\HistoryTracking\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Jobful\HistoryTracking\Contracts\Activity as ActivityContract;

/**
 * Jobful\History\Models\History.
 *
 * @property int $id
 * @property int|null $trackerType
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $event
 * @property \Illuminate\Support\Collection|null $properties
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $causer
 * @property-read \Illuminate\Support\Collection $changes
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History forBatch(string $batchUuid)
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History forEvent(string $event)
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History hasBatch()
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History inTrackerType($trackerType)
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Jobful\HistoryTracking\Models\History query()
 */
class History extends Model implements ActivityContract
{
    const TYPE_AUTOMATED = 1;
    const TYPE_MANUAL = 2;


    public static function type(): array
    {
        return [
            self::TYPE_AUTOMATED => __('Automatic'),
            self::TYPE_MANUAL => __('Manual'),
        ];
    }

    public $guarded = [];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('historytrack.database_connection'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('historytrack.table_name'));
        }

        parent::__construct($attributes);
    }

    public function subject(): MorphTo
    {
        if (config('historytrack.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }

        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->properties->toArray(), $propertyName, $defaultValue);
    }

    public function changes(): Collection
    {
        if (! $this->properties instanceof Collection) {
            return new Collection();
        }

        return $this->properties->only(['attributes', 'old']);
    }

    public function getChangesAttribute(): Collection
    {
        return $this->changes();
    }

    public function scopeInType(Builder $query, ...$trackerTypes): Builder
    {
        if (is_array($trackerTypes[0])) {
            $trackerTypes = $trackerTypes[0];
        }

        return $query->whereIn('tracker_type', $trackerTypes);
    }

    public function scopeCausedBy(Builder $query, Model $causer): Builder
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    public function scopeForEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }
}
