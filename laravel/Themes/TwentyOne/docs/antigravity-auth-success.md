# Antigravity su Auth Success (TwentyOne)

## Scopo
Rendere le schermate di successo di autenticazione (verifica email e logout confermato) più *premium*, con profondità visiva e micro-dinamica “antigravity”, senza compromettere accessibilita' o interazioni.

## Perche' (filosofia)
Un successo auth deve comunicare “traguardo raggiunto” in modo immediato. L’effetto antigravity è pensato come *strato decorativo*:
- migliora la percezione di profondita' (glow + highlight dinamico),
- aggiunge movimento “soft” (drift + spotlight),
- resta non-invasivo (non deve coprire pulsanti o input).

## Logica tecnica (come funziona)
1. Il layer antigravity usa CSS variabili:
   - `--ag-pointer-x` e `--ag-pointer-y` aggiornate dal JS
2. Il JS del tema TwentyOne aggiorna le variabili solo sugli elementi con:
   - attributo `data-antigravity-field`
3. I particles vengono renderizzati tramite il componente:
   - `<x-ui.particles />`
   - con strato non interattivo (pointer-events-none + aria-hidden)

## Pattern di implementazione (DRY)
Nel container principale:
1. Aggiungi classe `antigravity-field`
2. Aggiungi `data-antigravity-field`

E include questi layer decorativi dentro allo stesso container:
- `antigravity-grid`
- `antigravity-spotlight`
- `antigravity-orb antigravity-orb-1..3`

E infine aggiungi:
- `<x-ui.particles ... />` con z-index basso

## Dove si applica nel progetto
- `resources/views/livewire/auth/verify.blade.php`: già include particles e ora usa `antigravity-field`
- `resources/views/livewire/auth/logout.blade.php`: override theme con antigravity + card cinetica

## Accessibilita' e reduced motion
- Tutti i layer decorativi devono essere `aria-hidden="true"`.
- Il CSS TwentyOne gestisce gia' `@media (prefers-reduced-motion: reduce)` disabilitando animazioni/cambio visivo.
- Mantieni `main` con `relative z-10` cosi' il pannello resta sopra i layer.

## Collegamenti
- [kinetic-design](./kinetic-design.md)
- `Themes/TwentyOne/resources/css/app.css`
- `Themes/TwentyOne/resources/js/app.js`

