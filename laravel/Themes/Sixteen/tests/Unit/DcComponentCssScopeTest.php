<?php

declare(strict_types=1);

use function Safe\file_get_contents;

test('il css dei componenti non usa il nome della pagina', function (): void {
    $theme = dirname(__DIR__, 2);
    $faq = file_get_contents($theme.'/resources/views/components/pages/faq.blade.php');
    $listing = file_get_contents($theme.'/resources/css/app/06-page-cards-and-listing.css');
    $components = file_get_contents($theme.'/resources/css/app/20-dc-components.css');

    expect($faq)->not->toContain('main[data-page=');
    expect($listing)->not->toContain('domande-frequenti');
    expect($components)->toContain('.page-content[data-dc] .cmp-input-search');
    expect($components)->toContain('.page-content[data-dc] .cmp-rating');
    expect($components)->not->toContain('main[data-page=');
    expect($components)->not->toContain('.dc-faq');
});
