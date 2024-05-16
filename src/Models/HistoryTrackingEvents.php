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

    CONST TYPE_AUTOMATIC = 1;
    CONST TYPE_MANUAL = 2;

    CONST GROUP_SYSTEM = 1;
    CONST GROUP_CUSTOM = 2;

    public static function type(): array
    {
        return [
            self::TYPE_AUTOMATIC => __('Automatic'),
            self::TYPE_MANUAL => __('Manual'),
        ];
    }

    public static function group(): array
    {
        return [
            self::GROUP_SYSTEM => __('System'),
            self::GROUP_CUSTOM => __('Custom'),
        ];
    }

    /*
     * Add key-value for each deployment if you know beforehand the custom events and run the command to generate them
     * ex: 'attended_business_advisory' => 'Person has attended business advisory service',
     */
    CONST GENERATE = [
        self::GROUP_SYSTEM => [

        ],
        self::GROUP_CUSTOM => [

        ],
    ];

    /**
     * Predefined system constants - has no owner
     */
    CONST PREDEFINED_SYSTEM_CREATED_ACCOUNT = 'user_created_account';
    CONST PREDEFINED_SYSTEM_ONBOARD_COMPLETE = 'user_completed_onboarding';
    CONST PREDEFINED_SYSTEM_UPLOADED_CV = 'user_uploaded_cv';
    CONST PREDEFINED_SYSTEM_UPDATED_PASSWORD = 'user_updated_password';

    /**
     * Predefined job constants - has owner
     */

    CONST PREDEFINED_JOB_APPLIED = 'user_applied_to_job';
    CONST PREDEFINED_JOB_INVITED = 'user_invited_to_job';
    CONST PREDEFINED_JOB_ACCEPTED = 'user_accepted_job';
    CONST PREDEFINED_JOB_MATCHED = 'user_matched_with_company';
    CONST PREDEFINED_JOB_REJECTED_USER = 'user_rejected_job';
    CONST PREDEFINED_JOB_REJECTED_COMPANY = 'company_rejected_user_application';
    CONST PREDEFINED_JOB_HIRED = 'company_hired_user';

    /**
     * Predefined courses constants - has owner
     */
    CONST PREDEFINED_COURSE_STARTED = 'user_started_course';
    CONST PREDEFINED_COURSE_COMPLETED = 'user_completed_course';
    CONST PREDEFINED_COURSES_COMPLETED = 'user_completed_all_courses';

    /**
     * Predefined kanban constants - has owner
     */
    CONST PREDEFINED_KANBAN_MOVED = 'user_moved_in_kanban';

    public function customEvents(): HasMany
    {
        return $this->hasMany(HistoryTrackingCustomEvents::class, 'type');
    }

    public static function findByGroup($group): HistoryTrackingEvents
    {
        return self::where('id', $group)->first();
    }
}
