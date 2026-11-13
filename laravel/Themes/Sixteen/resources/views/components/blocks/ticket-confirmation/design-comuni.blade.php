@props(['data' => []])
{{--
    Conferma del ticket appena creato (pagina reale tickets.confirmation, riferimento sito/segnalazione-04-conferma): stessa resa del blocco
    report-step (layout `done`, JSON della pagina) con i valori del ticket al posto dei segnaposto `:code`, `:email`, `:receipt_url`,
    `:area_url`; le righe di `heading.lines` con `when` ("mail" | "in_app") si mostrano solo se coincidono con `email_delivery`. I valori arrivano da ResolveTicketConfirmationDataAction (flash di CreateTicketAction, altrimenti `?code=`).
    Senza ticket vale `without_code` (stesso schema di `heading`): niente codice ne' ricevuta.
--}}
@php
    $confirmation = app(\Modules\Fixcity\Actions\ResolveTicketConfirmationDataAction::class)->execute(
        is_string(request()->query('code')) ? request()->query('code') : null
    );
    $hasCode = (string) data_get($confirmation, 'code', '') !== '';
    $replacements = [
        ':code' => (string) data_get($confirmation, 'code', ''),
        ':email' => (string) data_get($confirmation, 'email', ''),
        ':receipt_url' => (string) data_get($confirmation, 'receipt.url', '#'),
        ':area_url' => (string) data_get($confirmation, 'reserved_area.url', '#'),
    ];
    $fill = static function (mixed $node) use (&$fill, $replacements): mixed {
        if (is_array($node)) {
            return array_map($fill, $node);
        }

        return is_string($node) ? strtr($node, $replacements) : $node;
    };
    $blockData = is_array($data) ? $data : [];
    if (! $hasCode && is_array($blockData['without_code'] ?? null)) {
        $blockData['heading'] = $blockData['without_code']['heading'] ?? [];
    }
    unset($blockData['without_code']);
    // riga con `when`: la dice il JSON, la sceglie il server (`email_delivery`, DCP-286): "mail" = parte anche una email, "in_app" = solo l'area personale.
    // Senza valore (chi ha solo il codice) nessuna delle due righe si mostra: non si dice nulla sull'account altrui.
    $delivery = (string) data_get($confirmation, 'email_delivery', '');
    $blockData['heading']['lines'] = array_values(array_filter(
        (array) data_get($blockData, 'heading.lines', []),
        static fn (mixed $line): bool => ! is_array($line) || ! array_key_exists('when', $line) || ($delivery !== '' && $line['when'] === $delivery),
    ));
    if ($replacements[':email'] === '') {
        // senza email (ripiego `?code=`) la riga "Ti invieremo il riepilogo all'email" non ha senso
        $blockData['heading']['lines'] = array_values(array_filter(
            (array) data_get($blockData, 'heading.lines', []),
            static fn (mixed $line): bool => ! in_array(':email', array_column((array) data_get($line, 'parts', []), 'strong'), true),
        ));
    }
@endphp
@include('pub_theme::components.blocks.report-step.design-comuni', ['data' => $fill($blockData)])
