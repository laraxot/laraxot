<?php

declare(strict_types=1);

use Dom\Element;
use Dom\HTMLDocument;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as ViewComponent;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\Livewire;
use Modules\Xot\Tests\XotBaseTestCase;
use PHPUnit\Framework\Assert;
use Symfony\Component\Finder\Finder;

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_replace;

// XotBaseTestCase e non Tests\TestCase: questa non usa RefreshDatabase, qui nessuna riga di database serve (stato in memoria).
uses(XotBaseTestCase::class);

/*
 * DCP-43, passo 2 del wizard: contratto di markup delle due viste di STRUTTURA del tema (non ancora cablate in TicketForm):
 * `components/wizard/dc-section-card.blade.php` (scheda-sezione) e `components/wizard/data-step.blade.php` (layout del passo con indice
 * laterale, colonna `.steppers-content` e navigazione Indietro/Avanti). Riferimento: sito/segnalazione-02-dati (sezioni report-place,
 * report-info, report-author). Specifica: story DCP-35, sezione "Specifica passo 2", righe 4-7, 10, 15, 16.
 *
 * Le viste girano dentro un componente Livewire di prova con Section Filament reali e stato in memoria, come in
 * Modules/Fixcity/tests/Unit/DcFieldViewsTest.php: nessun database, nessun file di lingua scritto nel repo (le stringhe di
 * `fixcity::ticket_form.*` arrivano da una cartella temporanea messa al posto del namespace, vedi dcwHtml()).
 *
 * Prova di efficacia: con la variabile d'ambiente DC_WIZARD_VIEWS_OVERRIDE=<cartella> le viste di quella cartella (stesso albero
 * `components/wizard/*.blade.php`) passano davanti a quelle del tema; serve a mostrare che i test falliscono su una copia rotta.
 */

/** Valore ostile: chiude l'attributo, apre uno script e prova ad aggiungere un gestore di evento. */
const DCW_EVIL = '"><script>alert(1)</script><b onmouseover="alert(2)" x="';

/** Chiavi `fixcity::ticket_form.*` lette dalla vista di passo (valori riconoscibili, diversi dal ripiego italiano). */
const DCW_LANG = [
    'required_notice' => 'TXT-required',
    'navscroll' => ['title' => 'TXT-nav-title'],
    'nav' => ['aria_label' => 'TXT-aria', 'back' => 'TXT-back', 'next' => 'TXT-next'],
];

/** Il passo "dati" come lo cablera' TicketForm: View(data-step) con tre Section(dc-section-card), l'ultima nascondibile (Autore, ospite). */
final class DcWizardProbe extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public string $placeHeading = 'Luogo';

    public string $placeDescription = 'Indica il luogo del disservizio';

    public string $placeId = 'report-place';

    /** `last` passato dal chiamante alla prima scheda (null = automatico). */
    public ?bool $placeLast = null;

    public string $infoHeading = 'Disservizio';

    public bool $infoRequired = true;

    public string $authorHeading = 'Autore della segnalazione';

    public bool $authorVisible = true;

    /** `last` passato dal chiamante all'ultima scheda (null = automatico). */
    public ?bool $authorLast = null;

    /** true = le Section stanno direttamente nel form, senza la vista data-step (prova la scheda da sola). */
    public bool $standalone = false;

    public function form(Schema $schema): Schema
    {
        $card = 'pub_theme::components.wizard.dc-section-card';

        // Il form di Filament ha il gap acceso (`fi-sc-has-gap`, 24 px): le viste devono spegnerlo da sole, la sonda non lo spegne per loro.
        $sections = [
            Section::make($this->placeHeading)->description($this->placeDescription)->id($this->placeId)
                ->view($card, ['cardClass' => 'p-big p-lg-4', 'last' => $this->placeLast])
                ->schema([Html::make('<p data-child="place">Campo luogo</p>')]),
            Section::make($this->infoHeading)->id('report-info')
                ->view($card, ['cardClass' => 'p-big', 'required' => $this->infoRequired])
                ->schema([Html::make('<p data-child="info">Campo disservizio</p>')]),
            Section::make($this->authorHeading)->description('Informazione su di te')->id('report-author')
                ->view($card, ['last' => $this->authorLast])
                ->visible($this->authorVisible)
                ->schema([Html::make('<p data-child="author">Campo autore</p>')]),
        ];

        return $schema->statePath('data')->gap()->components(
            $this->standalone ? $sections : [ViewComponent::make('pub_theme::components.wizard.data-step')->schema($sections)],
        );
    }

    public function render(): string
    {
        return '<div class="page-content" data-dc>{{ $this->form }}</div>';
    }
}

