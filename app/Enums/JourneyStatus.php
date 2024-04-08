<?php

namespace App\Enums;

enum JourneyStatus: string
{
    case PENDING = 'pending';
    case STARTED = 'started';
    case ENDED = 'ended';
}
