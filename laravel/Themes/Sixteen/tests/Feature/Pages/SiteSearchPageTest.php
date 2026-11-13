<?php

declare(strict_types=1);

namespace Themes\Sixteen\Tests\Feature\Pages;

use Dom\HTMLDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Modules\Fixcity\Actions\Search\SearchFrontOfficeAction;
use Modules\Fixcity\Database\Factories\TicketFactory;
use Modules\Fixcity\Datas\FrontOfficeSearchItem;
use Modules\Fixcity\Enums\TicketStatusEnum;
use Modules\Fixcity\Enums\TicketTypeEnum;
use Tests\TestCase;

/**
 * Site-wide front-office search (`/{lang}/ricerca?q=`): public tickets, published posts and events, CMS pages by title.
 * Story: Modules/Fixcity/docs/stories/site-wide-front-office-search.story.md.
 * CMS pages are only READ here (Sushi over the tenant JSON); nothing is written to the content directory.
 */
final class SiteSearchPageTest extends TestCase
{
    /** @var list<string> */
    protected array $connectionsToTransact = ['mariadb', 'user', 'fixcity', 'blog'];

    /** `articles.type` (Parental STI) exists in the cloned schema; a full migrate would run DDL inside the transaction. */
    protected function runTestingMigrations(): void {}

    public function test_an_empty_query_returns_no_results(): void
    {
        $result = app(SearchFrontOfficeAction::class)->execute('   ', 'it');

        $this->assertTrue($result->isEmptyQuery());
        $this->assertSame([], $result->items);
        $this->assertSame(0, $result->count());
    }

    public function test_it_finds_public_tickets_published_posts_and_events_but_not_drafts(): void
    {
        $this->makeTicket('Zorbaquux lampione spento', TicketStatusEnum::IN_PROGRESS);
        $this->makeTicket('Zorbaquux bozza privata', TicketStatusEnum::DRAFT);
        $this->makeArticle('post', 'Zorbaquux notizia pubblicata', Carbon::now()->subDay());
        $this->makeArticle('post', 'Zorbaquux notizia futura', Carbon::now()->addWeek());
        $this->makeArticle('post', 'Zorbaquux notizia bozza', null);
        $this->makeArticle('event', 'Zorbaquux evento in piazza', Carbon::now()->subDay(), Carbon::now()->addDays(3));

        $result = app(SearchFrontOfficeAction::class)->execute('zorbaquux', 'it');
        $byTitle = [];
        foreach ($result->items as $item) {
            $byTitle[$item->title] = $item;
        }

        $this->assertEqualsCanonicalizing(
            ['Zorbaquux lampione spento', 'Zorbaquux notizia pubblicata', 'Zorbaquux evento in piazza'],
            array_keys($byTitle),
        );
        $this->assertSame(3, $result->count());
        $this->assertSame('ticket', $byTitle['Zorbaquux lampione spento']->type);
        $this->assertSame('post', $byTitle['Zorbaquux notizia pubblicata']->type);
        $this->assertSame('event', $byTitle['Zorbaquux evento in piazza']->type);
        $this->assertStringContainsString('/tickets/', $byTitle['Zorbaquux lampione spento']->url);
        $this->assertStringContainsString('/blog/', $byTitle['Zorbaquux notizia pubblicata']->url);
        $this->assertStringContainsString('/events/', $byTitle['Zorbaquux evento in piazza']->url);
        $this->assertSame('Segnalazione', $byTitle['Zorbaquux lampione spento']->typeLabel);
    }

    public function test_it_finds_public_cms_pages_by_their_title_in_the_requested_locale(): void
    {
        $italian = $this->pageTitles(app(SearchFrontOfficeAction::class)->execute('mappa del', 'it')->items);
        $english = $this->pageTitles(app(SearchFrontOfficeAction::class)->execute('sitemap', 'en')->items);
        $self = $this->pageTitles(app(SearchFrontOfficeAction::class)->execute('cerca nel sito', 'it')->items);

        $this->assertSame(['Mappa del sito'], array_keys($italian));
        $this->assertStringEndsWith('/mappa-sito', $italian['Mappa del sito']);
        $this->assertSame(['Sitemap'], array_keys($english));
        // the results page itself is never a result
        $this->assertArrayNotHasKey('Cerca nel sito', $self);
    }

