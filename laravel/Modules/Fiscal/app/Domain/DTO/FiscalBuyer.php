<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

/**
 * Cessionario/committente (buyer) fiscal identity for an e-invoice.
 *
 * Raw values as held by the vertical: the FatturaPA formatting rules
 * (missing VAT, "0000000" recipient code, empty denomination) are applied
 * by the XML generator, not here.
 */
final readonly class FiscalBuyer
{
    public function __construct(
        public string $name,
        public ?string $vatNumber = null,
        public ?string $sdiRecipientCode = null,
    ) {}
}