/** Mette davanti le viste della cartella indicata (solo per la prova di efficacia); va chiamata prima del primo render. */
function dcwUseOverrideViews(): void
{
    $override = getenv('DC_WIZARD_VIEWS_OVERRIDE');
    if (is_string($override) && $override !== '') {
        View::prependNamespace('pub_theme', $override);
    }
}

/**
 * @param  array<string, mixed>  $props  proprieta pubbliche della sonda
 * @param  array<string, mixed>  $lang  contenuto di `fixcity::ticket_form` (it); [] = chiavi assenti, la vista usa il ripiego italiano
 */
function dcwHtml(array $props = [], array $lang = DCW_LANG): string
{
    // La chiave di lingua mancante scrive la riga nel DB e il file lang: nei test mai.
    config(['lang.save_missing_translations' => false]);
    app()->setLocale('it');
    dcwUseOverrideViews();

    $directory = sys_get_temp_dir().'/dc-wizard-lang-'.bin2hex(random_bytes(6));
    File::ensureDirectoryExists($directory.'/it');
    File::put($directory.'/it/ticket_form.php', '<?php return '.var_export($lang, true).';');
    Lang::getLoader()->addNamespace('fixcity', $directory);

    try {
        return Livewire::test(DcWizardProbe::class, $props)->html();
    } finally {
        File::deleteDirectory($directory);
    }
}

/**
 * @param  array<string, mixed>  $props
 * @param  array<string, mixed>  $lang
 */
function dcwDoc(array $props = [], array $lang = DCW_LANG): HTMLDocument
{
    return HTMLDocument::createFromString(dcwHtml($props, $lang), LIBXML_NOERROR);
}

function dcwOne(HTMLDocument|Element $scope, string $selector): Element
{
    return $scope->querySelector($selector) ?? Assert::fail('Selettore non trovato: '.$selector);
}

/**
 * @return list<Element>
 */
function dcwAll(HTMLDocument|Element $scope, string $selector): array
{
    $elements = [];
    foreach ($scope->querySelectorAll($selector) as $element) {
        $elements[] = $element;
    }

    return $elements;
}

function dcwText(Element $element): string
{
    return trim($element->textContent ?? '');
}

/**
 * @return list<string>
 */
function dcwClasses(Element $element): array
{
    $classes = array_values(array_filter(explode(' ', $element->className), static fn (string $class): bool => $class !== ''));
    sort($classes);

    return $classes;
}

/** Ritrova la `section` di una scheda a partire dall'id (ordine del DOM). */
function dcwSection(HTMLDocument $document, string $id): Element
{
    return $document->getElementById($id) ?? Assert::fail('Nessuna sezione con id '.$id);
}

/** La `.cmp-card` della scheda (porta `mb-40`). */
function dcwCmpCard(Element $section): Element
{
    return dcwOne($section, 'section > .cmp-card');
}

// ---------------------------------------------------------------------------------------------------------------------
// dc-section-card: la scheda-sezione
// ---------------------------------------------------------------------------------------------------------------------

it('renders dc-section-card as the reference section card', function (): void {
    $document = dcwDoc();
    $place = dcwSection($document, 'report-place');

    // section.it-page-section[id] > .cmp-card > .card.has-bkg-grey con intestazione e corpo.
    Assert::assertSame('SECTION', $place->nodeName);
    Assert::assertTrue($place->classList->contains('it-page-section'));
    $card = dcwOne($place, 'section.it-page-section > .cmp-card > .card.has-bkg-grey');
    Assert::assertTrue($card->classList->contains('shadow-sm'));

    $title = dcwOne($card, '.card-header > .d-flex > h2.title-xxlarge');
    Assert::assertSame('Luogo', dcwText($title));
    Assert::assertSame('Indica il luogo del disservizio', dcwText(dcwOne($card, '.card-header > p.subtitle-small.mb-0')));

    // I campi della Section stanno nel corpo della scheda, non fuori.
    $body = dcwOne($card, '.card-body.p-0');
    Assert::assertSame('Campo luogo', dcwText(dcwOne($body, '[data-child="place"]')));
    Assert::assertCount(0, dcwAll($document, '#report-info [data-child="place"]'));
});

