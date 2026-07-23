<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { border-bottom: 2px solid #111827; margin-bottom: 12px; padding-bottom: 8px; }
        .title { font-size: 18px; font-weight: bold; }
        .meta { margin-top: 6px; color: #374151; }
        .grid { width: 100%; }
        .grid td { vertical-align: top; padding: 6px 0; }
        .label { color: #6b7280; width: 140px; }
        .section { margin-top: 14px; }
        .section h2 { font-size: 14px; margin: 0 0 6px 0; }
        .box { border: 1px solid #e5e7eb; padding: 8px; border-radius: 4px; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border-bottom: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        table.items th { background: #f9fafb; }
        .right { text-align: right; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">
            {{ __('work_orders.work_order') }} {{ $workOrder->code ?: ('#' . $workOrder->id) }}
        </div>
        <div class="meta">
            {{ $workOrder->title }}
        </div>
    </div>

    <table class="grid">
        <tr>
            <td style="width: 50%;">
                <div class="box">
                    <div><strong>{{ __('work_orders.fields.customer') }}:</strong> {{ $workOrder->customer?->legal_name ?? '-' }}</div>
                    <div class="muted">{{ $workOrder->site?->name ?? '' }}</div>
                    <div class="muted">
                        {{ $workOrder->site?->address_line1 ?? $workOrder->customer?->address_line1 ?? '' }}
                        {{ $workOrder->site?->postal_code ?? $workOrder->customer?->postal_code ?? '' }}
                        {{ $workOrder->site?->city ?? $workOrder->customer?->city ?? '' }}
                    </div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="box">
                    <div><strong>{{ __('work_orders.fields.status') }}:</strong> {{ $workOrder->status?->name ?? '-' }}</div>
                    <div><strong>{{ __('work_orders.fields.opened_at') }}:</strong> {{ optional($workOrder->opened_at)->format('Y-m-d H:i') ?: '-' }}</div>
                    <div><strong>{{ __('work_orders.fields.scheduled_at') }}:</strong> {{ optional($workOrder->scheduled_at)->format('Y-m-d H:i') ?: '-' }}</div>
                    <div><strong>{{ __('work_orders.fields.started_at') }}:</strong> {{ optional($workOrder->started_at)->format('Y-m-d H:i') ?: '-' }}</div>
                    <div><strong>{{ __('work_orders.fields.completed_at') }}:</strong> {{ optional($workOrder->completed_at)->format('Y-m-d H:i') ?: '-' }}</div>
                </div>
            </td>
        </tr>
    </table>

    @if($workOrder->description)
        <div class="section">
            <h2>{{ __('work_orders.fields.description') }}</h2>
            <div class="box">{!! nl2br(e($workOrder->description)) !!}</div>
        </div>
    @endif

    @if($workOrder->notes)
        <div class="section">
            <h2>{{ __('work_orders.fields.notes') }}</h2>
            <div class="box">{!! nl2br(e($workOrder->notes)) !!}</div>
        </div>
    @endif

    @if($workOrder->activities->count() > 0)
        <div class="section">
            <h2>{{ __('work_orders.activities') }}</h2>
            <table class="items">
                <thead>
                    <tr>
                        <th style="width: 140px;">{{ __('work_orders.fields.occurred_at') }}</th>
                        <th style="width: 110px;">{{ __('work_orders.fields.type') }}</th>
                        <th>{{ __('work_orders.fields.body') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->activities->sortByDesc('occurred_at')->take(20) as $activity)
                        <tr>
                            <td>{{ optional($activity->occurred_at)->format('Y-m-d H:i') ?: '-' }}</td>
                            <td>{{ __('work_orders.activity_types.' . $activity->type) }}</td>
                            <td>{!! nl2br(e($activity->body)) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="muted" style="margin-top: 6px;">{{ __('Showing latest 20 activities.') }}</div>
        </div>
    @endif
</body>
</html>

