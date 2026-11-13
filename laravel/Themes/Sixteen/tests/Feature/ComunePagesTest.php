<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Fixcity\Models\News;
use Modules\Fixcity\Models\Ticket;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    /** @var TestCase $this */
    if (! class_exists(Ticket::class)) {
        $this->markTestSkipped('Modulo Fixcity assente in questa base — test Comune rinviati.');
    }
});

test('homepage loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.homepage'));

    $response->assertStatus(200);
    $response->assertSee('Benvenuti nel Comune di');
    $response->assertSee('Segnalazioni');
    $response->assertSee('Servizi');
    $response->assertSee('Novità');
});

test('homepage displays recent tickets', function () {
    /** @var TestCase $this */
    if (! class_exists(Ticket::class)) {
        $this->markTestSkipped('Modulo Fixcity assente in questa base — test Comune rinviati.');
    }

    /** @var \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Fixcity\Models\Ticket> $ticketFactory */
    $ticketFactory = Ticket::factory();
    $ticketFactory->create([
        'name' => 'Buca Stradale',
        'description' => 'Buca pericolosa in via Roma',
    ]);

    $response = $this->get(route('comune.homepage'));

    $response->assertSee('Ultime Segnalazioni');
    $response->assertSee('Buca Stradale');
    $response->assertSee('Buca pericolosa in via Roma');
});

test('homepage displays recent news', function () {
    /** @var TestCase $this */
    if (! class_exists(News::class)) {
        $this->markTestSkipped('Modulo Fixcity assente in questa base — test Comune rinviati.');
    }

    $newsFactory = News::factory();
    $newsFactory->create([
        'title' => 'Nuovo Servizio Online',
        'excerpt' => 'Il comune lancia un nuovo servizio digitale',
    ]);

    $response = $this->get(route('comune.homepage'));

    $response->assertSee('Ultime Novità');
    $response->assertSee('Nuovo Servizio Online');
    $response->assertSee('Il comune lancia un nuovo servizio digitale');
});

test('servizi page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get('/it/services');

    $response->assertStatus(200);
    $response->assertSee('Servizi per la città');
    $response->assertSee('Segnala un problema');
    $response->assertSee('Per inviare una segnalazione è necessario accedere al proprio account.');
    $response->assertSee('Esplora la mappa e l’elenco');
    $response->assertSee('Segui una segnalazione');
    $response->assertSee('/it/tickets/create', false);
    $response->assertSee('/it/tickets/track', false);
    $response->assertSee('id="main-container"', false);
    $response->assertSee('id="footer"', false);
    $response->assertDontSee('example.test');
    $response->assertDontSee('06 1234567');
});

test('servizi page localizes task discovery and feedback for every supported locale', function () {
    /** @var TestCase $this */
    $localeContent = [
        'it' => ['Per inviare una segnalazione è necessario accedere al proprio account.', 'Segnalazioni pubbliche', 'Anagrafe e stato civile'],
        'en' => ['Sign in to your account to submit a report.', 'Public reports', 'Registry and civil status'],
        'de' => ['Melden Sie sich an, um eine Meldung zu senden.', 'Öffentliche Meldungen', 'Meldewesen und Personenstand'],
        'es' => ['Inicia sesión para enviar una incidencia.', 'Incidencias públicas', 'Padrón y registro civil'],
    ];

    foreach ($localeContent as $locale => [$authNote, $categoryTitle, $unsupportedCategory]) {
        /** @var string $locale */
        $response = $this->get('/'.$locale.'/services');

        $response->assertOk();
        $response->assertSee('id="service-search"', false);
        $response->assertSee('aria-live="polite"', false);
        $response->assertSee($authNote);
        $response->assertSee('id="report"', false);
        $response->assertSee('id="browse"', false);
        $response->assertSee('id="track"', false);
        $response->assertSee('id="categories"', false);
        $response->assertSee('href="/'.$locale.'/lista-categorie#reports"', false);
        $response->assertSee($categoryTitle)
            ->assertDontSee($unsupportedCategory);
        $response->assertSee('id="main-container"', false);
        $response->assertSee('id="footer"', false);
        $response->assertDontSee('example.test');
    }
});

test('service category directory links its supported category to the real reports list', function () {
    foreach (['it', 'en', 'de', 'es'] as $locale) {
        $response = $this->get('/'.$locale.'/lista-categorie');

        $response->assertOk()
            ->assertSee('id="reports"', false)
            ->assertSee('href="/'.$locale.'/tickets"', false)
            ->assertDontSee('id="registry"', false)
            ->assertDontSee('id="taxes"', false)
            ->assertDontSee('id="social"', false);
    }
});