it('renders one section per visible Section, in order, each with its own id', function (): void {
    $document = dcwDoc();
    $ids = array_map(static fn (Element $section): string => $section->getAttribute('id') ?? '', dcwAll($document, 'section.it-page-section'));

    Assert::assertSame(['report-place', 'report-info', 'report-author'], $ids);
});

it('marks the title with icon-required only when the card is required', function (): void {
    $document = dcwDoc();

    $required = dcwOne(dcwSection($document, 'report-info'), 'h2.title-xxlarge');
    Assert::assertTrue($required->classList->contains('icon-required'));
    Assert::assertTrue($required->classList->contains('mb-3'));
    Assert::assertFalse($required->classList->contains('mb-1'));

    foreach (['report-place', 'report-author'] as $id) {
        $plain = dcwOne(dcwSection($document, $id), 'h2.title-xxlarge');
        Assert::assertFalse($plain->classList->contains('icon-required'), 'icon-required su una scheda non obbligatoria: '.$id);
        Assert::assertTrue($plain->classList->contains('mb-1'));
    }

    $off = dcwDoc(['infoRequired' => false]);
    Assert::assertCount(0, dcwAll($off, '.icon-required'));
});

it('applies the card modifiers passed by the caller and nothing else', function (): void {
    $document = dcwDoc();

    Assert::assertSame(['card', 'has-bkg-grey', 'p-big', 'p-lg-4', 'shadow-sm'], dcwClasses(dcwOne(dcwSection($document, 'report-place'), '.card')));
    Assert::assertSame(['card', 'has-bkg-grey', 'p-big', 'shadow-sm'], dcwClasses(dcwOne(dcwSection($document, 'report-info'), '.card')));
    // cardClass vuoto: nessuna classe in piu' e nessuno spazio spurio.
    Assert::assertSame(['card', 'has-bkg-grey', 'shadow-sm'], dcwClasses(dcwOne(dcwSection($document, 'report-author'), '.card')));
});

it('omits the subtitle when the section has no description', function (): void {
    $document = dcwDoc(['placeDescription' => '']);

    Assert::assertCount(0, dcwAll(dcwSection($document, 'report-place'), 'p.subtitle-small'));
    Assert::assertCount(0, dcwAll(dcwSection($document, 'report-info'), 'p.subtitle-small'), 'Il Disservizio non ha descrizione.');
    Assert::assertCount(1, dcwAll(dcwSection($document, 'report-author'), 'p.subtitle-small'));
});

it('gives mb-40 to every card except the last visible one', function (): void {
    $document = dcwDoc();
    $mb40 = static fn (string $id): bool => dcwCmpCard(dcwSection($document, $id))->classList->contains('mb-40');

    Assert::assertTrue($mb40('report-place'));
    Assert::assertTrue($mb40('report-info'));
    Assert::assertFalse($mb40('report-author'), 'L\'ultima scheda visibile non ha mb-40.');
});

it('moves mb-40 off the previous card when the last section is hidden', function (): void {
    $document = dcwDoc(['authorVisible' => false]);

    Assert::assertTrue(dcwCmpCard(dcwSection($document, 'report-place'))->classList->contains('mb-40'));
    Assert::assertFalse(dcwCmpCard(dcwSection($document, 'report-info'))->classList->contains('mb-40'), 'Con l\'Autore nascosto l\'ultima e\' il Disservizio.');
    Assert::assertNull($document->getElementById('report-author'));
});

it('lets the caller override the automatic last card with the last view data', function (): void {
    $document = dcwDoc(['placeLast' => true, 'authorLast' => false]);

    Assert::assertFalse(dcwCmpCard(dcwSection($document, 'report-place'))->classList->contains('mb-40'));
    Assert::assertTrue(dcwCmpCard(dcwSection($document, 'report-author'))->classList->contains('mb-40'));
});

