<?php

declare(strict_types=1);

namespace Modules\Employee\Enums;

enum AbsenceRequestStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
