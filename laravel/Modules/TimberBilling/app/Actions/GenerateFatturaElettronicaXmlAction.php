<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Modules\Fiscal\Domain\Actions\GenerateFatturaElettronicaXmlAction as FiscalFatturaXmlGenerator;
use Modules\TimberBilling\Support\TimberInvoiceToFiscalDataMapper;
use Modules\TimberBilling\Models\TimberInvoice;

/**
 * Timber-facing adapter: maps a Timber invoice to the vertical-agnostic
 * FiscalInvoiceData and delegates the FatturaPA XML serialisation to the
 * shared Fiscal generator.
 */
final class GenerateFatturaElettronicaXmlAction
{
    public function __construct(
        private readonly TimberInvoiceToFiscalDataMapper $mapper,
        private readonly FiscalFatturaXmlGenerator $generator,
    ) {}

    public function execute(TimberInvoice $invoice): string
    {
        return $this->generator->execute($this->mapper->map($invoice));
    }
}
