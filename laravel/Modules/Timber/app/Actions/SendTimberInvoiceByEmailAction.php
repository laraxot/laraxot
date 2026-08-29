<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Facades\Mail;
use Modules\Timber\Mail\TimberInvoiceMail;
use Modules\Timber\Models\TimberInvoice;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Invia via email al cliente il documento fiscale con il PDF in allegato.
 */
final class SendTimberInvoiceByEmailAction
{
    use QueueableAction;

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
        $kinds = config('timber.invoice_kinds', []);
        $label = is_array($kinds) && isset($kinds[$invoice->kind]) && is_string($kinds[$invoice->kind])
            ? $kinds[$invoice->kind]
            : 'Documento';

        Mail::to($email)->send(new TimberInvoiceMail($label, (string) ($invoice->number ?? (string) $invoice->id), $pdf));

        return $email;
    }
}
