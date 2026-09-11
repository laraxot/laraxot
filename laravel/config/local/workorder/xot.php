<?php

declare(strict_types=1);

return [
    'adm_home' => '01',
    'enable_ads' => '1',
    'main_module' => 'WorkOrder',
    'primary_lang' => 'it',
    'pub_theme' => 'Zero',
    'pub_theme' => 'Sixteen',
    'pub_theme' => 'Zero',
    'search_action' => 'it/videos',
    'show_trans_key' => false,
    'disable_admin_dynamic_route' => true,
    'disable_frontend_dynamic_route' => false,
    'register_adm_theme' => false,
    'register_pub_theme' => true,

    'testing' => [
        /*
         * Il database dei test, dichiarato invece che indovinato.
         *
         * XotBaseTestCase::sharedSqlitePath() senza questa chiave ripiega su
         * «l'unico *.sqlite dentro database/», e quando i file sono piu' di uno
         * sceglie un `test_data.sqlite` che nessuno ha mai costruito. E' quello
         * che e' successo appena database/database.sqlite e' comparso: la suite
         * ha smesso di trovare le tabelle senza che nessuno avesse toccato i
         * test.
         */
        'sqlite_file' => 'fixcity_data.sqlite',
    ],
];
