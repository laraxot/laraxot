<?php

declare(strict_types=1);

namespace Modules\Wts\Mail;

use Modules\Wts\Models\WtsNonConformityReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class WtsNonConformityReportedMail extends Mailable
{
    use Queueable;

    public function __construct(
        public readonly WtsNonConformityReport $report,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Segnalazione non conformita WTS '.($this->report->form_number ?? 'senza formulario'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'wts::mail.non-conformity-reported',
        );
    }
}
