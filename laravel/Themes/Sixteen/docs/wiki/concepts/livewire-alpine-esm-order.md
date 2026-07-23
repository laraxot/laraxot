---
title: livewire alpine esm order (tema sixteen)
type: troubleshooting
confidence: high
created: 2026-05-22
updated: 2026-05-26
sources:
     - laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php
     - laravel/Themes/Sixteen/resources/js/components/mobile-menu.js
related:
     - ../../../../../Modules/Fixcity/docs/wiki/concepts/segnalazione-runtime-asset-integrity.md
     - ../../../../../Modules/Geo/resources/js/filament/map-picker.js
     - ../../design-comuni/analysis/header-html-parity.md
     - ../../../../../../docs/wiki/memories/sixteen-vite-public-path-alpine-livewire-order.md
tags:
     - livewire
     - filament
     - alpine.js
     - vite
     - tema-sixteen
---

# ordinamento Livewire Alpine e bundle ESM tema Sixteen

## webroot Laravel dopo `vite build`

`public_path()` in questa base punta a **`public_html/`** (repo root `…/base_fixcity_fila5/public_html/`).  
Vite nel tema mette bundle e `manifest.json` in **`laravel/Themes/Sixteen/public/`**; Laravel legge **`public_html/themes/Sixteen/manifest.json`**.

Dopo **`npm run build`** dalla cart tema, pubblicare nel webroot usato dall'app:

```bash
npm run copy
```

Equivalente combinato:

```bash
npm run build:with-webroot
```

(`build:with-webroot` = `vite build && npm run copy`.)  

Altrimenti: `rsync` di `Themes/Sixteen/public/assets/` e copia di `manifest.json` verso `public_html/themes/Sixteen/`. Senza questo passaggio restano vecchi nomi tipo `app-BDB…`.

## problema

Messaggi tipo `Alpine Expression Error: X is not defined` su pagine Filament/Livewire (es. wizard segnalazione) **anche con** `Alpine.data('X', …)` nei sorgenti `resources/js/app.js`.

## causa

1. **`@vite(['resources/js/app.js'])`** emette uno script **`type="module"`** defer: viene eseguito **dopo** che il parsing del documento ha già incontrato **`@livewireScripts` / `@filamentScripts`**.
2. `@livewireScripts` avvia **Alpine bundled** in modo sincrono e attraversa il DOM (incluse le direttive `x-data` dell'header).
3. Anche un `document.addEventListener('alpine:init', …)` dentro `app.js` **non basta**, perché l'evento `alpine:init` viene emesso mentre il listener non è ancora stato registrato (il modulo non è ancora valutato).

## [2026-05-26] aggiornamento: inline bootstrap obbligatorio

Dopo numerose iterazioni, il pattern "tutto in Vite" **non funziona** per componenti Alpine usati nel markup HTML iniziale:

- Vite bundle è `type="module"` defer → eseguito **dopo** DOMContentLoaded
- Livewire (`@livewireScripts`) include Alpine che parte **prima** → `alpine:init` è già scattato
- Listener `alpine:init` in `app.js` arriva **troppo tardi**

**Pattern attuale**: registrare Alpine.data inline **PRIMA** di `@livewireScripts` in `<head>`.

### Componente `mobileMenu` (5 template attivi)

L'active layout `components/layouts/main.blade.php` ha nel `<head>`:

```html
<script>
    document.addEventListener('alpine:init', function () {
        Alpine.data('mobileMenu', () => ({
            isOpen: false,
            isMobileBreakpoint: false,
            init() {
                this.checkBreakpoint();
                window.addEventListener('resize', () => this.checkBreakpoint());
            },
            toggle() { this.isOpen = !this.isOpen; },
            open() { this.isOpen = true; },
            close() { this.isOpen = false; },
            checkBreakpoint() {
                this.isMobileBreakpoint = window.innerWidth < 768;
                if (!this.isMobileBreakpoint && this.isOpen) this.close();
            },
            isMobile() { return this.isMobileBreakpoint; },
            closeOnItemClick() { if (this.isMobileBreakpoint) this.close(); },
        }));
    });
</script>
```

La factory in `app.js` (`components/mobile-menu.js`) è una seconda registrazione identica e innocua — `Alpine.data()` è idempotente.

### Perché inline e non in un file separato?

- Un file `.js` caricato prima di `@livewireScripts` con `<script src="...">` (non module) funzionerebbe, ma richiede un asset fuori dalla pipeline Vite → complexity
- Un secondo entry `@vite` non ha senso: l'intero punto è che Vite è defer, quindi qualsiasi entry Vite arriva dopo Alpine
- L'inline è 25 righe, è il pattern **canonico** Alpine, identico al dark-mode anti-FOUC già presente nel layout

**Questa NON è JS legacy**. È bootstrapping strutturale, esattamente come `<script>if(localStorage...){document.documentElement.classList.add('dark')}</script>`.

## pattern per altri componenti

Chiunque usi **`x-data` su markup presente allo start iniziale** e dipenda da `Alpine.data` definito nel bundle tema deve:

1. Aggiungere un inline `<script>` con registro su `alpine:init` PRIMA di `@livewireScripts`
2. Mantenere la factory in `app.js` per l'uso dopo navigazione Livewire (redundant ma safe)

Alternative non valide per questo tema:
- Usare `x-ignore` + `Alpine.initTree()` usa API private e causa FOUC
- Asset non-defer caricati prima di Alpine non fanno parte della pipeline Vite
- Markup caricato dopo il bundle (morpha Livewire tardivo) va bene per componenti Livewire ma non per markup statico

## riferimenti incrociati

- Header parity: [header-html-parity.md](../../design-comuni/analysis/header-html-parity.md)
- Catena storica asset segnalazione / geo: [`segnalazione-runtime-asset-integrity.md`](../../../../../Modules/Fixcity/docs/wiki/concepts/segnalazione-runtime-asset-integrity.md)
- Binding Filament + Alpine: [`filament-custom-field-binding-modifiers-theme-boundary.md`](./filament-custom-field-binding-modifiers-theme-boundary.md)

## asset stale: come controllarsi prima di correggere il file sbagliato

Quando la console cita un hash specifico (`app-BDBkID6g.js`, `app-BIM-cnUB.js`, ecc.), quello e' il fatto primario. Non ragionare dal sorgente `resources/js/app.js` senza verificare quale bundle viene davvero referenziato dall'HTML.

Checklist minima:

```bash
curl -s http://127.0.0.1:8000/it/segnalazione-crea -o /tmp/seg-current.html
rg -n "assets/app-[A-Za-z0-9_-]+\.js|geoMapPickerField|headerMobileNav|mobileMenu" /tmp/seg-current.html
jq -r '."resources/js/app.js".file' public_html/themes/Sixteen/manifest.json
jq -r '."resources/js/app.js".file' laravel/Themes/Sixteen/public/manifest.json
cd laravel && php artisan tinker --execute="dump(['public_path' => public_path(), 'vite_app' => json_decode(file_get_contents(public_path('themes/Sixteen/manifest.json')), true)['resources/js/app.js']['file'] ?? null]);"
```

Regola pratica:

- `public_path()` in questa app punta a `public_html`, quindi il manifest autorevole runtime e' `public_html/themes/Sixteen/manifest.json`.
- `laravel/public/themes/Sixteen/manifest.json` puo' essere storico/fuorviante: non usarlo come prova che la pagina stia servendo quel bundle.
- Il riferimento definitivo e' sempre l'HTML appena scaricato dalla URL esatta.
- Dopo `npm run build`, eseguire anche `npm run copy` dal tema Sixteen se l'app serve asset da `public_html`.
