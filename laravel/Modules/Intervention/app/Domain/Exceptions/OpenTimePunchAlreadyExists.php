<?php

declare(strict_types=1);

namespace Modules\Intervention\Domain\Exceptions;

use DomainException;

final class OpenTimePunchAlreadyExists extends DomainException
{
    public static function forUser(int $userId): self
    {
        return new self("User {$userId} already has an open time punch.");
    }
}
