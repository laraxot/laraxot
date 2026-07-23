<?php

declare(strict_types=1);

uses(Tests\TestCase::class);

test('homepage loads successfully', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('Elenco segnalazioni');
});

test('homepage has required components', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('nav-tabs');
    $response->assertSee('category-list');
    $response->assertSee('map-lit');
});

test('homepage has breadcrumb', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('breadcrumb');
    $response->assertSee('Home');
    $response->assertSee('Elenco segnalazioni');
});

test('homepage has map and list tabs', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('Mappa');
    $response->assertSee('Elenco');
});

test('homepage has filters', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('Filtra');
});

test('homepage has cta section', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('Fai una segnalazione');
    $response->assertSee('Segnala');
});

test('homepage has contacts section', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('Contatta il comune');
});

test('homepage has rating feedback', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
    $response->assertSee('Valuta da 1 a 5 stelle');
});

test('segnalazioni elenco test page loads successfully', function (): void {
    /** @var Tests\TestCase $this */
    $response = $this->get('/it/tests/ticket-list');

    $response->assertStatus(200);
    $response->assertSee('Elenco segnalazioni');
});
