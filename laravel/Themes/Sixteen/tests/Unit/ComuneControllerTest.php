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

test('homepage returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.homepage'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.homepage');
});

test('homepage displays recent tickets', function () {
    /** @var TestCase $this */
    $ticket = Ticket::factory()->create([
        'name' => 'Test Ticket',
        'description' => 'Test Description',
    ]);

    $response = $this->get(route('comune.homepage'));

    $response->assertSee('Test Ticket');
    $response->assertSee('Test Description');
});

test('homepage displays recent news', function () {
    /** @var TestCase $this */
    $news = News::factory()->create([
        'title' => 'Test News',
        'excerpt' => 'Test Excerpt',
    ]);

    $response = $this->get(route('comune.homepage'));

    $response->assertSee('Test News');
    $response->assertSee('Test Excerpt');
});

test('servizi returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.servizi'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.servizi');
});

test('servizi displays services', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.servizi'));

    $response->assertSee('Segnalazioni');
    $response->assertSee('Servizi');
    $response->assertSee('Anagrafe');
    $response->assertSee('Tributi');
});

test('novita returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.novita'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.novita');
});

test('novita displays news', function () {
    /** @var TestCase $this */
    $news = News::factory()->count(5)->create();

    $response = $this->get(route('comune.novita'));

    foreach ($news as $article) {
        $response->assertSee($article->title);
    }
});

test('contatti returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.contatti'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.contatti');
});

test('contatti displays contact info', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.contatti'));

    $response->assertSee((string) config('comune.nome'));
    $response->assertSee((string) config('comune.indirizzo'));
    $response->assertSee((string) config('comune.telefono'));
    $response->assertSee((string) config('comune.email'));
});

test('documenti returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.documenti'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.documenti');
});

test('eventi returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.eventi'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.eventi');
});

test('anagrafe returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.anagrafe'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.anagrafe');
});

test('tributi returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.tributi'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.tributi');
});

test('urbanistica returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.urbanistica'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.urbanistica');
});

test('prenotazioni returns view', function () {
    /** @var TestCase $this */
    $response = $this->get(route('comune.prenotazioni'));

    $response->assertStatus(200);
    $response->assertViewIs('sixteen::pages.comune.prenotazioni');
});

test('send contact validates required fields', function () {
    /** @var TestCase $this */
    $response = $this->post(route('comune.contatti.send'), []);

    $response->assertSessionHasErrors(['nome', 'email', 'oggetto', 'messaggio']);
});

test('send contact validates email format', function () {
    /** @var TestCase $this */
    $response = $this->post(route('comune.contatti.send'), [
        'nome' => 'Test User',
        'email' => 'invalid-email',
        'oggetto' => 'Test Subject',
        'messaggio' => 'Test Message',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('send contact success', function () {
    /** @var TestCase $this */
    $response = $this->post(route('comune.contatti.send'), [
        'nome' => 'Test User',
        'email' => 'test@example.com',
        'oggetto' => 'Test Subject',
        'messaggio' => 'Test Message',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Messaggio inviato con successo!');
});
