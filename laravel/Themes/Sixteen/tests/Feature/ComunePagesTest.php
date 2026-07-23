<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Fixcity\App\Models\News;
use Modules\Fixcity\App\Models\Ticket;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->artisan('migrate', ['--database' => 'testing']);
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
    $ticket = Ticket::factory()->create([
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
    $news = News::factory()->create([
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
    $response = $this->get(route('comune.servizi'));

    $response->assertStatus(200);
    $response->assertSee('Servizi del Comune');
    $response->assertSee('Segnalazioni');
    $response->assertSee('Prenotazione Appuntamenti');
    $response->assertSee('Documenti e Moduli');
});

test('servizi page displays service categories', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.servizi'));

    $response->assertSee('Anagrafe');
    $response->assertSee('Tributi');
    $response->assertSee('Urbanistica');
    $response->assertSee('Sociale');
    $response->assertSee('Cultura');
    $response->assertSee('Ambiente');
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
    $news = News::factory()->count(3)->create([
        'title' => 'Notizia Test',
        'excerpt' => 'Estratto notizia test',
    ]);

    $response = $this->get(route('comune.novita'));

    foreach ($news as $article) {
        $response->assertSee($article->title);
        $response->assertSee($article->excerpt);
    }
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

    $response->assertSee((string) config('comune.nome'));
    $response->assertSee((string) config('comune.indirizzo'));
    $response->assertSee((string) config('comune.telefono'));
    $response->assertSee((string) config('comune.email'));
    $response->assertSee((string) config('comune.pec'));
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
