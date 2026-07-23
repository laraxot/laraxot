<?php

declare(strict_types=1);

return [
    'fields' => [
        'original_text' => [
            'label' => 'Text',
            'tooltip' => 'Original comment body',
            'helper_text' => '',
            'description' => '',
        ],
        'text' => [
            'label' => 'Rendered text',
            'tooltip' => 'HTML processed body',
            'helper_text' => '',
            'description' => '',
        ],
        'commentable_type' => [
            'label' => 'Subject type',
            'tooltip' => 'Morph class of the commented entity',
            'helper_text' => '',
            'description' => '',
        ],
        'commentable_id' => [
            'label' => 'Subject ID',
            'tooltip' => 'Primary key of the commented entity',
            'helper_text' => '',
            'description' => '',
        ],
        'approved_at' => [
            'label' => 'Approved at',
            'tooltip' => 'Moderation approval timestamp',
            'helper_text' => '',
            'description' => '',
        ],
        'commentator_display' => [
            'label' => 'Author',
            'tooltip' => 'Display name of the commentator',
            'helper_text' => '',
            'description' => '',
        ],
        'moderation_status' => [
            'label' => 'Status',
            'tooltip' => 'Moderation status',
            'helper_text' => '',
            'description' => '',
        ],
        'commentator' => [
            'label' => 'Author',
            'tooltip' => 'User who wrote the comment',
            'helper_text' => '',
            'description' => '',
        ],
        'commentable' => [
            'label' => 'Subject',
            'tooltip' => 'Entity the comment is attached to',
            'helper_text' => '',
            'description' => '',
        ],
        'status' => [
            'label' => 'Status',
            'tooltip' => 'Moderation status',
            'helper_text' => '',
            'description' => '',
            'pending' => 'Pending',
            'approved' => 'Approved',
        ],
        'parent_id' => [
            'label' => 'Reply to',
            'tooltip' => 'Parent comment ID',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => [
            'label' => 'Created at',
            'tooltip' => 'Creation timestamp',
            'helper_text' => '',
            'description' => '',
        ],
    ],
    'actions' => [
        'approve' => [
            'label' => 'Approve',
            'tooltip' => 'Approve this comment',
        ],
        'reject' => [
            'label' => 'Reject',
            'tooltip' => 'Delete the rejected comment',
            'confirmation' => 'Reject this comment?',
        ],
        'approve_bulk' => [
            'label' => 'Approve selected',
            'tooltip' => 'Approve selected pending comments',
        ],
        'reject_bulk' => [
            'label' => 'Reject selected',
            'tooltip' => 'Reject selected comments',
            'confirmation' => 'Reject selected comments?',
        ],
    ],
    'filters' => [
        'moderation' => [
            'label' => 'Moderation',
            'tooltip' => 'Filter by approval status',
            'pending' => 'Pending',
            'approved' => 'Approved',
        ],
    ],
    'label' => 'Comment',
    'plural_label' => 'Comments',
    'navigation' => [
        'name' => 'Comments',
        'plural' => 'Comments',
        'group' => [
            'name' => 'Moderation',
            'description' => 'Comments and reactions management',
        ],
        'label' => 'Comments',
        'sort' => 40,
        'icon' => 'heroicon-o-chat-bubble-left-right',
    ],
];