it('turns off the Filament gap and grid wrappers inside the card body, also without the step view', function (): void {
    foreach ([[], ['standalone' => true]] as $props) {
        $document = dcwDoc($props);

        foreach (['report-place', 'report-info', 'report-author'] as $id) {
            $schema = dcwOne(dcwSection($document, $id), '.card-body > .d-block');
            Assert::assertTrue($schema->classList->contains('fi-sc'));
            Assert::assertFalse($schema->classList->contains('fi-sc-has-gap'), 'Il gap di 24 px rompe la geometria del riferimento: '.$id);
        }
        Assert::assertCount(0, dcwAll($document, '.fi-section-content'), 'Il contenitore di Filament ha sfondo e padding globali.');
    }
});

it('turns off the gap and the grid of the schema that holds the cards in data-step', function (): void {
    $container = dcwOne(dcwDoc(), '.it-page-sections-container > .fi-sc');

    Assert::assertTrue($container->classList->contains('d-block'));
    Assert::assertFalse($container->classList->contains('fi-sc-has-gap'), 'Il gap tra le schede sposterebbe la navigazione.');
});

// ---------------------------------------------------------------------------------------------------------------------
// data-step: il layout del passo
// ---------------------------------------------------------------------------------------------------------------------

it('renders the data step as the reference two-column layout', function (): void {
    $document = dcwDoc();

    $notice = dcwOne($document, 'p.title-xsmall.d-lg-none.my-5');
    Assert::assertSame('TXT-required', dcwText($notice));

    // L'avviso precede la riga; la riga ha la colonna dell'indice (solo >= 992 px) e quella delle schede.
    $row = dcwOne($document, 'div.row');
    Assert::assertTrue($notice->nextElementSibling?->isSameNode($row) ?? false, 'L\'avviso e\' il fratello che precede la riga.');
    $navColumn = dcwOne($row, 'div.row > div.col-12.col-lg-3.d-lg-block.mb-4.d-none');
    $contentColumn = dcwOne($row, 'div.row > div.col-12.col-lg-8.offset-lg-1');
    Assert::assertTrue($navColumn->nextElementSibling?->isSameNode($contentColumn) ?? false);
    Assert::assertTrue($navColumn->querySelector('.cmp-navscroll') !== null);
    Assert::assertTrue($contentColumn->querySelector('.steppers-content') !== null);
});

it('keeps the navscroll in wire:ignore and the fields outside of it', function (): void {
    $document = dcwDoc();

    // dcNavscroll mette x-data da JS: un morph di Livewire lo perderebbe senza wire:ignore.
    $navscroll = dcwOne($document, '.cmp-navscroll');
    $ignored = dcwOne($navscroll, 'nav[data-bs-navscroll]')->closest('[wire\\:ignore]') ?? Assert::fail('Il navscroll non e\' in wire:ignore.');
    Assert::assertTrue($ignored->contains($navscroll));

    // Le schede con i campi NON possono stare in wire:ignore: Livewire non aggiornerebbe piu' valori ed errori.
    foreach (dcwAll($document, 'section.it-page-section') as $section) {
        Assert::assertNull($section->closest('[wire\\:ignore]'), 'Una scheda e\' dentro wire:ignore.');
    }
    Assert::assertNull(dcwOne($document, '.cmp-nav-steps')->closest('[wire\\:ignore]'));
});

it('builds the navscroll entries from the visible sections, in order', function (): void {
    $document = dcwDoc();

    $nav = dcwOne($document, '.cmp-navscroll');
    Assert::assertSame('TXT-nav-title', dcwText(dcwOne($nav, '.accordion-button')));
    Assert::assertSame('TXT-nav-title', $nav->querySelector('nav[data-bs-navscroll]')?->getAttribute('aria-label'));

    $links = dcwAll($nav, 'ul.link-list[data-element="page-index"] > li.nav-item > a.nav-link');
    Assert::assertSame(['#report-place', '#report-info', '#report-author'], array_map(static fn (Element $link): string => $link->getAttribute('href') ?? '', $links));
    Assert::assertSame(['Luogo', 'Disservizio', 'Autore della segnalazione'], array_map(static fn (Element $link): string => dcwText($link), $links));

    // Ogni voce punta all'id della sezione nella stessa posizione (l'indice di dcNavscroll e' la posizione).
    $sections = dcwAll($document, 'section.it-page-section');
    Assert::assertCount(3, $sections);
    foreach ($links as $position => $link) {
        Assert::assertSame('#'.$sections[$position]->getAttribute('id'), $link->getAttribute('href'));
    }
});

