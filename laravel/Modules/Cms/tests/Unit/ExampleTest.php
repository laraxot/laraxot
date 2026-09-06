<?php

declare(strict_types=1);

use Modules\Cms\Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    cmsSkipTest('Requires admin panel + role seeding not available in minimal Cms test bootstrap.');
});

it('user admin can view main dashboard', function (): void {
})->todo('Il beforeEach salta gia\' il test: servono pannello admin e seeding dei ruoli, assenti dal bootstrap minimo di Cms.');

it('guest user can view main dashboard', function (): void {
})->todo('Come sopra: senza pannello admin non c\'e\' nessuna dashboard da chiedere.');
