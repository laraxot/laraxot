<?php

return [
    'roles' => [
        'super_admin' => [
            'panels' => [
                'admin' => true,
                'operator' => true,
                'wts' => true,
                'dds' => true,
                'thermo' => true,
                'energy-broker' => true,
                'asd-soci' => true,
                'timber' => true,
                'metalwork' => true,
                'customer' => true,
                'supplier' => true,
            ],
            'features' => [
                '*' => true,
                'admin.artisan_readiness' => true,
                'operator.artisan_readiness' => true,
            ],
        ],
        'admin' => [
            'panels' => [
                'admin' => true,
                'operator' => true,
                'wts' => true,
                'dds' => true,
                'thermo' => true,
                'energy-broker' => true,
                'asd-soci' => true,
                'timber' => true,
                'metalwork' => true,
            ],
            'features' => [
                '*' => true,
                'admin.artisan_readiness' => true,
                'operator.artisan_readiness' => true,
            ],
        ],
        'administrator' => [
            'panels' => [
                'admin' => true,
                'operator' => true,
                'wts' => true,
                'dds' => true,
                'thermo' => true,
                'energy-broker' => true,
                'asd-soci' => true,
                'timber' => true,
                'metalwork' => true,
            ],
            'features' => [
                '*' => true,
                'admin.artisan_readiness' => true,
                'operator.artisan_readiness' => true,
            ],
        ],
        'operator' => [
            'panels' => [
                'operator' => true,
            ],
            'features' => [
                'operator.my_schedule' => true,
                'operator.intervention_calendar' => true,
                'operator.interventions' => true,
                'operator.work_orders' => true,
                'operator.ai_assistant' => true,
                'operator.whatsapp_ai' => true,
                'operator.email_ai' => true,
                'operator.artisan_readiness' => true,
                'operator.create_intervention_from_calendar' => false,
            ],
        ],
        'wts' => [
            'panels' => [
                'wts' => true,
            ],
            'features' => [
                'wts.*' => true,
            ],
        ],
        'timber_operator' => [
            'panels' => [
                'timber' => true,
            ],
            'features' => [
                'timber.dashboard' => true,
                'timber.purchases' => true,
                'timber.yard' => true,
                'timber.processing' => true,
                'timber.drying' => true,
                'timber.outputs' => true,
                'timber.labels' => true,
                'timber.traceability' => true,
                'timber.inventory' => true,
                'timber.registries' => false,
            ],
        ],
        'timber_admin' => [
            'panels' => [
                'timber' => true,
            ],
            'features' => [
                'timber.*' => true,
            ],
        ],
        'metalwork_operator' => [
            'panels' => [
                'metalwork' => true,
            ],
            'features' => [
                'metalwork.dashboard' => true,
                'metalwork.quotation' => true,
                'metalwork.drawings' => true,
                'metalwork.estimates' => true,
                // Prices and rates are the workshop's margin: read-only for the
                // operator, who quotes with them but does not set them.
                'metalwork.registries' => false,
            ],
        ],
        'metalwork_admin' => [
            'panels' => [
                'metalwork' => true,
            ],
            'features' => [
                'metalwork.*' => true,
            ],
        ],
        'dds_admin' => [
            'panels' => [
                'dds' => true,
            ],
            'features' => [
                'dds.*' => true,
            ],
        ],
        'dds_technician' => [
            'panels' => [
                'dds' => true,
            ],
            'features' => [
                'dds.dashboard' => true,
                'dds.crm' => false,
                'dds.surveys_contracts' => true,
                'dds.planning' => true,
                'dds.field_app' => true,
                'dds.customer_portal' => false,
                'dds.administration' => false,
                'dds.full_suite' => false,
            ],
        ],
        'dds_customer' => [
            'panels' => [
                'dds' => true,
            ],
            'features' => [
                'dds.dashboard' => true,
                'dds.crm' => false,
                'dds.surveys_contracts' => false,
                'dds.planning' => false,
                'dds.field_app' => false,
                'dds.customer_portal' => true,
                'dds.administration' => false,
                'dds.full_suite' => false,
            ],
        ],
        'thermo_admin' => [
            'panels' => [
                'thermo' => true,
            ],
            'features' => [
                'thermo.*' => true,
            ],
        ],
        'thermo_technician' => [
            'panels' => [
                'thermo' => true,
            ],
            'features' => [
                'thermo.dashboard' => true,
                'thermo.calendar' => true,
                'thermo.account_access' => false,
                'thermo.crm' => false,
                'thermo.survey_quote' => true,
                'thermo.field_app' => true,
                'thermo.compliance_billing' => false,
                'thermo.bluetooth_pump' => true,
                'thermo.ai_documents' => false,
            ],
        ],
        'thermo_supplier_call_center' => [
            'panels' => [
                'thermo' => true,
            ],
            'features' => [
                'thermo.dashboard' => true,
                'thermo.calendar' => true,
                'thermo.account_access' => false,
                'thermo.crm' => false,
                'thermo.survey_quote' => false,
                'thermo.field_app' => false,
                'thermo.compliance_billing' => false,
                'thermo.bluetooth_pump' => false,
                'thermo.ai_documents' => false,
            ],
        ],
        'thermo_customer' => [
            'panels' => [
                'thermo' => true,
            ],
            'features' => [
                'thermo.dashboard' => true,
                'thermo.calendar' => false,
                'thermo.account_access' => false,
                'thermo.crm' => false,
                'thermo.survey_quote' => false,
                'thermo.field_app' => false,
                'thermo.compliance_billing' => true,
                'thermo.bluetooth_pump' => false,
                'thermo.ai_documents' => false,
            ],
        ],
        'energy_broker_admin' => [
            'panels' => [
                'energy-broker' => true,
            ],
            'features' => [
                'energy_broker.*' => true,
            ],
        ],
        'energy_broker_sales' => [
            'panels' => [
                'energy-broker' => true,
            ],
            'features' => [
                'energy_broker.dashboard' => true,
                'energy_broker.crm' => true,
                'energy_broker.documents' => true,
                'energy_broker.offers' => true,
                'energy_broker.pipeline' => true,
                'energy_broker.compliance' => false,
                'energy_broker.commissions' => false,
                'energy_broker.customer_care' => true,
                'energy_broker.agent_portal' => false,
            ],
        ],
        'energy_broker_backoffice' => [
            'panels' => [
                'energy-broker' => true,
            ],
            'features' => [
                'energy_broker.dashboard' => true,
                'energy_broker.crm' => true,
                'energy_broker.documents' => true,
                'energy_broker.offers' => false,
                'energy_broker.pipeline' => true,
                'energy_broker.compliance' => true,
                'energy_broker.commissions' => false,
                'energy_broker.customer_care' => true,
                'energy_broker.agent_portal' => false,
            ],
        ],
        'energy_broker_compliance' => [
            'panels' => [
                'energy-broker' => true,
            ],
            'features' => [
                'energy_broker.dashboard' => true,
                'energy_broker.crm' => false,
                'energy_broker.documents' => true,
                'energy_broker.offers' => false,
                'energy_broker.pipeline' => true,
                'energy_broker.compliance' => true,
                'energy_broker.commissions' => false,
                'energy_broker.customer_care' => false,
                'energy_broker.agent_portal' => false,
            ],
        ],
        'energy_broker_agent' => [
            'panels' => [
                'energy-broker' => true,
            ],
            'features' => [
                'energy_broker.dashboard' => true,
                'energy_broker.crm' => true,
                'energy_broker.documents' => true,
                'energy_broker.offers' => true,
                'energy_broker.pipeline' => true,
                'energy_broker.compliance' => false,
                'energy_broker.commissions' => false,
                'energy_broker.customer_care' => false,
                'energy_broker.agent_portal' => true,
            ],
        ],
        'energy_broker_support' => [
            'panels' => [
                'energy-broker' => true,
            ],
            'features' => [
                'energy_broker.dashboard' => true,
                'energy_broker.crm' => true,
                'energy_broker.documents' => false,
                'energy_broker.offers' => false,
                'energy_broker.pipeline' => true,
                'energy_broker.compliance' => false,
                'energy_broker.commissions' => false,
                'energy_broker.customer_care' => true,
                'energy_broker.agent_portal' => false,
            ],
        ],
        'giveback_admin' => [
            'panels' => [
                'giveback-platform' => true,
            ],
            'features' => [
                'giveback.*' => true,
            ],
        ],
        'giveback_merchant' => [
            'panels' => [
                'giveback-platform' => true,
            ],
            'features' => [
                'giveback.dashboard' => true,
                'giveback.value_flow' => true,
                'giveback.widget' => true,
                'giveback.onboarding' => true,
                'giveback.merchant_dashboard' => true,
                'giveback.admin_payouts' => false,
                'giveback.lottery_compliance' => true,
                'giveback.roadmap' => false,
            ],
        ],
        'giveback_support' => [
            'panels' => [
                'giveback-platform' => true,
            ],
            'features' => [
                'giveback.dashboard' => true,
                'giveback.value_flow' => true,
                'giveback.widget' => true,
                'giveback.onboarding' => true,
                'giveback.merchant_dashboard' => true,
                'giveback.admin_payouts' => true,
                'giveback.lottery_compliance' => true,
                'giveback.roadmap' => false,
            ],
        ],
        'asd_admin' => [
            'panels' => [
                'asd-soci' => true,
            ],
            'features' => [
                'asd.*' => true,
            ],
        ],
        'asd_secretary' => [
            'panels' => [
                'asd-soci' => true,
            ],
            'features' => [
                'asd.dashboard' => true,
                'asd.enrollment' => true,
                'asd.member_register' => true,
                'asd.accounting' => false,
                'asd.events' => true,
                'asd.member_area' => true,
                'asd.documentation' => true,
            ],
        ],
        'asd_treasurer' => [
            'panels' => [
                'asd-soci' => true,
            ],
            'features' => [
                'asd.dashboard' => true,
                'asd.enrollment' => false,
                'asd.member_register' => true,
                'asd.accounting' => true,
                'asd.events' => true,
                'asd.member_area' => false,
                'asd.documentation' => true,
            ],
        ],
        'asd_member' => [
            'panels' => [
                'asd-soci' => true,
            ],
            'features' => [
                'asd.dashboard' => true,
                'asd.enrollment' => true,
                'asd.member_register' => false,
                'asd.accounting' => false,
                'asd.events' => true,
                'asd.member_area' => true,
                'asd.documentation' => true,
            ],
        ],
        'asd_event_manager' => [
            'panels' => [
                'asd-soci' => true,
            ],
            'features' => [
                'asd.dashboard' => true,
                'asd.enrollment' => false,
                'asd.member_register' => false,
                'asd.accounting' => false,
                'asd.events' => true,
                'asd.member_area' => false,
                'asd.documentation' => true,
            ],
        ],
        'customer' => [
            'panels' => [
                'customer' => true,
            ],
            'features' => [
                'customer.*' => true,
            ],
        ],
        'supplier' => [
            'panels' => [
                'supplier' => true,
            ],
            'features' => [
                'supplier.*' => true,
            ],
        ],
    ],
    'accounts' => [
        'info@gsbuildingservice.it' => [
            'panels' => [
                'admin' => true,
                'operator' => true,
                'wts' => false,
                'dds' => false,
            ],
            'features' => [
                'operator.my_schedule' => false,
                'operator.intervention_calendar' => true,
                'operator.interventions' => true,
                'operator.work_orders' => true,
                'operator.dashboard' => true,
                'operator.daily_notes' => false,
                'operator.quotations' => false,
                'operator.catalog' => false,
                'operator.vehicles' => false,
                'operator.hr' => false,
                'operator.ai_assistant' => true,
                'operator.artisan_readiness' => true,
                'operator.materials' => false,
                'operator.upload_media' => true,
                'operator.capture_signature' => true,
                'operator.capture_sketch' => true,
                'operator.create_intervention_from_calendar' => false,
                'operator.create_intervention_from_list' => false,
                'operator.login_redirect_calendar' => false,
                'operator.force_self_assignment' => true,
                'admin.*' => true,
                'admin.dashboard' => true,
                'admin.intervention_calendar' => true,
                'admin.interventions' => true,
                'admin.team_interventions' => true,
                'admin.assigned_perimeter' => false,
                'admin.work_orders' => true,
                'admin.artisan_readiness' => true,
                'admin.customers' => true,
                'admin.audit_log' => true,
                'admin.platform_settings' => true,
                'admin.whatsapp_ai' => true,
                'admin.email_ai' => true,
                'admin.users' => true,
                'admin.roles' => true,
                'admin.inventory' => true,
                'admin.billing' => true,
                'admin.quotations' => true,
                'admin.catalog' => true,
                'admin.vehicles' => true,
                'admin.hr' => true,
                'admin.bom' => true,
                'admin.production' => true,
                'admin.resellers' => true,
                'admin.reports' => true,
                'admin.landing_intervention_calendar' => false,
            ],
            'logo' => 'images/GS BBUILDING SERVICE.png',
            'permissions' => [
                'ViewAny:Customer',
                'View:Customer',
                'Create:Customer',
                'Update:Customer',
                'ViewAny:WorkOrder',
                'View:WorkOrder',
                'Create:WorkOrder',
                'Update:WorkOrder',
            ],
        ],

        // Elettronica Maiorano S.r.l.s. — impianti elettrici + videosorveglianza + fotovoltaico.
        // La chiave DEVE combaciare con ownerEmail() di ElettronicaMaioranoBootstrapSeeder.
        'elettronica.maiorano@gmail.com' => [
            'panels' => [
                'admin' => true,
                'thermo' => true,
            ],
            'features' => [
                // Anagrafica impianti cliente (videosorveglianza/quadri/fotovoltaico) + schemi tecnici.
                'admin.customer_systems' => true,
            ],
        ],
    ],
];
