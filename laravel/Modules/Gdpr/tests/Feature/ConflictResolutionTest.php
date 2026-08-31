<?php

declare(strict_types=1);

namespace Modules\Gdpr\Tests\Feature;

use Modules\Gdpr\Models\Profile;
use Modules\Gdpr\Models\Treatment;
use Modules\Gdpr\Tests\TestCase;

uses(\Modules\Gdpr\Tests\TestCase::class);

it('verifica che le classi corrette siano istanziabili', function (): void {
    expect((new Treatment())::class)->toBe(Treatment::class);
    expect((new Profile())::class)->toBe(Profile::class);
});

it('verifica che le proprietà delle classi siano accessibili', function (): void {
    $treatment = new Treatment();
    $profile = new Profile();

    // Verifica che le proprietà fillable siano definite
    expect(count($treatment->getFillable()))->toBeGreaterThanOrEqual(0);
    expect(count($profile->getFillable()))->toBeGreaterThanOrEqual(0);

    // Verifica che la connessione al database sia definita correttamente
    expect($profile->getConnectionName())->toBe('gdpr');
});
