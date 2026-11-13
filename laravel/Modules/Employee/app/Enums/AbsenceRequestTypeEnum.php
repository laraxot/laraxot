<?php

declare(strict_types=1);

namespace Modules\Employee\Enums;

enum AbsenceRequestTypeEnum: string
{
    case VACATION = 'vacation';
    case LEAVE = 'leave';
    case SICK = 'sick';
    case INJURY = 'injury';
}
