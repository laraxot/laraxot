<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Domain\DTO;

use Modules\Quotation\Domain\ValueObjects\Money;

final readonly class AgendaEvent
{
    public function __construct(
        public string $date,
        public string $type,
        public string $title,
        public ?Money $amount,
    ) {}
}
