<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { border-bottom: 2px solid #111827; margin-bottom: 12px; padding-bottom: 8px; }
        .title { font-size: 18px; font-weight: bold; }
        .meta { margin-top: 6px; color: #374151; }
        .box { border: 1px solid #e5e7eb; padding: 8px; border-radius: 4px; margin-bottom: 10px; }
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { vertical-align: top; width: 50%; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.items th, table.items td { border-bottom: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        table.items th { background: #f9fafb; }
        .right { text-align: right; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">
            {{ __('billing.invoice') }} {{ $invoice->number ?: ('#' . $invoice->id) }}
        </div>
        <div class="meta">
            {{ __('billing.fields.issued_on') }}: {{ optional($invoice->issued_on)->format('d/m/Y') ?: '-' }}
            — {{ __('billing.fields.customer') }}: {{ $customerName }}
        </div>
    </div>

    <table class="grid">
        <tr>
            <td style="padding-right: 8px;">
                <div class="box">
                    @if (!empty($logo))
                        <img src="{{ $logo }}" alt="" style="max-height: 48px; margin-bottom: 6px;">
                    @endif
                    <div><strong>{{ __('billing.sections.supplier') }}</strong></div>
                    <div style="margin-top: 6px;">
                        <div>{{ $supplier['legal_name'] ?? '' }}</div>
                        <div class="muted">{{ $supplier['address'] ?? '' }}</div>
                        <div class="muted">
                            {{ __('billing.fields.vat_number') }}: {{ $supplier['vat_number'] ?? '-' }}
                            @if (!empty($supplier['tax_code']))
                                — {{ __('billing.fields.tax_code') }}: {{ $supplier['tax_code'] }}
                            @endif
                        </div>
                        <div class="muted">
                            {{ __('billing.fields.phone') }}: {{ $supplier['phone'] ?? '-' }}
                            @if (!empty($supplier['email']))
                                — {{ __('billing.fields.email') }}: {{ $supplier['email'] }}
                            @endif
                        </div>
                    </div>
                </div>
            </td>
            <td style="padding-left: 8px;">
                <div class="box">
                    <div><strong>{{ __('billing.sections.customer') }}</strong></div>
                    <div style="margin-top: 6px;">
                        <div>{{ $customerName }}</div>
                        @if ($customer)
                            <div class="muted">
                                @php
                                    $addr = trim((string) ($customer->address_line1 ?? ''));
                                    $addr2 = trim((string) ($customer->address_line2 ?? ''));
                                    $zip = trim((string) ($customer->postal_code ?? ''));
                                    $city = trim((string) ($customer->city ?? ''));
                                    $prov = trim((string) ($customer->province ?? ''));
                                @endphp
                                {{ $addr }}@if($addr2) — {{ $addr2 }}@endif
                                @if($zip || $city || $prov)
                                    — {{ trim("{$zip} {$city} {$prov}") }}
                                @endif
                            </div>
                            <div class="muted">
                                {{ __('billing.fields.vat_number') }}: {{ $customer->vat_number ?: '-' }}
                                @if ($customer->tax_code)
                                    — {{ __('billing.fields.tax_code') }}: {{ $customer->tax_code }}
                                @endif
                            </div>
                            <div class="muted">
                                {{ __('billing.fields.phone') }}: {{ $customer->phone ?: '-' }}
                                @if ($customer->email)
                                    — {{ __('billing.fields.email') }}: {{ $customer->email }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="box">
        <div>
            <strong>{{ __('billing.fields.number') }}:</strong>
            {{ $invoice->number ?: ('#' . $invoice->id) }}
        </div>
        <div>
            <strong>{{ __('billing.fields.total') }}:</strong>
            {{ number_format((float) $invoice->total, 2, ',', '.') }} EUR
        </div>
        @if (!empty($linkedWorkOrders))
            <div style="margin-top: 6px;">
                <strong>{{ __('billing.sections.work_orders_linked') }}:</strong>
                {{ implode(', ', $linkedWorkOrders) }}
            </div>
        @elseif ($workOrderCode)
            <div style="margin-top: 6px;">
                <strong>{{ __('billing.fields.work_order') }}:</strong>
                {{ $workOrderCode }}
            </div>
        @endif
    </div>

    {{-- Placeholder semplice: ad oggi la fattura non ha righe. --}}
    <table class="items">
        <thead>
        <tr>
            <th>{{ __('billing.fields.number') }}</th>
            <th>{{ __('billing.fields.customer') }}</th>
            <th>{{ __('billing.fields.work_orders') }}</th>
            <th class="right">{{ __('billing.fields.total') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $invoice->number ?: ('#' . $invoice->id) }}</td>
            <td>{{ $customerName }}</td>
            <td>{{ !empty($linkedWorkOrders) ? implode(', ', $linkedWorkOrders) : ($workOrderCode ?: '-') }}</td>
            <td class="right">{{ number_format((float) $invoice->total, 2, ',', '.') }}</td>
        </tr>
        </tbody>
    </table>

    @if($invoice->notes)
        <div class="box">
            <strong>{{ __('billing.fields.notes') }}:</strong><br>
            {!! nl2br(e($invoice->notes)) !!}
        </div>
    @endif

    @if($invoice->electronic_invoice_sent_at)
        <div class="box muted">
            {{ __('billing.fields.einvoice_sent_at') }}:
            {{ optional($invoice->electronic_invoice_sent_at)->format('d/m/Y H:i') ?: '-' }}
            @if($invoice->electronic_invoice_external_id)
                — {{ __('billing.fields.einvoice_external_id') }}: {{ $invoice->electronic_invoice_external_id }}
            @endif
        </div>
    @endif
</body>
</html>

