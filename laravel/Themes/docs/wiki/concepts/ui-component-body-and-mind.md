# UI Components: Body and Mind Philosophy

In our design system, every complex interactive component is split into two parts:

## The Body (Blade)
- **Location**: `resources/views/...`
- **Role**: The skeleton in the DOM. 
- **Responsibility**: It handles the bridge between the backend (Livewire, PHP) and the frontend. It manages events, state synchronization, and accessibility wrappers.

## The Mind (Lit JS)
- **Location**: `resources/js/components/...`
- **Role**: The intelligence and interaction.
- **Responsibility**: It manages the Shadow DOM, keeps styles isolated, handles complex libraries (like Leaflet), and performs real-time UI updates without hitting the server.

## Why the Split?
- **Separation of Concerns**: The Blade doesn't need to know how to draw a map; it just needs to know that a "coordinate changed" event will happen.
- **Performance**: Lit components are extremely fast and efficient, re-rendering only what changed in the Shadow DOM.
- **Evolution**: We can upgrade the "Mind" (e.g., move from Leaflet to another library) without changing the "Body" (the HTML structure in the form).
