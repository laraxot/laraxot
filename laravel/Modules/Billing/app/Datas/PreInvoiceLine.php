<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

/**
 * Riga proposta per la fattura, con l'origine da cui e' stata ricavata.
 */
final readonly class PreInvoiceLine
{
    /** @var string */
    /** @var string */
    public const SOURCE_LABOR = 'labor';

    /** @var string */
    /** @var string */
    public const SOURCE_CALLOUT = 'callout';

    /** @var string */
    /** @var string */
    public const SOURCE_MATERIAL = 'material';

    /** @var string */
    /** @var string */
    public const SOURCE_EXTRA = 'extra';

    public function __construct(
        public string $description,
        public float $amount,
        public string $sourceCode,
    ) {}
}
