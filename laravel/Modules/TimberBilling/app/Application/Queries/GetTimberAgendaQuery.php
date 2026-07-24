<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use App\Timber\Models\TimberDocument;
use Illuminate\Support\Carbon;
use Modules\Quotation\DTO\Money;
use Modules\TimberBilling\Domain\DTO\AgendaEvent;
use Modules\TimberBilling\Models\TimberInvoice;
use Modules\TimberBilling\Models\TimberPaymentInstallment;

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
            $events[] = new AgendaEvent(
                date: Carbon::parse($invoice->invoice_date)->format('Y-m-d'),
                type: 'Vendita',
                title: trim(sprintf('%s %s %s', $invoice->kind, (string) $invoice->number, (string) ($invoice->customer->business_name ?? ''))),
                amount: Money::fromFloat((float) $invoice->total_amount),
            );
        }

        foreach (TimberPaymentInstallment::query()
            ->whereBetween('due_date', [$fromDate, $toDate])
            ->with('customer')
            ->get() as $installment) {
            $events[] = new AgendaEvent(
                date: Carbon::parse($installment->due_date)->format('Y-m-d'),
                type: $installment->direction === 'out' ? 'Pagamento' : 'Scadenza incasso',
                title: trim(sprintf('%s %s', (string) $installment->payment_method_code, (string) ($installment->customer->business_name ?? ''))),
                amount: Money::fromFloat((float) $installment->amount),
            );
        }

        foreach (TimberDocument::query()
            ->whereBetween('document_date', [$fromDate, $toDate])
            ->get() as $document) {
            $events[] = new AgendaEvent(
                date: Carbon::parse($document->document_date)->format('Y-m-d'),
                type: 'Documento',
                title: trim(sprintf('%s %s', (string) $document->document_type_code, (string) $document->number)),
                amount: Money::fromFloat((float) $document->total_amount),
            );
        }

        usort($events, static fn (AgendaEvent $a, AgendaEvent $b): int => [$a->date, $a->type] <=> [$b->date, $b->type]);

        return $events;
    }
}
