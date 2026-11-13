---
title: "coordinate picker"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-14
qmd: "coordinate picker"
issues: []
discussions: []
status: draft
---

# CoordinatePicker: Integration Guide for Themes

The `CoordinatePicker` component is available for both Back Office and Front Office usage. It utilizes a LitElement Web Component (`coordinate-picker-lit`) for map rendering and Leaflet for interactivity.

## Technical Implementation

### Synchronizing State

The component uses a robust Alpine.js bridge to Livewire:

```javascript
state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }}
```

This syntax is mandatory to ensure that the theme-side map component obeys the server-side configuration (e.g., real-time updates via `live()`).

### Event Handling

- **`coords-changed`**: Emitted by the Lit component when the marker is moved. Intercepted by Alpine to update the `state` array.
- **`searchAddress`**: `#[Renderless]` Livewire method of the PHP field (`searchAddress(string $query): array` in the trait `HasCoordinatePicker`, used by `Modules\Geo\Filament\Forms\Components\CoordinatePicker`). It queries Nominatim `search` from the server and returns `[]` for queries shorter than 3 characters. The Lit search panel does **not** call it by default: `map/controls/search.js` queries Nominatim from the browser with `fetch`, unless the element defines a `searchAddress(query)` function, and `coordinate-picker.blade.php` does not define one (checked 2026-10-14). The reason "to respect rate limits" is not stated in the code (not verified).
- **`reverseGeocode`**: `reverseGeocode(float $latitude, float $longitude): ?array` in the same trait is the server-side variant (Nominatim `reverse` from PHP). On a map click the Lit component asks Nominatim from the browser instead (`fetchNominatimReverse` in `map/reverse-geocode.js`, called by `map/events.js`).

## Component attributes (`coordinate-picker-lit`)

Source of truth: `static properties` in `laravel/Modules/Geo/resources/js/components/coordinate-picker-lit.js` (checked 2026-10-14). Defaults are the ones set in the constructor.

| Attribute | Type | Default | Notes |
|---|---|---|---|
| `state` | Object | `null` | `{latitude, longitude, ...}` (JSON in the attribute) |
| `zoom` | Number | `13` | |
| `height` | String | `400px` | |
| `center-latitude`, `center-longitude` | Number | `null` | |
| `default-view` | Object | `null` | `{lat, lng, zoom}`, used when there is no point |
| `geolocate-when-empty` | Boolean | `false` | |
| `labels` | Object | `{}` | texts of the controls (`zoom_in`, `zoom_out`, ...) |
| `provider` | String | `osm` | |
| `show-search` | Boolean | `true` | |
| `readonly` | Boolean | `false` | fixed marker, no click or drag that writes the state; only the zoom control is rendered (`READONLY_PARTS` in `map/controls/render-controls.js`); map without keyboard focus and without wheel zoom |
| `marker-icon-url` | String | `null` | optional, icon of the report type (`fixcity::svg`) |
| `marker-color` | String | unset | optional, colour of the status |
| `marker-label` | String | `''` | optional, name of the type; the initial letter is drawn only when the type icon was given but cannot be used |

In the Filament field, `readonly` is emitted by `coordinate-picker.blade.php` when `isMapReadonly()` is true (field `disabled()` or `readOnly()`), and `show-search` only when it is false. The wizard summary map (`review_map` in `TicketForm::getSummarySectionSchema()`, built with `->disabled()`) is therefore read-only.

Read-only use in the theme: block `components/blocks/ticket-detail/design-comuni.blade.php` (public page `/{lang}/tickets/{id}`) passes `readonly`, `state`, `zoom="16"`, `height="18rem"`, `labels`, `marker-icon-url`, `marker-color`, `marker-label`. Icon and colour come from `ResolveTicketTypeMarkerPropertiesAction` and `ResolveTicketStatusMarkerPropertiesAction`, the same Actions that build the GeoJSON of the public map. Story: [DCP-152 (Geo)](../../../../Modules/Geo/docs/stories/dcp-152-marker-dettaglio-ticket-e-pulsanti-mappa-su-pagine-dc.story.md).

The control buttons of the Lit (`.ctrl-btn`) are made opaque by `.map-container .ctrl-btn.ctrl-btn` in `map/styles.js`, so that the generic `button` reset of the `data-dc` pages does not make them transparent (the cause and the specificity numbers are in DCP-152; not re-measured here).

## UI Overrides

Themes can override the look and feel by creating a view at:
`resources/views/vendor/geo/filament/forms/components/coordinate-picker.blade.php`
(not verified: this is the Laravel vendor-view convention; Sixteen has no file named `coordinate-picker*.blade.php` under `resources/views`, checked 2026-10-14. The view that is rendered is `Modules/Geo/resources/views/filament/forms/components/coordinate-picker.blade.php`.)

However, it is recommended to use the standard XotBase view resolution and customize the Lit component's CSS variables if needed (not verified: `coordinate-picker-lit` renders in the light DOM, `createRenderRoot() { return this; }`, so the theme CSS reaches it directly).
