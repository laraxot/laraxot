{{-- Sezione "persona": scheda con nome, dato anagrafico e dettagli apribili (stesso markup dell'elenco segnalazioni: partial info-card). --}}
@include('pub_theme::components.blocks.report-partials.info-card', ['card' => $section['card'] ?? []])
