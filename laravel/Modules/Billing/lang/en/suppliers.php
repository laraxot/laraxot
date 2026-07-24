<?php

declare(strict_types=1);

return [
    'navigation' => 'Suppliers',

    'supplier' => 'Supplier',
    'suppliers' => 'Suppliers',
    'delivery_note' => 'Delivery note',
    'delivery_notes' => 'Delivery notes',
    'delivery_note_lines' => 'Delivery note lines',
    'supplier_invoice' => 'Supplier invoice',
    'supplier_invoices' => 'Supplier invoices',
    'supplier_invoice_lines' => 'Supplier invoice lines',

    'fields' => [
        'legal_name' => 'Legal name',
        'vat_number' => 'VAT number',
        'email' => 'Email',
        'phone' => 'Phone',
        'is_active' => 'Active',
        'number' => 'Number',
        'supplier' => 'Supplier',
        'work_order' => 'Work order',
        'received_on' => 'Received on',
        'issued_on' => 'Issued on',
        'notes' => 'Notes',
        'total' => 'Total',
        'status' => 'Status',
        'description' => 'Description',
        'quantity' => 'Quantity',
        'unit_price' => 'Unit price',
        'line_total' => 'Line total',
        'delivery_note_line' => 'Delivery note line',
        'lines_count' => 'Lines',
        'delivery_notes_count' => 'Delivery notes',
        'invoices_count' => 'Invoices',
    ],

    'statuses' => [
        'pending' => 'Pending reconciliation',
        'reconciled' => 'Reconciled',
        'disputed' => 'Disputed',
    ],

    'reconciliation' => [
        'action' => 'Reconcile with delivery notes',
        'title' => 'Supplier invoice reconciliation',
        'submit' => 'Close',
        'summary' => 'Reconciliation summary',
        'clean_title' => 'No discrepancies',
        'clean_body' => 'All invoiced lines match what was delivered.',
        'disputed_title' => 'Discrepancies found',
        'disputed_body' => 'Differences were found between invoiced and delivered: review before proceeding.',
        'columns' => [
            'description' => 'Description',
            'delivered_quantity' => 'Delivered qty',
            'invoiced_quantity' => 'Invoiced qty',
            'delivered_unit_price' => 'Delivery note price',
            'invoiced_unit_price' => 'Invoice price',
            'amount_difference' => 'Difference',
            'type' => 'Type',
        ],
        'types' => [
            'not_delivered' => 'Not delivered',
            'quantity_over' => 'Quantity over',
            'price_mismatch' => 'Price mismatch',
            'not_invoiced' => 'Delivered, not invoiced',
        ],
        'explanations' => [
            'not_delivered' => 'Invoiced material that no delivery note shows as delivered.',
            'quantity_over' => 'Invoiced a quantity greater than the delivered one.',
            'price_mismatch' => 'Invoiced price differs from the delivery note price.',
            'not_invoiced' => 'Delivered but not yet invoiced: expected on a following invoice.',
        ],
    ],
];