test('novita page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.novita'));

    $response->assertStatus(200);
    $response->assertSee('Novità e Comunicati');
    $response->assertSee('Filtra per Categoria');
    $response->assertSee('Archivio');
});

test('novita page displays news articles', function () {
    /** @var TestCase $this */
    if (! class_exists(News::class)) {
        $this->markTestSkipped('Modulo Fixcity assente in questa base — test Comune rinviati.');
    }

    /** @var \Illuminate\Database\Eloquent\Factories\Factory $newsFactory */
    $newsFactory = News::factory();
    $newsFactory->count(3)->create();

    $this->get(route('comune.novita'));
});

test('contatti page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.contatti'));

    $response->assertStatus(200);
    $response->assertSee('Contatti');
    $response->assertSee('Informazioni di Contatto');
    $response->assertSee('Orari di Apertura');
    $response->assertSee('Mappa');
});

test('contatti page displays contact information', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.contatti'));

    $response->assertSee(config()->string('comune.nome'));
    $response->assertSee(config()->string('comune.indirizzo'));
    $response->assertSee(config()->string('comune.telefono'));
    $response->assertSee(config()->string('comune.email'));
    $response->assertSee(config()->string('comune.pec'));
});

test('contatti page displays opening hours', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.contatti'));

    $response->assertSee('Lunedì - Venerdì');
    $response->assertSee('Martedì e Giovedì');
    $response->assertSee('Sabato');
    $response->assertSee('Domenica');
});

test('documenti page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.documenti'));

    $response->assertStatus(200);
    $response->assertSee('Documenti');
    $response->assertSee('Regolamento Comunale');
    $response->assertSee('Bilancio 2024');
    $response->assertSee('Modulo Richiesta Carta d\'Identità');
});

test('eventi page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.eventi'));

    $response->assertStatus(200);
    $response->assertSee('Eventi');
    $response->assertSee('Festa del Patrono');
    $response->assertSee('Mercato Contadino');
    $response->assertSee('Consiglio Comunale');
});

test('anagrafe page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.anagrafe'));

    $response->assertStatus(200);
    $response->assertSee('Anagrafe');
});

test('tributi page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.tributi'));

    $response->assertStatus(200);
    $response->assertSee('Tributi');
});

test('urbanistica page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.urbanistica'));

    $response->assertStatus(200);
    $response->assertSee('Urbanistica');
});

test('prenotazioni page loads successfully', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.prenotazioni'));

    $response->assertStatus(200);
    $response->assertSee('Prenotazioni');
});

test('contact form validation', function () {
    /** @var TestCase $this */
    $response = $this->post(route('comune.contatti.send'), []);

    $response->assertSessionHasErrors(['nome', 'email', 'oggetto', 'messaggio']);
});

test('contact form email validation', function () {
    /** @var TestCase $this */
    $response = $this->post(route('comune.contatti.send'), [
        'nome' => 'Test User',
        'email' => 'invalid-email',
        'oggetto' => 'Test Subject',
        'messaggio' => 'Test Message',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact form success', function () {
    /** @var TestCase $this */
    $response = $this->post(route('comune.contatti.send'), [
        'nome' => 'Test User',
        'email' => 'test@example.com',
        'oggetto' => 'Test Subject',
        'messaggio' => 'Test Message',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

test('responsive design', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.homepage'));

    $response->assertStatus(200);
    $response->assertSee('viewport');
    $response->assertSee('width=device-width');
    $response->assertSee('initial-scale=1');
});

test('accessibility features', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.homepage'));

    $response->assertStatus(200);
    $response->assertSee('alt=');
    $response->assertSee('aria-label');
    $response->assertSee('role=');
});

test('bootstrap italia integration', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.homepage'));

    $response->assertStatus(200);
    $response->assertSee('bootstrap-italia');
    $response->assertSee('it-header-wrapper');
    $response->assertSee('it-footer');
});

test('leaflet integration', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.contatti'));

    $response->assertStatus(200);
    $response->assertSee('leaflet');
    $response->assertSee('L.map');
});

test('font awesome integration', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.homepage'));

    $response->assertStatus(200);
    $response->assertSee('font-awesome');
    $response->assertSee('fas fa-');
});
