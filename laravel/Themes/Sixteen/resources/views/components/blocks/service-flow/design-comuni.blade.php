@props(['data' => []])
{{--
    Flusso di servizio Design Comuni (cartella servizi/ del riferimento): scheda servizio, area personale, passi del wizard con
    stepper (dati, preferenze, riepilogo), pagamento ed esito. Un solo punto di ingresso per tutte le famiglie (servizi-pagamento, imu, multa, permessi, vantaggi,
    graduatoria): la chiave `kind` del JSON sceglie il tipo di pagina (pages/<kind>), le sezioni si scelgono per `type` (sections/<type>), il resto sono parti
    condivise (parts/). Un `kind` sconosciuto non produce nulla. Nessun testo e' nelle viste: tutto arriva dal JSON della pagina. Il modello dei dati e' descritto nella story DCP-21
    (docs/bmad/stories/dcp-21-servizi-pagamento-e-service-flow.story.md). Stili: resources/css/design-comuni/.
--}}
@php
    $kind = preg_replace('/[^a-z-]/', '', (string) data_get($data, 'kind', 'service-sheet'));
@endphp
@includeIf('pub_theme::components.blocks.service-flow.pages.'.$kind, ['data' => $data])
