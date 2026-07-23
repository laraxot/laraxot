/**
 * Theme Two - Application JS
 *
 * VIETATO importare Alpine.js qui. Livewire/Filament fornisce già Alpine nel bundle.
 * Importare alpine.js causa "Detected multiple instances of Alpine running" e
 * "$wire is not defined" nei form Filament (LoginWidget, ecc.).
 * Vedi: Themes/Two/docs/fix/layout.txt e docs/fix/login-alpine.txt
 * Theme Two - Entry point JS.
 * Alpine.js è fornito da Livewire/Filament (@filamentScripts).
 * NON importare alpine.js qui: causa "Detected multiple instances of Alpine" e "$wire is not defined".
 * Vedi docs/fix/layout.txt
 */
// Alpine.js is provided by Livewire/Filament bundle (@filamentScripts / @livewireScripts).
// NEVER import Alpine here — doing so creates a second Alpine instance that breaks $wire.
