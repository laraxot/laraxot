<?php

declare(strict_types=1);

namespace Modules\Timber\Domain\DTO;

use Modules\Quotation\DTO\Money;

final readonly class AgendaEvent
{
    public function __construct(
        public string $date,
        public string $type,
        public string $title,
        public ?Money $amount,
    ) {}
}
