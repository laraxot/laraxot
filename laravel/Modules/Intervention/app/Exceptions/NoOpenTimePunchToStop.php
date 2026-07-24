<?php

declare(strict_types=1);

namespace Modules\Intervention\Exceptions;

use DomainException;

final class NoOpenTimePunchToStop extends DomainException
{
    public static function forUserAndIntervention(int $userId, int $interventionId): self
    {
        return new self("No open time punch found for user {$userId} on intervention {$interventionId}.");
    }
}
