<?php

declare(strict_types=1);

use Modules\Fixcity\Database\Factories\TicketFactory;
use Modules\Fixcity\Enums\TicketStatusEnum;
use Modules\Fixcity\Enums\TicketTypeEnum;
use Tests\TestCase;

uses(TestCase::class);

/*
 * "Elenco segnalazioni" (riferimento design-comuni-pagine-statiche `segnalazioni-elenco.html`).
 * In origine l'elenco era la homepage (`/it`). Oggi la homepage sono i blocchi di `pages/home.json`
 * (hero, mappa, come funziona, azioni rapide: story homepage-design-comuni-rebuild) e l'elenco vive su
 * `/it/tickets` (`pages/tickets/index.json`, blocco `ticket-list/design-comuni`, DCP-104). La pagina di
 * parita' `tests.ticket-list.json` e' stata rimossa (e040344c0b) e sostituita da `tests.segnalazioni-elenco.json`.
 * Story: Themes/Sixteen/docs/stories/sixteen-suite-stale-contracts.story.md
 */
const TICKET_LIST_URL = '/it/tickets';

/** Il rail dei filtri (category-list, "Filtra") compare solo se c'e' qualcosa da filtrare: serve una segnalazione pubblica. */
function seedPublicTicket(): void
{
    TicketFactory::new()->createOne([
        'name' => 'Lampione spento',
        'status' => TicketStatusEnum::IN_PROGRESS,
        'type' => TicketTypeEnum::ROAD_MAINTENANCE,
        'location' => ['lat' => 43.7696, 'lng' => 11.2558, 'address' => 'Via Solferino, Firenze'],
    ]);
}

test('homepage loads successfully', function (): void {
    /** @var TestCase $this */
    $response = $this->get('/it');

    $response->assertStatus(200);
});

test('ticket list page has required components', function (): void {
    /** @var TestCase $this */
    seedPublicTicket();
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('nav-tabs');
    $response->assertSee('category-list');
    $response->assertSee('map-lit');
});

test('ticket list page has breadcrumb', function (): void {
    /** @var TestCase $this */
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('breadcrumb');
    $response->assertSee('Home');
    $response->assertSee('Elenco segnalazioni');
});

test('ticket list page has map and list tabs', function (): void {
    /** @var TestCase $this */
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('Mappa');
    $response->assertSee('Elenco');
});

test('ticket list page has filters', function (): void {
    /** @var TestCase $this */
    seedPublicTicket();
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('Filtra');
});

test('ticket list page has cta section', function (): void {
    /** @var TestCase $this */
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('Fai una segnalazione');
    $response->assertSee('Segnala');
});

/*
 * La fascia contatti dell'elenco reale non e' quella generica del riferimento ("Contatta il comune": chatbot, appuntamento):
 * dal blocco DCP-104 (commit e39f3cfda2, chiavi `fixcity::ticket_list.contacts`) offre l'aiuto proprio delle
 * segnalazioni, con titolo "Hai bisogno di aiuto?". La pagina di parita' /it/tests/segnalazioni-elenco resta "Contatta il comune".
 */
test('ticket list page has contacts section', function (): void {
    /** @var TestCase $this */
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('cmp-contacts', false);
    $response->assertSee('Hai bisogno di aiuto?');
    $response->assertSee('Leggi le domande frequenti');
    $response->assertSee('Traccia una segnalazione');
});

test('ticket list page has rating feedback', function (): void {
    /** @var TestCase $this */
    $response = $this->get(TICKET_LIST_URL);

    $response->assertStatus(200);
    $response->assertSee('Valuta da 1 a 5 stelle');
});

test('segnalazioni elenco test page loads successfully', function (): void {
    /** @var TestCase $this */
    $response = $this->get('/it/tests/segnalazioni-elenco');

    $response->assertStatus(200);
    $response->assertSee('Elenco segnalazioni');
});
