<?php

declare(strict_types=1);

return [
    'navigation' => 'Site surveys',
    'site_survey' => 'Site survey',
    'site_surveys' => 'Site surveys',

    'fields' => [
        'code' => 'Code',
        'customer' => 'Customer',
        'customer_site' => 'Site',
        'notes' => 'Notes',
        'performed_at' => 'Performed at',
        'performed_by' => 'Performed by',
        'quotation' => 'Quotation',
        'scheduled_at' => 'Scheduled at',
        'status' => 'Status',
        'title' => 'Title',
        'work_order' => 'Work order',
        'attachments_count' => 'Attachments',
    ],

    'statuses' => [
        'planned' => 'Planned',
        'done' => 'Done',
        'quoted' => 'Quoted',
    ],

    'sections' => [
        'general' => 'General',
        'links' => 'Links',
        'outcome' => 'Outcome',
    ],

    'hints' => [
        'no_attachments' => 'No attachments yet: add photos, videos or voice notes from the survey.',
    ],

    'actions' => [
        'record' => 'Record survey',
        'record_hint' => 'Mark the survey as done and save the attachments.',
        'recorded_title' => 'Survey recorded',
        'recorded_body' => 'The survey has been recorded successfully.',
        'attachments' => 'Attachments',
    ],

    'attachments' => [
        'title' => 'Attachments',
        'download' => 'Download',
        'file' => 'File',
        'file_title' => 'File title',
        'kind' => 'Kind',
        'kinds' => [
            'photo' => 'Photo',
            'video' => 'Video',
            'audio' => 'Voice note',
        ],
        'uploaded_at' => 'Uploaded at',
    ],
];
