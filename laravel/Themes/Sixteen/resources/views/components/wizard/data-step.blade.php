{{--
    Layout del passo "dati" del wizard /tickets/create (sito/segnalazione-02-dati, DCP-35 P2-A): avviso campi obbligatori (solo < 992 px), indice laterale
    `.cmp-navscroll`, colonna `.steppers-content` con le schede e la navigazione del passo (Indietro / Avanti) DENTRO la colonna.
    Uso: `Filament\Schemas\Components\View::make('pub_theme::components.wizard.data-step')->schema([Section ... ->view('pub_theme::components.wizard.dc-section-card')])`.
    Filament passa `$getChildSchema()` (le Section). Le voci dell'indice (ancora = `$getId()`, testo = `$getHeading()`) si ricavano dalle Section visibili:
    una scheda nascosta (es. Autore per l'ospite) sparisce anche dall'indice, e l'ordine resta quello atteso da `dcNavscroll` (indice = posizione).
    L'indice sta in `wire:ignore`: `dcNavscroll` ha `x-data` messo da JS (mountDesignComuniComponents), un morph Livewire lo perderebbe.
    Avanti = `requestNextStep()` (Alpine `wizardSchemaComponent`, vendor/filament/schemas wizard.js) -> `callSchemaComponentMethod(key, 'nextStep', ...)` ->
    `Wizard::nextStep()` (valida il passo e manda `next-wizard-step`). Indietro = `goToPreviousStep()` (solo client). Nessuna bozza: niente "Salva Richiesta" (DCP-33, decisione 4).
    Nessun Bootstrap JS: i pulsanti sono `type="button"`, senza `data-bs-*`. Testi: chiavi `fixcity::ticket_form.*` lette dal loader con ripiego italiano (vedi story DCP-35, "Chiavi lingua proposte (P2-A)").
--}}
@php
    // ponytail: __() su una chiave mancante crea la riga DB e fa scrivere il file lingua dall'auto-writer. Finche' le 5 chiavi nuove non esistono in
    // lang/*/ticket_form.php si legge dal loader con testo di ripiego (stessa tecnica di dc-place-autocomplete); poi sostituire con __().
    $t = static function (string $key, string $default): string {
        [$namespace, $path] = explode('::', $key, 2);
        [$group, $item] = explode('.', $path, 2);
        $value = \Illuminate\Support\Arr::get(app('translator')->getLoader()->load(app()->getLocale(), $group, $namespace), $item);

        return is_string($value) ? $value : $default;
    };
    // Dopo un "Avanti" respinto (campi mancanti) il focus va subito al primo campo non valido visibile: ha `aria-invalid` e `aria-describedby` verso il suo errore,
    // cosi' tastiera e screen reader sentono cosa correggere (DCP-286, FO-3-17/FO-3-23). Filament poi scorre "smooth" fino al wrapper dell'ultimo errore, che puo'
    // lasciare il campo sopra il bordo della finestra: a scorrimento finito (o dopo 1,2 s) lo si riporta al centro. Il click parte sempre da `requestNextStep()`.
    $focusFirstInvalid = "await requestNextStep(); await \$nextTick(); const first = [...\$root.querySelectorAll('[aria-invalid=true]')].find((field) => field.offsetParent !== null); first?.focus({ preventScroll: true }); if (first) { await Promise.race([new Promise((done) => addEventListener('scrollend', done, { once: true })), new Promise((done) => setTimeout(done, 1200))]); first.scrollIntoView({ block: 'center' }); }";
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $childSchema = $getChildSchema()->gap(false)->extraAttributes(['class' => 'd-block']); // niente gap (24px) ne' display:grid (margini che non collassano): vedi dc-section-card
    $nav = [
        'suffix' => 'one',
        'title' => $t('fixcity::ticket_form.navscroll.title', 'INFORMAZIONI RICHIESTE'),
        'progress_label' => $t('fixcity::ticket_form.navscroll.title', 'INFORMAZIONI RICHIESTE'),
        'items' => collect($childSchema->getComponents())
            ->filter(static fn ($component): bool => $component instanceof \Filament\Schemas\Components\Section && filled($component->getHeading()))
            ->map(static fn (\Filament\Schemas\Components\Section $section): array => ['anchor' => $section->getId(), 'label' => $section->getHeading()])
            ->values()
            ->all(),
    ];
@endphp
<p class="title-xsmall d-lg-none my-5">{{ $t('fixcity::ticket_form.required_notice', 'I campi contraddistinti dal simbolo asterisco sono obbligatori') }}</p>
<div class="row">
    <div class="col-12 col-lg-3 d-lg-block mb-4 d-none" wire:ignore>
        @include('pub_theme::components.blocks.report-partials.navscroll', ['nav' => $nav])
    </div>
    <div class="col-12 col-lg-8 offset-lg-1">
        <div class="steppers-content" aria-live="polite">
            <div class="it-page-sections-container">
                {{ $childSchema }}
            </div>
            <div class="cmp-nav-steps" data-wizard-nav>
                <nav class="steppers-nav" aria-label="{{ $t('fixcity::ticket_form.nav.aria_label', 'Step') }}">
                    <button type="button" class="btn btn-sm steppers-btn-prev p-0" x-on:click="goToPreviousStep()">
                        <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
                        <span class="text-button-sm t-primary">{{ $t('fixcity::ticket_form.nav.back', 'Indietro') }}</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm steppers-btn-confirm" x-on:click="{{ $focusFirstInvalid }}">
                        <span class="text-button-sm">{{ $t('fixcity::ticket_form.nav.next', 'Avanti') }}</span>
                        <svg class="icon icon-white icon-sm" aria-hidden="true"><use href="{{ $icon('it-chevron-right') }}"></use></svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</div>
