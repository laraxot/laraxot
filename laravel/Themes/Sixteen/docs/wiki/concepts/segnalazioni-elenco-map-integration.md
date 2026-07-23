# Segnalazioni elenco — integrazione mappa in Sixteen

## Ownership

| Layer | Responsabilità |
|-------|----------------|
| Tema Sixteen | Blade pagina `/it`, sidebar filtri, tab mappa/lista, CSS cascade Leaflet |
| Modulo Geo | `<map-lit>`, marker, popup, cluster, build Vite entry |
| Modulo Fixcity | Ticket + `GenerateTicketsJsonAction` → `tickets.json` |

## Integrazione blade

Homepage `/it`: `grid/2col.blade.php` → `ticket/column-main.blade.php` → CTA `cta/ticket.blade.php`.

| View | Path | Regola naming |
|------|------|----------------|
| Colonna principale | `components/blocks/ticket/column-main.blade.php` | inglese `ticket` |
| CTA mappa/lista | `components/blocks/cta/ticket.blade.php` | **vietato** `cta/segnalazione` — vedi [no-italian-component-names](../rules/no-italian-component-names.md) |
| Include | `@include('pub_theme::components.blocks.cta.ticket', ['cta' => $cta])` | `$cta` da `TicketLayoutViewModel::cta()` |

**URL CTA:** solo `LaravelLocalization::localizeURL($path)` nel blade — **vietato** `FrontofficeUrl` (scope header/CMS nav, non blocchi CTA).

Testo UI «Fai una segnalazione» resta in `lang/it/` — non nel nome file.

File legacy (altre pagine): `ticket-layout/layout.blade.php` (e varianti elenco).

Pattern attivo:

```blade
<map-lit
    id="block-map"
    data-url="/data/tickets.json"
    …
></map-lit>
```

Asset: `@vite` manifest tema → `public_html/themes/Sixteen/assets/map-lit-*.js`  
**Non** usare CDN Leaflet nel tema se il bundle Geo è attivo.

`body[data-page="ticket-list"]` — regole CSS listing in `listing-parity.css`, `07-map-clusters-and-leaflet.css`.

## Filtri

- Tipologia: checkbox sidebar → `filters-changed` con array `types`
- Stato: STORY-128 — facet da stesso `tickets.json`
- `map-lit` applica filtri su `_allMarkers` senza refetch

## Contratto dati GeoJSON

Path: `/data/tickets.json`  
Producer: `Modules/Fixcity/app/Actions/GenerateTicketsJsonAction.php`

Properties minime per marker/popup:

- `id`, `title`, `type`, `type_label`, `type_color`, `type_icon_url` / `iconUrl`
- `status`, `status_label`, `status_color` (o equivalenti risolti in `map-lit`)
- `address`, `city`, opz. `description`, `detail_url`

## UX attesa (2026-06)

- Cluster farmshops-style; `removeOutsideVisibleBounds: false`
- Marker: `__inner` stato + `__glyph-pad` bianco + `__point` (triangolo CSS); vedi [geo-map-lit-reconstruction-guide.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md)
- Popup **block `popup`**: apertura immediata, dettaglio lazy `/api/ticket-details/{id}`
- Popup **senza** vuoto header (vedi runbook sotto)
- Hover cluster/marker: ombra only, no transform
- Ricerca indirizzo collassabile (controllo lente)
- Producer GeoJSON: `Modules/Fixcity/app/Actions/GenerateTicketsJsonAction.php`

## Build obbligatoria dopo modifica JS/CSS

```bash
cd laravel/Themes/Sixteen && npm run build
php artisan view:clear
```

Doc build: [map-lit-vite-build-troubleshooting.md](./map-lit-vite-build-troubleshooting.md)

## Documentazione ricostruzione

- [geo-map-lit-reconstruction-guide.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md)
- [map-lit-it-incidents-2026-06.md](../../../../Modules/Geo/docs/wiki/troubleshooting/map-lit-it-incidents-2026-06.md)

## Riferimenti codice

- `Modules/Geo/resources/js/components/map-lit.js`
- `Modules/Geo/docs/wiki/entities/geo-map-lit.md`
- [geo-map-popup-leaflet-boundary.md](./geo-map-popup-leaflet-boundary.md)
