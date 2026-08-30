<?php

declare(strict_types=1);

return [
    'navigation' => 'Financial control',

    'aging' => [
        'title' => 'Who owes you money',
        'as_of' => 'As of :date',
        'total' => 'Outstanding',
        'overdue_total' => 'Past due',
        'empty' => 'Nothing outstanding. Every invoice has been paid.',
    ],

    'buckets' => [
        'not_due' => 'Not due yet',
        'days_1_30' => '1–30 days late',
        'days_31_60' => '31–60 days late',
        'days_61_90' => '61–90 days late',
        'over_90' => 'Over 90 days late',
    ],

    'cash_flow' => [
        'title' => 'Cash flow',
        'opening_balance' => 'Balance today',
        'closing_balance' => 'Balance at the end',
        'lowest_balance' => 'Lowest point',
        'first_shortfall' => 'Money runs out on',
        'no_shortfall' => 'The balance stays positive over the whole period.',
        'inflow' => 'In',
        'outflow' => 'Out',
        'balance' => 'Balance',
    ],

    'margin' => [
        'title' => 'Margin by job',
        'revenue' => 'Billed',
        'labour_cost' => 'Labour',
        'material_cost' => 'Materials',
        'external_cost' => 'Subcontractors',
        'total_cost' => 'Total cost',
        'margin' => 'Margin',
        'margin_percent' => 'Margin %',
        'no_revenue' => 'Not billed yet',
        'loss' => 'At a loss',
        'rate_in_use' => 'Labour costed at :rate €/hour.',
        'default_rate_warning' => 'No company hourly cost has been set yet, so :rate €/hour is being used as an estimate. Enter your real figure — until then the margin per job is indicative.',
    ],

    'fields' => [
        'due_on' => 'Due on',
        'paid_on' => 'Paid on',
        'paid_amount' => 'Amount paid',
        'outstanding' => 'Outstanding',
        'days_overdue' => 'Days late',
    ],
];
