<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Services for your city', 'description' => 'Report a local issue, browse public reports or track a request.', 'breadcrumb' => 'Services'],
    'hero' => ['title' => 'Services for your city', 'subtitle' => 'Submit a report, browse public reports or track a request using its tracking code.', 'search_label' => 'Search available tasks', 'search_placeholder' => 'Search tasks...'],
    'nav' => ['aria' => 'Available tasks', 'report' => 'Submit a report', 'browse' => 'Browse reports', 'track' => 'Track a request'],
    'featured' => ['title' => 'What you can do', 'subtitle' => 'Choose a task to open an available Fixcity journey.'],
    'card' => [
        'featured' => 'Featured',
        'access' => 'Open service',
        'access_aria' => 'Open service: :title',
        'status' => ['active' => 'Available', 'inactive' => 'Unavailable', 'maintenance' => 'Under maintenance'],
    ],
    'tasks' => [
        'report_title' => 'Report an issue', 'report_body' => 'Describe a problem in a public place and send it to the responsible office.', 'report_auth_note' => 'Sign in to your account to submit a report.', 'report_action' => 'Submit a report',
        'browse_title' => 'Explore the map and list', 'browse_body' => 'Browse public reports and see work in progress across the city.', 'browse_action' => 'Open reports',
        'track_title' => 'Track a report', 'track_body' => 'Check for updates with the tracking code received after submission.', 'track_action' => 'Open tracking',
    ],
    'results' => ['singular' => 'task available', 'plural' => 'tasks available', 'empty' => 'No tasks match your search. Try different words.'],
    'categories' => [
        'title' => 'Report categories',
        'subtitle' => 'Choose the type of problem you want to report or browse reports already made public.',
        'actions' => ['aria' => 'Report actions', 'report' => 'Report a problem', 'browse' => 'Browse reports'],
        'directory' => ['title' => 'Explore by category', 'subcategories' => 'Subcategories', 'empty' => 'Categories are not available yet. You can still browse public reports.'],
        'items' => [
            'reports' => ['title' => 'Public reports', 'description' => 'Browse reports made public by the municipality and see intervention updates.'],
        ],
    ],
];
