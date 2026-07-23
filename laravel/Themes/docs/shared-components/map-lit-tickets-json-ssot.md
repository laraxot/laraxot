# map-lit + tickets.json — SSoT mappa e filtri

## Scopo

Su `/it/#` (pagina `home`, `data-page=segnalazioni-elenco`) mappa e filtri devono usare **un solo file**: `/data/tickets.json` → `public_html/data/tickets.json`.

## Componenti

| Pezzo | Dove |
|-------|------|
| Lit map | `<map-lit id="block-map" data-url="...">` in `ticket/column-main.blade.php` |
| JS | `Modules/Geo/resources/js/components/map-lit.js` |
| Filtri PHP | `BuildSegnalazioniFilterAggregateAction` |
| Alpine sync | `Themes/Sixteen/resources/js/app.js` (`filter-types-updated`) |

## Vietato in produzione parity

- `use_design_comuni_list_demo: true` in `home.json` → mappa SVG + catalogo statico
- Filtri da `disservizio-lista.json` disgiunti dalla mappa

## Cluster (farmshops.eu)

Pattern da [direktvermarkter.js](https://github.com/CodeforKarlsruhe/farmshops.eu/blob/master/js/direktvermarkter.js):

- `maxClusterRadius: (z) => z < 12 ? 80 : 45`
- `iconCreateFunction` con conteggio + icone tipo se zoom ≥ 8
- divIcon **80×80**, `iconAnchor (40, 40)`
- Marker aggiunti **singolarmente** al cluster (no `L.geoJson` wrapper)
- `removeOutsideVisibleBounds: false` — marker restano dopo GPS lontano (STORY-124)
- **Mai** `refreshClusters()` manuale; `refreshWhenVisible()` → solo `invalidateSize`
- **Mai** `transform: scale` su hover cluster — fix in tema `07-map-clusters-and-leaflet.css` (STORY-123)

Doc dettaglio: [marker-cluster-hover-stability.md](../../Sixteen/docs/wiki/concepts/marker-cluster-hover-stability.md) · [map-lit-it-incidents-2026-06.md](../../Modules/Geo/docs/wiki/troubleshooting/map-lit-it-incidents-2026-06.md)

## Popup (block `popup`)

`map/popup-ticket.js` — BEM `popup`, `popup--loading` senza footer nel DOM; `<div class="popup__header">` (non `<header>` — evita `min-height: 222px` tema).

Dettaglio lazy: `GET /api/ticket-details/{id}`.

Doc: [geo-map-popup-bem.md](../../Modules/Geo/docs/wiki/concepts/geo-map-popup-bem.md) · [map-popup-header-whitespace-fix.md](../../Modules/Geo/docs/wiki/troubleshooting/map-popup-header-whitespace-fix.md)

## Marker

`map/marker-config.js` — `__inner` (stato), `__glyph-pad` (bianco), `__point` (triangolo CSS); 40×44 px.

Doc: [geo-map-lit-reconstruction-guide.md](../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md)

## Export GeoJSON

```bash
cd laravel
php -r "require 'vendor/autoload.php'; \$app=require 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); (new Modules\Fixcity\Actions\GenerateTicketsJsonAction())->execute();"
```

Backoffice: azione export su `ListTickets` Filament.

## Build tema

```bash
cd laravel/Themes/Sixteen
npm install   # prima build su ambiente pulito
npm run build && npm run copy
```

Dopo ogni modifica a `map-lit.js`: **obbligatorio** `npm run build`.

Centraggio: assenza `lat`/`lng` → GPS; presenza → coordinate esplicite (create/edit stesso form). Vedi [map-lit-lat-lng-gps-default-pattern.md](../../../docs/wiki/memories/map-lit-lat-lng-gps-default-pattern.md).

Collegamenti: [segnalazioni-elenco-visual-gap-audit.md](./segnalazioni-elenco-visual-gap-audit.md) · STORY-120
