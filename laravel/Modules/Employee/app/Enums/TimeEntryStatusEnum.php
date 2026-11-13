<?php

declare(strict_types=1);

namespace Modules\Employee\Enums;

enum TimeEntryStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case AUTO_APPROVED = 'auto_approved';
    case REJECTED = 'rejected';
}
