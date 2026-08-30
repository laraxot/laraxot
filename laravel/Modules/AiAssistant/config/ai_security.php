<?php

declare(strict_types=1);

/*
 * Solo valori di default: niente env() nei config di modulo, Larastan lo vieta
 * fuori dalla config/ di root (stessa scelta gia' fatta in Catalog/config/metel).
 */
return [
    /*
     * Action types an agent may propose. This is an allow-list on purpose: a
     * new type added to the executor tomorrow stays refused until somebody
     * lists it here, rather than slipping through because nobody thought to
     * forbid it. Keep it in step with ExecuteAiActionProposalAction.
     */
    'allowed_action_types' => [
        'start_time_punch',
        'stop_time_punch',
        'upsert_daily_note',
        'change_work_order_status',
        'finalize_intervention_report',
        'create_inventory_movement',
        'allocate_inventory_to_work_order',
        'allocate_inventory_to_intervention',
        'create_production_order',
        'advance_production_phase',
        'create_compliance_document',
        'create_work_order_change_order',
        'generate_pos_draft',
        'apply_public_procurement_tender_draft',
        'create_public_procurement_contract_from_opportunity',
        'import_public_procurement_price_items',
        'confirm_energy_broker_document_import',
        'append_work_order_activity_from_whatsapp',
        'organize_whatsapp_request',
        'create_intervention_from_whatsapp',
        'update_intervention_schedule_from_whatsapp',
        'append_work_order_activity_from_email',
        'create_intervention_from_email',
        'update_intervention_schedule_from_email',
    ],

    /*
     * Ceiling on proposals one user can be handed inside the window. Human
     * confirmation already stops a bad proposal from doing damage; this stops
     * an agent in a loop from burying the person who has to read them.
     */
    'max_proposals_per_window' => 30,
    'proposal_window_minutes' => 10,
];
