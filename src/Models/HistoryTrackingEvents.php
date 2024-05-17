<?php

namespace Jobful\HistoryTracking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Since we do not have specific models for entities that are not part of the project, this table holds only the category/group of custom events and system, so it can be used as polymorphic relationship, ex: system and custom
 * Other type of tracking events categories are stored as model in the polymorphic relationship, ex: Jobs, Courses, Kanban, etc
 * Predefined events below are used throughout the app. If you want extra, just add them here as constants
 */
class HistoryTrackingEvents extends Model
{
    protected $table = 'history_tracking_events';

    protected $fillable = [
        'name',
    ];

    CONST TYPE_CUSTOM = 1;

    public static function type(): array
    {
        return [
            self::TYPE_CUSTOM => __('Custom'),
        ];
    }

    /*
     * Add key-value for each deployment if you know beforehand the custom events and run the command to generate them
     * ex: 'attended_business_advisory' => 'Person has attended business advisory service',
     */
    CONST GENERATE = [
        self::TYPE_CUSTOM => [

        ],
    ];

    /**
     * Predefined system constants - has no owner
     */
    CONST PREDEFINED_SYSTEM_CREATED_ACCOUNT = 'user_created_account';
    CONST PREDEFINED_SYSTEM_PASSED_THRESHOLD = 'passed_threshold_cv';
    CONST PREDEFINED_USER_ACCOUNTLESS_CONVERTED_TO_ACCOUNT = 'user_accountless_converted_to_account';
    /**
     * Predefined job constants - has owner
     */

    CONST PREDEFINED_JOB_APPLIED = 'user_applied_to_job';
    CONST PREDEFINED_JOB_INVITED = 'user_invited_to_job';
    CONST PREDEFINED_JOB_MATCHED = 'user_matched_with_company';
    CONST PREDEFINED_JOB_DECLINED_INVITATION = 'user_declined_invitation';
    CONST PREDEFINED_JOB_COMPANY_DECLINED_APPLICATION = 'company_declined_application';

    /**
     * Predefined kanban constants - has owner
     */
    CONST PREDEFINED_KANBAN_HIRED = 'user_hired';
    CONST PREDEFINED_KANBAN_REJECTED = 'user_rejected';

    public function customEvents(): HasMany
    {
        return $this->hasMany(HistoryTrackingCustomEvents::class, 'type');
    }

    public static function findByType($type): HistoryTrackingEvents
    {
        return self::where('id', $type)->first();
    }
}
