<?php

namespace Jobful\HistoryTracking\Test\Enums;

enum StringBackedEnum: string
{
    case Published = 'published';
    case Draft = 'draft';
}
