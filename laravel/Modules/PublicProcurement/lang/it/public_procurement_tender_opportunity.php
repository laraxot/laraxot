<?php

declare(strict_types=1);

return [
    'fields' => [
        'status' => ['label' => 'Status'],
        'tender_source_id' => ['label' => 'Tender source id'],
        'customer_id' => ['label' => 'Customer id'],
        'title' => ['label' => 'Title'],
        'contracting_authority' => ['label' => 'Contracting authority'],
        'cig' => ['label' => 'Cig'],
        'cup' => ['label' => 'Cup'],
        'cpv_code' => ['label' => 'Cpv code'],
        'category' => ['label' => 'Category'],
        'territory' => ['label' => 'Territory'],
        'estimated_amount' => ['label' => 'Estimated amount'],
        'published_at' => ['label' => 'Published at'],
        'submission_deadline_at' => ['label' => 'Submission deadline at'],
        'site_visit_due_at' => ['label' => 'Site visit due at'],
        'source_url' => ['label' => 'Source url'],
        'raw_excerpt' => ['label' => 'Raw excerpt'],
        'notes' => ['label' => 'Notes'],
    ],
    'actions' => [
        'evaluateEligibility' => ['label' => 'EvaluateEligibility'],
        'createContract' => ['label' => 'CreateContract'],
    ],
    'navigation' => ['label' => 'public procurement tender opportunity.navigation'],
];
