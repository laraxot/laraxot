<?php

declare(strict_types=1);

use function Safe\file_get_contents;

it('keeps the Sixteen header content contract in tenant JSON', function (): void {
    $root = dirname(__DIR__, 4);
    $jsonPath = $root.'/config/local/fixcity/database/content/sections/header.json';
    $headerData = json_decode(file_get_contents($jsonPath), true, 512, JSON_THROW_ON_ERROR);
    /** @var array<string, mixed> $headerData */
    $headerBlocks = $headerData['blocks'];
    /** @var array<string, array<int, array{data: array<string, mixed>}> > $headerBlocks */
    $renderer = file_get_contents($root.'/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php');

    foreach (['it', 'en', 'de', 'es'] as $locale) {
        $data = $headerBlocks[$locale][0]['data'];
        /** @var array{brand: array{name: string, tagline: string, logo: string}, navigation: array<int, array{label: string, url: string, element: string}>, languages: array{items: array<int, array{locale: string, label: string}>}} $data */

        expect($data)->toHaveKeys(['brand', 'region', 'account', 'navigation', 'languages', 'search', 'menu']);
        expect($data['brand'])->toHaveKeys(['name', 'tagline', 'logo']);
        expect($data['navigation'])->not->toBeEmpty();
        expect($data['languages']['items'])->not->toBeEmpty();
        expect($data['region']['name'])->toBe('Demo');
    }

    expect($renderer)
        ->not->toContain('Nome della Regione')
        ->not->toContain('Seleziona lingua')
        ->not->toContain('Cerca nel sito')
        ->not->toContain("url('/')");
});
