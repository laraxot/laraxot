<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class TimberInvoiceMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $documentLabel,
        public string $documentNumber,
        private readonly string $pdf,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->documentLabel.' '.$this->documentNumber,
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '<p>In allegato trova il documento <strong>'.e($this->documentNumber).'</strong>.</p>',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn (): string => $this->pdf, 'documento-'.$this->documentNumber.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
