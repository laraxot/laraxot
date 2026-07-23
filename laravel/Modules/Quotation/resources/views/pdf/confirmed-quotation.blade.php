<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { border-bottom: 2px solid #111827; margin-bottom: 12px; padding-bottom: 8px; }
        .title { font-size: 18px; font-weight: bold; }
        .meta { margin-top: 6px; color: #374151; }
        .box { border: 1px solid #e5e7eb; padding: 8px; border-radius: 4px; margin-bottom: 10px; }
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
            {{ __('quotations.confirmed_quotation') }} {{ $quotation->code ?: ('#' . $quotation->id) }}
        </div>
        <div class="meta">
            {{ __('quotations.fields.customer') }}: {{ $customerName }}
            @if($workOrderCode)
                — {{ __('quotations.fields.work_order') }}: {{ $workOrderCode }}
            @endif
        </div>
    </div>

    <div class="box">
        <div class="muted">
            {{ __('quotations.fields.confirmed_at') }}: {{ optional($quotation->confirmed_at)->format('Y-m-d H:i') ?: '-' }}
            — {{ __('quotations.fields.issued_at') }}: {{ optional($quotation->issued_at)->format('Y-m-d H:i') ?: '-' }}
        </div>
        @if($quotation->title)
            <div style="margin-top: 6px;"><strong>{{ __('quotations.fields.title') }}:</strong> {{ $quotation->title }}</div>
        @endif
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>{{ __('quotations.fields.description') }}</th>
                <th class="right" style="width: 70px;">{{ __('quotations.fields.quantity') }}</th>
                <th class="right" style="width: 90px;">{{ __('quotations.fields.unit_price') }}</th>
                <th class="right" style="width: 80px;">{{ __('quotations.fields.discount_percent') }}</th>
                <th class="right" style="width: 90px;">{{ __('quotations.fields.line_total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items->sortBy('sort_order') as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ number_format((float) $item->quantity, 2, '.', '') }}</td>
                    <td class="right">{{ number_format((float) $item->unit_price, 2, '.', '') }}</td>
                    <td class="right">{{ number_format((float) $item->discount_percent, 2, '.', '') }}%</td>
                    <td class="right">{{ number_format((float) $item->line_total, 2, '.', '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="box" style="margin-top: 10px;">
        <table style="width:100%;">
            <tr>
                <td class="right"><strong>{{ __('quotations.fields.subtotal') }}:</strong></td>
                <td class="right" style="width: 100px;">{{ number_format((float) $quotation->subtotal, 2, '.', '') }}</td>
            </tr>
            <tr>
                <td class="right"><strong>{{ __('quotations.fields.discount_amount') }}:</strong></td>
                <td class="right">{{ number_format((float) $quotation->discount_amount, 2, '.', '') }}</td>
            </tr>
            <tr>
                <td class="right"><strong>{{ __('quotations.fields.taxable_amount') }}:</strong></td>
                <td class="right">{{ number_format((float) $quotation->taxable_amount, 2, '.', '') }}</td>
            </tr>
            <tr>
                <td class="right"><strong>{{ __('quotations.fields.tax_percent') }}:</strong></td>
                <td class="right">{{ number_format((float) $quotation->tax_percent, 2, '.', '') }}%</td>
            </tr>
            <tr>
                <td class="right"><strong>{{ __('quotations.fields.tax_amount') }}:</strong></td>
                <td class="right">{{ number_format((float) $quotation->tax_amount, 2, '.', '') }}</td>
            </tr>
            <tr>
                <td class="right"><strong>{{ __('quotations.fields.total') }}:</strong></td>
                <td class="right"><strong>{{ number_format((float) $quotation->total, 2, '.', '') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($quotation->notes)
        <div class="box">
            <strong>{{ __('quotations.fields.notes') }}:</strong><br>
            {!! nl2br(e($quotation->notes)) !!}
        </div>
    @endif
</body>
</html>

