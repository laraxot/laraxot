<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Modules\Fiscal\Actions\GenerateFatturaElettronicaXmlAction as FiscalFatturaXmlGenerator;
use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Support\TimberInvoiceToFiscalDataMapper;
use Spatie\QueueableAction\QueueableAction;

/**
 * Timber-facing adapter: maps a Timber invoice to the vertical-agnostic
 * FiscalInvoiceData and delegates the FatturaPA XML serialisation to the
 * shared Fiscal generator.
 */
final class GenerateFatturaElettronicaXmlAction
{
    use QueueableAction;

    public function __construct(
        private readonly TimberInvoiceToFiscalDataMapper $mapper,
        private readonly FiscalFatturaXmlGenerator $generator,
    ) {}

    public function execute(TimberInvoice $invoice): string
    {
        return $this->generator->execute($this->mapper->map($invoice));
    }
}
