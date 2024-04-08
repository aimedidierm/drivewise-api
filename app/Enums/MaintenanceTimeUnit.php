<?php

namespace App\Enums;

enum MaintenanceTimeUnit: string
{
    case MINUTE = 'minute';
    case HOUR = 'hour';
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
}
