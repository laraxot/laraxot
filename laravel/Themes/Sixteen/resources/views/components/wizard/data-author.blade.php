{{--
    Scheda "Autore della segnalazione" del passo dati (sito/segnalazione-02-dati, `section#report-author .cmp-info-button-card.mt-3`, DCP-128).
    Dati VERI dell'utente loggato, stessa fonte del riepilogo (`Modules\Fixcity\Actions\BuildTicketReviewSummaryAction`): `review.author` (nome,
    righe anagrafiche: oggi vuote perche' il codice fiscale non ha una sorgente) e `review.contacts` (telefono del profilo, email) nei dettagli
    apribili, gli stessi del riepilogo. Sotto il nome la riga `card-info` (due righe come nel riferimento: etichetta e valore) mostra la prima riga
    anagrafica se c'e', altrimenti l'email (`$email`): identifica l'account che firma.
    Uso: `View::make('pub_theme::components.wizard.data-author')->viewData(['review' => ..., 'email' => ...])` dentro la Section `report-author`.
    Markup = partial `report-partials/info-card` (lo stesso dell'elenco segnalazioni). "Mostra tutto" apre/chiude con il Collapse di Bootstrap Italia
    montato a caricamento pagina (`data-bs-toggle`); `wire:ignore` perche' un morph di Livewire non perda l'inizializzazione e lo stato aperto.
--}}
@php
    $author = $review['author'] ?? null;
    $contacts = $review['contacts'] ?? null;
    $email = $email ?? '';
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $info = $author['rows'][0] ?? ['label' => __('fixcity::ticket_review.contacts.fields.email'), 'value' => $email];
    $card = [
        'class' => 'mt-3',
        'title' => $author['name'] ?? '',
        'info' => $info,
        'header_id' => 'heading-collapse-parents',
        'toggle_id' => 'collapse-parents',
        'toggle_label' => __('fixcity::ticket_form.fields.author.show_more'),
        'summary' => filled($contacts) ? ['class' => 'has-border', 'title' => $contacts['title'] ?? '', 'rows' => $contacts['rows'] ?? []] : ['rows' => []],
    ];
@endphp
@if (filled($author))
    <div wire:ignore>
        @include('pub_theme::components.blocks.report-partials.info-card', ['card' => $card])
    </div>
@endif