it('drops a hidden section from the navscroll as well', function (): void {
    $document = dcwDoc(['authorVisible' => false]);

    Assert::assertNull($document->getElementById('report-author'));
    $links = dcwAll($document, '.cmp-navscroll a.nav-link');
    Assert::assertSame(['#report-place', '#report-info'], array_map(static fn (Element $link): string => $link->getAttribute('href') ?? '', $links));
    Assert::assertStringNotContainsString('Autore della segnalazione', dcwText(dcwOne($document, '.cmp-navscroll')));
});

it('puts the sections container and then the step navigation inside steppers-content', function (): void {
    $document = dcwDoc();

    $content = dcwOne($document, '.steppers-content');
    Assert::assertSame('polite', $content->getAttribute('aria-live'));

    // Solo due figli, in questo ordine: le schede e la navigazione (DENTRO la colonna, non nel footer di Filament).
    $children = [];
    for ($child = $content->firstElementChild; $child !== null; $child = $child->nextElementSibling) {
        $children[] = $child->className;
    }
    Assert::assertSame(['it-page-sections-container', 'cmp-nav-steps'], $children);
    Assert::assertCount(3, dcwAll(dcwOne($content, '.it-page-sections-container'), 'section.it-page-section'));
    Assert::assertCount(3, dcwAll($document, 'section.it-page-section'), 'Nessuna scheda fuori dal contenitore.');
});

it('renders Indietro and Avanti wired to the wizard, with no Salva Richiesta', function (): void {
    $document = dcwDoc();

    $nav = dcwOne($document, '.steppers-content > .cmp-nav-steps > nav.steppers-nav');
    Assert::assertSame('TXT-aria', $nav->getAttribute('aria-label'));
    $buttons = dcwAll($nav, 'button');
    Assert::assertCount(2, $buttons, 'Solo Indietro e Avanti.');
    foreach ($buttons as $button) {
        Assert::assertSame('button', $button->getAttribute('type'), 'Un pulsante senza type="button" invierebbe il form.');
        Assert::assertFalse($button->hasAttribute('data-bs-toggle') || $button->hasAttribute('data-bs-target'), 'Nessun Bootstrap JS sui pulsanti.');
    }

    [$back, $next] = $buttons;
    Assert::assertTrue($back->classList->contains('steppers-btn-prev'));
    Assert::assertSame('TXT-back', dcwText($back));
    Assert::assertSame('goToPreviousStep()', $back->getAttribute('x-on:click'));
    Assert::assertTrue($next->classList->contains('steppers-btn-confirm'));
    Assert::assertSame('TXT-next', dcwText($next));
    // DCP-286: dopo un "Avanti" respinto il focus va al primo campo non valido; il click continua a partire da requestNextStep()
    Assert::assertStringStartsWith('await requestNextStep();', (string) $next->getAttribute('x-on:click'));
    Assert::assertStringContainsString('[aria-invalid=true]', (string) $next->getAttribute('x-on:click'));

    // Le icone sono decorative e puntano agli sprite chevron del tema.
    Assert::assertStringEndsWith('#it-chevron-left', dcwOne($back, 'svg.icon[aria-hidden="true"] use')->getAttribute('href') ?? '');
    Assert::assertStringEndsWith('#it-chevron-right', dcwOne($next, 'svg.icon[aria-hidden="true"] use')->getAttribute('href') ?? '');

    // Nessuna bozza (DCP-33, decisione 4): niente Salva, niente chiamata a save().
    Assert::assertCount(0, dcwAll($document, '.steppers-btn-save'));
    Assert::assertSame(0, preg_match('/salva/i', dcwText($document->documentElement ?? Assert::fail('Documento vuoto.'))), 'Il testo "Salva" non deve comparire nel passo.');
    Assert::assertStringNotContainsString('$wire.save', dcwHtml());
});

it('falls back to the Italian texts when the language keys do not exist yet', function (): void {
    $document = dcwDoc(lang: []);

    Assert::assertSame('I campi contraddistinti dal simbolo asterisco sono obbligatori', dcwText(dcwOne($document, 'p.title-xsmall.d-lg-none')));
    Assert::assertSame('INFORMAZIONI RICHIESTE', dcwText(dcwOne($document, '.cmp-navscroll .accordion-button')));
    Assert::assertSame('Step', dcwOne($document, '.cmp-nav-steps nav')->getAttribute('aria-label'));
    Assert::assertSame('Indietro', dcwText(dcwOne($document, '.steppers-btn-prev')));
    Assert::assertSame('Avanti', dcwText(dcwOne($document, '.steppers-btn-confirm')));
});

