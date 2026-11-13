@include('pub_theme::components.blocks.segnalazioni.layout', ['data' => [
    'title' => __('pub_theme::segnalazione.elenco.title'),
    'subtitle' => __('pub_theme::segnalazione.elenco.subtitle', ['count' => 73]),
    'breadcrumb' => [
        ['label' => __('pub_theme::navigation.home'), 'url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/')],
        ['label' => __('pub_theme::segnalazione.elenco.title'), 'active' => true],
    ],
    'tabs' => ['items' => [
        ['id' => 'map', 'label' => __('pub_theme::segnalazione.elenco.map_tab'), 'active' => true],
        ['id' => 'list', 'label' => __('pub_theme::segnalazione.elenco.list_tab')],
    ]],
    'filters' => ['title' => __('pub_theme::segnalazione.filters.legend.label')],
    'main_content' => ['cta' => [
        'title' => __('pub_theme::segnalazione.elenco.make_report_title'),
        'text' => __('pub_theme::segnalazione.elenco.make_report_text'),
        'button_text' => __('pub_theme::segnalazione.elenco.make_report_btn'),
    ]],
    'contacts' => [
        'contact_title' => __('pub_theme::segnalazione.contacts.title'),
        'contacts' => [
            ['label' => __('pub_theme::segnalazione.contacts.faq')],
            ['label' => __('pub_theme::segnalazione.contacts.assistance')],
            ['label' => __('pub_theme::segnalazione.contacts.phone')],
            ['label' => __('pub_theme::segnalazione.contacts.appointment')],
        ],
    ],
]])
