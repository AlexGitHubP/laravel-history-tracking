<?php

namespace Jobful\HistoryTracking\Models;

use Illuminate\Database\Eloquent\Model;

class PredefinedEvents extends Model
{
    /**
     * Predefined system constants - has no owner
     */
    CONST PREDEFINED_CREATED_ACCOUNT = 'user_created_account';
    CONST PREDEFINED_ONBOARD_COMPLETE = 'user_completed_onboarding';
    CONST PREDEFINED_UPLOADED_CV = 'user_uploaded_cv';

    /**
     * Predefined job constants - has owner
     */
    CONST PREDEFINED_JOB_INVITED = 'user_invited_to_job';
    CONST PREDEFINED_JOB_ACCEPTED = 'user_accepted_job';
    CONST PREDEFINED_JOB_MATCHED = 'user_matched_with_company';
    CONST PREDEFINED_JOB_REJECTED_USER = 'user_rejected_job';
    CONST PREDEFINED_JOB_REJECTED_COMPANY = 'company_rejected_user_application';

    /**
     * Predefined courses constants - has owner
     */
    CONST PREDEFINED_STARTED_COURSE = 'user_started_course';
    CONST PREDEFINED_COURSE_COMPLETED = 'user_completed_course';
    CONST PREDEFINED_COURSES_COMPLETED = 'user_completed_all_courses';

}