// ---------------------------------------------------------------------------------------------------------------------
// Uscita escapata e sorgente
// ---------------------------------------------------------------------------------------------------------------------

it('escapes headings, descriptions, ids and language texts in both views', function (): void {
    $lang = [
        'required_notice' => DCW_EVIL,
        'navscroll' => ['title' => DCW_EVIL],
        'nav' => ['aria_label' => DCW_EVIL, 'back' => DCW_EVIL, 'next' => DCW_EVIL],
    ];
    $html = dcwHtml(['placeHeading' => DCW_EVIL, 'placeDescription' => DCW_EVIL, 'placeId' => DCW_EVIL, 'infoHeading' => DCW_EVIL, 'authorHeading' => DCW_EVIL], $lang);
    $document = HTMLDocument::createFromString($html, LIBXML_NOERROR);

    Assert::assertStringNotContainsString('<script', $html);
    Assert::assertStringNotContainsString('onmouseover="', $html);
    Assert::assertCount(0, dcwAll($document, 'script'), 'Un valore con <script> e\' diventato un elemento.');
    Assert::assertCount(0, dcwAll($document, '[onmouseover]'), 'Un valore con " ha aggiunto un attributo.');

    // Il testo ostile resta leggibile come testo (escapato, non scartato). L'id lo ripulisce gia' Filament (getId() toglie `"<>`):
    // qui conta che non apra attributi e che l'ancora dell'indice coincida con l'id della scheda.
    $place = dcwOne($document, 'section.it-page-section');
    $placeId = $place->getAttribute('id') ?? '';
    Assert::assertSame(0, preg_match('/["<>]/', $placeId), 'L\'id della scheda contiene virgolette o parentesi.');
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne($place, 'h2.title-xxlarge')));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne($place, 'p.subtitle-small')));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne(dcwSection($document, 'report-info'), 'h2.title-xxlarge')));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne(dcwSection($document, 'report-author'), 'h2.title-xxlarge')));

    $links = dcwAll($document, '.cmp-navscroll a.nav-link');
    Assert::assertCount(3, $links);
    Assert::assertSame('#'.$placeId, $links[0]->getAttribute('href'));
    Assert::assertSame(DCW_EVIL, dcwText($links[0]));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne($document, 'p.title-xsmall.d-lg-none')));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne($document, '.cmp-navscroll .accordion-button')));
    Assert::assertSame(DCW_EVIL, dcwOne($document, '.cmp-nav-steps nav')->getAttribute('aria-label'));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne($document, '.steppers-btn-prev')));
    Assert::assertSame(DCW_EVIL, dcwText(dcwOne($document, '.steppers-btn-confirm')));
});

/**
 * I nomi dei file (Finder e non facciate: il dataset si calcola al caricamento del file, prima dell'avvio dell'app).
 *
 * @return array<string, array{0: string}>
 */
function dcwViewNames(): array
{
    $datasets = [];
    foreach ((new Finder)->files()->in(dirname(__DIR__, 2).'/resources/views/components/wizard')->name(['dc-section-card.blade.php', 'data-step.blade.php']) as $file) {
        $datasets[$file->getFilename()] = [str_replace('.blade.php', '', $file->getFilename())];
    }

    return $datasets;
}

it('keeps the view source free of important and unescaped output', function (string $name): void {
    dcwUseOverrideViews();
    $path = View::getFinder()->find('pub_theme::components.wizard.'.$name);
    // I commenti Blade `{{-- --}}` documentano la vista (e citano `{!! !!}`): fuori dal controllo.
    $source = preg_replace('/\{\{--.*?--\}\}/s', '', file_get_contents($path));

    Assert::assertNotSame('', $source);
    Assert::assertSame(0, preg_match('/!important/i', $source), $name.': !important');
    Assert::assertStringNotContainsString('{!!', $source);
    Assert::assertStringNotContainsString('!!}', $source);
    Assert::assertStringNotContainsString('<?=', $source);
    Assert::assertSame(0, preg_match('/\becho\b/', $source), $name.': echo grezzo');
})->with(dcwViewNames());
