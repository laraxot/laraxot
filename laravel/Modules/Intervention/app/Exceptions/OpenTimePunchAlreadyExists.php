<?php

declare(strict_types=1);

namespace Modules\Intervention\Exceptions;

use DomainException;

final class OpenTimePunchAlreadyExists extends DomainException
{
    public static function forUser(int|string $userId): self
    {
        return new self("User {$userId} already has an open time punch.");
    }
}