    public function test_the_results_page_is_localized_and_prompts_for_a_query(): void
    {
        foreach (['it' => 'Cerca nel sito', 'en' => 'Search the site', 'de' => 'Website durchsuchen', 'es' => 'Buscar en el sitio'] as $locale => $heading) {
            $html = $this->get('/'.$locale.'/ricerca')->assertOk()->getContent();
            $this->assertIsString($html);
            $document = HTMLDocument::createFromString($html, LIBXML_NOERROR);

            $this->assertSame($heading, trim((string) $document->querySelector('h1')?->textContent), $locale);
            $this->assertNotNull($document->querySelector('[data-element="breadcrumb"]'), $locale);
            $form = $document->querySelector('form[role="search"]#site-search-form');
            $this->assertNotNull($form, $locale);
            $this->assertStringEndsWith('/'.$locale.'/ricerca', (string) $form->getAttribute('action'));
            $this->assertNotNull($form->querySelector('input[name="q"]'), $locale);
            $this->assertNotNull($document->querySelector('[data-element="search-empty-query"]'), $locale);
        }
    }

    public function test_the_results_page_lists_live_results_and_announces_the_count(): void
    {
        $this->makeTicket('Zorbaquux tombino rotto', TicketStatusEnum::IN_PROGRESS);
        $this->makeArticle('post', 'Zorbaquux avviso ai cittadini', Carbon::now()->subDay());

        $html = $this->get('/it/ricerca?q=zorbaquux')->assertOk()->getContent();
        $this->assertIsString($html);
        $document = HTMLDocument::createFromString($html, LIBXML_NOERROR);

        $status = $document->querySelector('[role="status"]');
        $this->assertNotNull($status);
        $this->assertStringContainsString('2 risultati', Str::squish((string) $status->textContent));
        $links = [];
        foreach ($document->querySelectorAll('ul[data-element="search-results"] > li a') as $link) {
            $links[trim((string) $link->textContent)] = (string) $link->getAttribute('href');
        }
        $this->assertArrayHasKey('Zorbaquux tombino rotto', $links);
        $this->assertArrayHasKey('Zorbaquux avviso ai cittadini', $links);
        $this->assertSame('zorbaquux', $document->querySelector('#site-search-form input[name="q"]')?->getAttribute('value'));
    }

    public function test_the_header_search_modal_sends_q_to_the_results_page(): void
    {
        $this->makeTicket('Cestino rotto Zorbaquux', TicketStatusEnum::IN_PROGRESS);
        $this->makeTicket('Buca in via Solferino', TicketStatusEnum::IN_PROGRESS);

        $home = $this->get('/it')->assertOk()->getContent();
        $this->assertIsString($home);
        $form = HTMLDocument::createFromString($home, LIBXML_NOERROR)->querySelector('#search-modal form');
        $this->assertNotNull($form);
        $url = (string) $form->getAttribute('action').'?'.http_build_query(['q' => 'cestino rotto']);

        $this->get($url)->assertOk()->assertSee('Cestino rotto Zorbaquux')->assertDontSee('Buca in via Solferino')->assertSee('1 risultato');
    }

    public function test_a_query_without_matches_says_so(): void
    {
        $html = $this->get('/en/ricerca?q=qwxzvnothinghere')->assertOk()->getContent();
        $this->assertIsString($html);
        $document = HTMLDocument::createFromString($html, LIBXML_NOERROR);

        $this->assertStringContainsString('0 results', Str::squish((string) $document->querySelector('[role="status"]')?->textContent));
        $this->assertNotNull($document->querySelector('[data-element="search-no-results"]'));
    }

    /**
     * @param  list<FrontOfficeSearchItem>  $items
     * @return array<string, string> title => url of the CMS page results
     */
    private function pageTitles(array $items): array
    {
        $pages = [];
        foreach ($items as $item) {
            if ($item->type === 'page') {
                $pages[$item->title] = $item->url;
            }
        }

        return $pages;
    }

    private function makeTicket(string $name, TicketStatusEnum $status): void
    {
        TicketFactory::new()->createOne([
            'name' => $name,
            'status' => $status,
            'type' => TicketTypeEnum::ROAD_MAINTENANCE,
            'location' => ['lat' => 43.7696, 'lng' => 11.2558, 'address' => 'Via Solferino - 50100 Firenze'],
        ]);
    }

    private function makeArticle(string $contentType, string $title, ?Carbon $publishedAt, ?Carbon $eventStart = null): void
    {
        $article = new Article;
        $article->forceFill([
            'uuid' => (string) Str::uuid(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(6)),
            'description' => $title.' description',
            'content_blocks' => [['type' => 'paragraph', 'data' => ['text' => '<p>'.$title.'</p>']]],
            'sidebar_blocks' => [],
            'footer_blocks' => [],
            'type' => $contentType,
            'published_at' => $publishedAt,
            'event_start_date' => $eventStart,
        ]);
        $article->save();
    }
}
