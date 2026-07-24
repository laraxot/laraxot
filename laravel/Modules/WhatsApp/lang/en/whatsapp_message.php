<?php

declare(strict_types=1);

return [
    'fields' => [
        'conversation_id' => ['label' => 'Conversation'],
        'work_order_id' => ['label' => 'Work order'],
        'intervention_id' => ['label' => 'Intervention'],
        'ai_action_proposal_id' => ['label' => 'AI proposal'],
        'meta_message_id' => ['label' => 'Message ID'],
        'direction' => ['label' => 'Direction'],
        'from_number' => ['label' => 'From'],
        'to_number' => ['label' => 'To'],
        'message_type' => ['label' => 'Message type'],
        'body' => ['label' => 'Body'],
        'status' => ['label' => 'Status'],
        'processing_status' => ['label' => 'Processing status'],
        'payload' => ['label' => 'Payload'],
        'ai_result' => ['label' => 'AI result'],
        'error' => ['label' => 'Error'],
        'received_at' => ['label' => 'Received at'],
        'sent_at' => ['label' => 'Sent at'],
        'processed_at' => ['label' => 'Processed at'],
        'created_at' => ['label' => 'Created at'],
        'updated_at' => ['label' => 'Updated at'],
    ],
    'label' => 'WhatsApp Message',
    'plural_label' => 'WhatsApp Messages',
];
