<?php

declare(strict_types=1);
use Modules\Cms\Tests\TestCase;

uses(TestCase::class);
test('password can be updated', function (): void {
})->todo('Il flusso di aggiornamento password vive nel tema pubblico: il test richiede il pannello montato.');

test('current password must be correct', function (): void {
})->todo('Come sopra: senza il form del tema non c\'e\' nessuna validazione da esercitare.');
