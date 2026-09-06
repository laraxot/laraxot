<?php

declare(strict_types=1);

namespace Modules\Timber\Queries;

use Illuminate\Support\Carbon;
use Modules\Quotation\DTO\Money;
use Modules\Timber\Domain\DTO\AgendaEvent;
use Modules\Timber\Models\TimberDocument;
use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Models\TimberPaymentInstallment;

/**
 * Agenda: raccoglie in un unico elenco ordinato per data gli eventi commerciali
 * del periodo — vendite (fatture), scadenze di pagamento e documenti di magazzino.
 */
final class GetTimberAgendaQuery
{
    /**
     * @return array<int, AgendaEvent>
     */
    public function execute(string $fromDate, string $toDate): array
    {
        $events = [];

        foreach (TimberInvoice::query()
            ->whereIn('status_code', ['issued', 'paid'])
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->with('customer')
            ->get() as $invoice) {
            /** @var string $customerName */
            $customerName = $invoice->customer->legal_name ?? '';
            $invoiceNumber = is_string($invoice->number) ? $invoice->number : (string) ($invoice->number ?? '');
            $events[] = new AgendaEvent(
                date: Carbon::parse($invoice->invoice_date)->format('Y-m-d'),
                type: 'Vendita',
                title: trim(sprintf('%s %s %s', $invoice->kind, $invoiceNumber, $customerName)),
                amount: Money::fromFloat((float) $invoice->total_amount),
            );
        }

        foreach (TimberPaymentInstallment::query()
            ->whereBetween('due_date', [$fromDate, $toDate])
            ->with('customer')
            ->get() as $installment) {
            /** @var string $customerName */
            $customerName = $installment->customer->legal_name ?? '';
            $paymentMethod = is_string($installment->payment_method_code) ? $installment->payment_method_code : '';
            $events[] = new AgendaEvent(
                date: Carbon::parse($installment->due_date)->format('Y-m-d'),
                type: $installment->direction === 'out' ? 'Pagamento' : 'Scadenza incasso',
                title: trim(sprintf('%s %s', $paymentMethod, $customerName)),
                amount: Money::fromFloat((float) $installment->amount),
            );
        }

        foreach (TimberDocument::query()
            ->whereBetween('document_date', [$fromDate, $toDate])
            ->get() as $document) {
            $events[] = new AgendaEvent(
                date: Carbon::parse($document->document_date)->format('Y-m-d'),
                type: 'Documento',
                title: trim(sprintf('%s %s', is_string($document->document_type_code) ? $document->document_type_code : '', is_string($document->number) ? $document->number : (string) ($document->number ?? ''))),
                amount: Money::fromFloat((float) $document->total_amount),
            );
        }

        usort($events, static fn (AgendaEvent $a, AgendaEvent $b): int => [$a->date, $a->type] <=> [$b->date, $b->type]);

        return $events;
    }
}
