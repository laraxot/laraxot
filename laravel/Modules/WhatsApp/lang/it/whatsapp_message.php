<?php

declare(strict_types=1);

return [
    'fields' => [
        'conversation_id' => ['label' => 'Conversazione'],
        'work_order_id' => ['label' => 'Commessa'],
        'intervention_id' => ['label' => 'Intervento'],
        'ai_action_proposal_id' => ['label' => 'Proposta AI'],
        'meta_message_id' => ['label' => 'ID messaggio'],
        'direction' => ['label' => 'Direzione'],
        'from_number' => ['label' => 'Mittente'],
        'to_number' => ['label' => 'Destinatario'],
        'message_type' => ['label' => 'Tipo messaggio'],
        'body' => ['label' => 'Corpo'],
        'status' => ['label' => 'Stato'],
        'processing_status' => ['label' => 'Stato elaborazione'],
        'payload' => ['label' => 'Payload'],
        'ai_result' => ['label' => 'Risultato AI'],
        'error' => ['label' => 'Errore'],
        'received_at' => ['label' => 'Ricevuto il'],
        'sent_at' => ['label' => 'Inviato il'],
        'processed_at' => ['label' => 'Elaborato il'],
        'created_at' => ['label' => 'Creato il'],
        'updated_at' => ['label' => 'Aggiornato il'],
    ],
    'label' => 'Messaggio WhatsApp',
    'plural_label' => 'Messaggi WhatsApp',
];
