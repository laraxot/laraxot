<?php

declare(strict_types=1);

use Filament\Support\Contracts\HasLabel;
use PHPUnit\Framework\Assert;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    // ThemeServiceProvider registra `sixteen::` solo quando il tema e' attivo: qui lo registriamo a mano.
    app('translator')->addNamespace('sixteen', base_path('Themes/Sixteen/lang'));
});

test('every sixteen enum resolves a translated label for each case in it and en', function (): void {
    $files = Finder::create()->files()->in(dirname(__DIR__, 2).'/app/Enums')->depth(0)->name('*Enum.php');

    expect($files->hasResults())->toBeTrue();

    foreach (['it', 'en'] as $locale) {
        app()->setLocale($locale);

        foreach ($files as $file) {
            $enum = 'Themes\\Sixteen\\Enums\\'.$file->getBasename('.php');

            if (! enum_exists($enum)) {
                Assert::fail("{$enum} non e' un enum");
            }

            foreach ($enum::cases() as $case) {
                if (! $case instanceof HasLabel) {
                    Assert::fail("{$enum}::{$case->name} non implementa HasLabel");
                }

                $label = $case->getLabel();

                expect($label)->not->toBe('')->and($label)->not->toContain('sixteen::');
            }
        }
    }
});
