<?php

return [
    'navigation' => 'Customers',
    'customer' => 'Customer',
    'customers' => 'Customers',
    'contacts' => 'Contacts',
    'sites' => 'Sites',
    'fields' => [
        'legal_name' => 'Legal Name',
        'vat_number' => 'VAT Number',
        'tax_code' => 'Tax Code',
        'email' => 'Email',
        'phone' => 'Phone',
        'address_line1' => 'Address',
        'address_line2' => 'Address 2',
        'city' => 'City',
        'postal_code' => 'Postal Code',
        'province' => 'Province',
        'country_code' => 'Country',
        'payment_method' => 'Payment Method',
        'iban' => 'IBAN',
        'notes' => 'Notes',
        'is_active' => 'Active',
        'full_name' => 'Full Name',
        'role' => 'Role',
        'name' => 'Name',
    ],
    'payment_methods' => [
        'bonifico' => 'Bank Transfer',
        'sepa' => 'SEPA Direct Debit',
        'carta' => 'Credit Card',
        'contanti' => 'Cash',
    ],
    'actions' => [
        'restore_cascade' => 'Restore Customer and Related Data',
    ],
];
