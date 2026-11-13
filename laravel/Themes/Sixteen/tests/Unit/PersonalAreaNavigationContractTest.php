<?php

declare(strict_types=1);

use function Safe\file_get_contents;

test('all personal area pages share the same localized navigation tabs via shared heading', function (): void {
    $root = dirname(__DIR__, 2).'/resources/views/components/blocks';

    $heading = file_get_contents($root.'/personal-area/heading.blade.php');
    expect($heading)
        ->toContain('components.blocks.personal-area.navigation')
        ->toContain('col-12 p-0');

    $navigation = file_get_contents($root.'/personal-area/navigation.blade.php');
    expect($navigation)
        ->toContain('personalAreaPratiche')
        ->toContain('personalAreaSeguite')
        ->toContain('personalAreaNotifiche')
        ->toContain('personalAreaImpostazioni')
        ->toContain('personal_area.nav.');

    foreach ([
        'personal-area-practices/heading.blade.php' => 'personal-area.heading',
        'personal-area-practices/followed.blade.php' => "'active' => 'followed'",
        'personal-area-practices/design-comuni.blade.php' => "'active' => 'practices'",
        'personal-area-settings/notifications.blade.php' => "'active' => 'notifications'",
        'personal-area-settings/settings.blade.php' => "'active' => 'settings'",
    ] as $view => $needle) {
        expect(file_get_contents($root.'/'.$view))->toContain($needle);
    }

    // Notifiche e impostazioni NON devono reincludere la nav fuori dall'heading (un solo chrome).
    foreach (['personal-area-settings/notifications.blade.php', 'personal-area-settings/settings.blade.php'] as $view) {
        $html = file_get_contents($root.'/'.$view);
        expect($html)->toContain('personal-area.heading');
        expect(substr_count($html, 'personal-area.navigation'))->toBe(0);
    }
});
