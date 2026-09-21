<?php

declare(strict_types=1);

namespace Modules\Gdpr\Tests\Feature;

use Modules\Gdpr\Models\Profile;
use Modules\Gdpr\Models\Treatment;
use Modules\Gdpr\Tests\TestCase;

uses(TestCase::class);

it('verifica che le classi corrette siano istanziabili', function (): void {
    // `new X()` restituisce per costruzione un X: il fatto verificabile e' che i due
    // model si costruiscano senza parametri obbligatori.
    expect((new \ReflectionClass(Treatment::class))->getConstructor()?->getNumberOfRequiredParameters() ?? 0)->toBe(0);
    expect((new \ReflectionClass(Profile::class))->getConstructor()?->getNumberOfRequiredParameters() ?? 0)->toBe(0);
});

it('verifica che le proprietà delle classi siano accessibili', function (): void {
    $treatment = new Treatment();
    $profile = new Profile();

    // `getFillable()` dichiara gia' array: cio' che conta e' che non sia vuoto,
    // altrimenti nessun attributo e' assegnabile in massa.
    expect($treatment->getFillable())->not->toBeEmpty();
    expect($profile->getFillable())->not->toBeEmpty();

    // Verifica che la connessione al database sia definita correttamente
    expect($profile->getConnectionName())->toBe('gdpr');
});
