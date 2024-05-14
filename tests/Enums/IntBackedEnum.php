<?php

namespace Jobful\HistoryTracking\Test\Enums;

enum IntBackedEnum: int
{
    case Published = 1;
    case Draft = 0;
}
