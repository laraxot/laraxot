<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Illuminate\Support\Facades\Mail;
use Modules\TimberBilling\Mail\TimberInvoiceMail;
use Modules\TimberBilling\Models\TimberInvoice;
use RuntimeException;

/**
 * Invia via email al cliente il documento fiscale con il PDF in allegato.
 */
final class SendTimberInvoiceByEmailAction
{
    public function __construct(
        private readonly GenerateTimberInvoicePdfAction $generatePdf,
    ) {}

    public function execute(TimberInvoice $invoice): string
    {
        $invoice->loadMissing('customer');
        $email = $invoice->customer !== null ? trim((string) ($invoice->customer->email ?? '')) : '';

        if ($email === '') {
            throw new RuntimeException('Il cliente non ha un indirizzo email.');
        }

        $pdf = $this->generatePdf->execute($invoice);
        $kinds = config('timberbilling.invoice_kinds', []);
        $label = is_array($kinds) && isset($kinds[$invoice->kind]) && is_string($kinds[$invoice->kind])
            ? $kinds[$invoice->kind]
            : 'Documento';

        Mail::to($email)->send(new TimberInvoiceMail($label, (string) ($invoice->number ?? (string) $invoice->id), $pdf));

        return $email;
    }
}
