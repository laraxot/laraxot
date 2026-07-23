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
- **`searchAddress`**: Exposed method to search for addresses using Nominatim (server-side to respect rate limits).
- **`reverseGeocode`**: Exposed method to find the address of specific coordinates.

## UI Overrides

Themes can override the look and feel by creating a view at:
`resources/views/vendor/geo/filament/forms/components/coordinate-picker.blade.php`

However, it is recommended to use the standard XotBase view resolution and customize the Lit component's CSS variables if needed.
