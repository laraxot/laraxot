<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Modules\TimberBilling\Models\TimberEInvoice;
use Modules\TimberBilling\Models\TimberInvoice;
use RuntimeException;

/**
 * Prepara la fattura elettronica di una fattura: genera l'XML FatturaPA e crea
 * (o aggiorna) il record e-fattura in stato "da inviare".
 */
final class PrepareTimberEInvoiceAction
{
    public function __construct(
        private readonly GenerateFatturaElettronicaXmlAction $generateXml,
    ) {}

    public function execute(TimberInvoice $invoice): TimberEInvoice
    {
        if ($invoice->kind !== 'fattura') {
            throw new RuntimeException('Solo le fatture generano una fattura elettronica.');
        }

        if ($invoice->issued_at === null) {
            throw new RuntimeException('Emetti la fattura prima di generare la fattura elettronica.');
        }

        $xml = $this->generateXml->execute($invoice);
        $vat = config('timberbilling.company.vat_number', '00000000000');
        $progressive = strtoupper(str_pad(base_convert((string) $invoice->id, 10, 36), 5, '0', STR_PAD_LEFT));
        $fileName = sprintf('IT%s_%s.xml', is_scalar($vat) ? (string) $vat : '00000000000', $progressive);

        /** @var TimberEInvoice $eInvoice */
        $eInvoice = TimberEInvoice::query()->updateOrCreate(
            ['invoice_id' => $invoice->getKey()],
            [
                'direction' => 'out',
                'sdi_status' => 'da_inviare',
                'xml_content' => $xml,
                'file_name' => $fileName,
                'transmitted_at' => null,
                'delivered_at' => null,
                'error_message' => null,
            ],
        );

        return $eInvoice;
    }
}
