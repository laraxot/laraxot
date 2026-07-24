<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

/**
 * @return list<string>
 */
function voiceKeysUsedInBlade(): array
{
    $blade = (string) file_get_contents(base_path('Modules/Quotations/Resources/views/filament/pages/record-quotation-voice.blade.php'));
    preg_match_all('/quotations\.voice\.[a-z_]+/', $blade, $matches);

    /** @var list<string> $keys */
    $keys = array_values(array_unique($matches[0]));

    // The technical-parameter labels are resolved dynamically (voice.params.<key>).
    $keys[] = 'quotations.voice.params.surface_m2';
    $keys[] = 'quotations.voice.params.flue_length_m';
    $keys[] = 'quotations.voice.params.thermostat_present';

    return $keys;
}

it('has every quotations.voice.* key translated in Italian', function (): void {
    app()->setLocale('it');

    foreach (voiceKeysUsedInBlade() as $key) {
        expect(__($key))->not->toBe($key, "Chiave IT mancante: {$key}");
    }
});

it('has every quotations.voice.* key translated in English', function (): void {
    app()->setLocale('en');

    foreach (voiceKeysUsedInBlade() as $key) {
        expect(__($key))->not->toBe($key, "Missing EN key: {$key}");
    }
});
