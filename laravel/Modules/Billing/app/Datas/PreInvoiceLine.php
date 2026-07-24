<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

/**
 * Riga proposta per la fattura, con l'origine da cui e' stata ricavata.
 */
final readonly class PreInvoiceLine
{
    public const SOURCE_LABOR = 'labor';

    public const SOURCE_CALLOUT = 'callout';

    public const SOURCE_MATERIAL = 'material';

    public const SOURCE_EXTRA = 'extra';

    public function __construct(
        public string $description,
        public float $amount,
        public string $sourceCode,
    ) {}
}
