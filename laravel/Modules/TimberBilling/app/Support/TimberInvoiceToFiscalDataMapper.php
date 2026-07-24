<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Support;

use Illuminate\Support\Carbon;
use Modules\Fiscal\Datas\FiscalBuyer;
use Modules\Fiscal\Datas\FiscalInvoiceData;
use Modules\Fiscal\Datas\FiscalInvoiceLine;
use Modules\Fiscal\Datas\FiscalSeller;
use Modules\TimberBilling\Models\TimberInvoice;

/**
 * Maps a Timber invoice aggregate to the vertical-agnostic FiscalInvoiceData
 * consumed by the Fiscal e-invoice generator. All Eloquent and Timber-specific
 * config access is confined here; the Fiscal core stays pure.
 */
final class TimberInvoiceToFiscalDataMapper
{
    public function map(TimberInvoice $invoice): FiscalInvoiceData
    {
        $invoice->loadMissing(['lines', 'customer']);

        $number = is_string($invoice->number) && $invoice->number !== ''
            ? $invoice->number
            : (string) $invoice->id;

        $date = Carbon::parse($invoice->invoice_date)->format('Y-m-d');

        $lines = $invoice->lines
            ->map(fn ($line): FiscalInvoiceLine => new FiscalInvoiceLine(
                description: (string) $line->description,
                quantity: (float) $line->quantity,
                unitPrice: (float) $line->unit_price,
                discountPercent: (float) $line->discount_percent,
                vatRatePercent: (float) $line->vat_rate,
            ))
            ->all();

        return new FiscalInvoiceData(
            number: $number,
            date: $date,
            seller: $this->seller(),
            buyer: $this->buyer($invoice),
            lines: $lines,
        );
    }

    private function seller(): FiscalSeller
    {
        $config = config('timberbilling.company', []);
        $config = is_array($config) ? $config : [];

        $defaults = [
            'vat_number' => '00000000000',
            'name' => 'Azienda',
            'tax_regime' => 'RF01',
            'address' => '',
            'postal_code' => '',
            'city' => '',
            'province' => '',
            'country' => 'IT',
        ];

        $value = static function (string $key) use ($config, $defaults): string {
            $raw = $config[$key] ?? $defaults[$key];

            return is_scalar($raw) ? (string) $raw : $defaults[$key];
        };

        return new FiscalSeller(
            country: $value('country'),
            vatNumber: $value('vat_number'),
            name: $value('name'),
            taxRegime: $value('tax_regime'),
            address: $value('address'),
            postalCode: $value('postal_code'),
            city: $value('city'),
            province: $value('province'),
        );
    }

    private function buyer(TimberInvoice $invoice): FiscalBuyer
    {
        $customer = $invoice->customer;

        $name = ($customer !== null && is_string($customer->legal_name) && $customer->legal_name !== '')
            ? $customer->legal_name
            : '';

        $vatNumber = ($customer !== null && is_string($customer->vat_number) && $customer->vat_number !== '')
            ? $customer->vat_number
            : null;

        return new FiscalBuyer(
            name: $name,
            vatNumber: $vatNumber,
            sdiRecipientCode: null,
        );
    }
}
