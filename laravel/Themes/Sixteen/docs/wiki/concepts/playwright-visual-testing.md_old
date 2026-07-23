---
title: Visual Testing per Frontend (Sixteen Theme)
type: concept
sources:
  - https://playwright.dev/docs/test-snapshots
  - https://playwright-php.dev/
  - https://github.com/hansonxyz/laravel-headless-browser-tester
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [visual-testing, playwright, sixteen-theme, frontend, blade, livewire, filament]
related:
  - header-section-owner-rule
  - design-comuni-header-auth-state
---

# Visual Testing per Frontend (Sixteen Theme)

## Introduzione

Il Sixteen Theme usa Blade templates, Livewire components e Filament per il frontend. Il testing visivo assicura che i cambiamenti al tema non rompano l'UI.

## Strumenti Disponibili

### 1. Playwright (Raccomandato)
- Cross-browser: Chromium, Firefox, WebKit
- Integrato in Pest v4 (`pest-plugin-browser`)
- Auto-wait integrato

### 2. Laravel Headless Browser Tester
Artisan command per test headless semplici:

```bash
# Screenshot base
php artisan browser:test /it --screenshot-path=/tmp/home.png

# Screenshot mobile
php artisan browser:test /it --screenshot-path=/tmp/mobile.png --screenshot-width=mobile

# Con utente autenticato
php artisan browser:test /it/dashboard --user=1 --screenshot-path=/tmp/dashboard.png

# Eval JavaScript (click, wait, poi screenshot)
php artisan browser:test /it/tests/segnalazione-crea \
    --eval="$('.open-modal').click(); await new Promise(r => setTimeout(r, 1000));" \
    --screenshot-path=/tmp/modal.png
```

## Testing Componenti Sixteen

### Header Section (v1.blade.php)

```php
// Test che l'header renderizza correttamente
it('header renders correctly for guests', function () {
    visit('/')
        ->assertScreenshotsMatches();
});

it('header renders correctly for authenticated users', function () {
    $user = User::factory()->create();
    actingAs($user);
    
    visit('/')
        ->assertScreenshotsMatches();
});
```

### MapPicker nel Wizard

```php
it('segnalazione create wizard shows map', function () {
    visit('/it/tests/segnalazione-crea')
        ->assertSee('Segnalazione')
        ->assertScreenshotsMatches();
});
```

## Screenshot Organizzazione

```
laravel/Themes/Sixteen/docs/wiki/assets/screenshots/
├── header/
│   ├── guest-desktop.png
│   ├── guest-mobile.png
│   ├── auth-desktop.png
│   └── auth-mobile.png
├── map-picker/
│   ├── default-view.png
│   ├── fullscreen.png
│   ├── marker-drag.png
│   └── layer-switch.png
└── wizard/
    ├── step-1-type.png
    ├── step-2-location.png
    └── step-3-details.png
```

## Workflow Testing Visivo

1. **Baseline**: Cattura screenshot di riferimento
2. **Modify**: Fai cambiamenti al codice/tema
3. **Compare**: Esegui test visuale
4. **Review**: Controlla differenze
5. **Update**: Se cambiamento è intenzionale, aggiorna baseline

```bash
# Update baseline screenshots
npx playwright test --update-snapshots
# o con Pest
./vendor/bin/pest --update-snapshots
```

## Integrazione CI/CD

```yaml
# .github/workflows/visual-tests.yml
name: Visual Tests
on: [push, pull_request]
jobs:
  visual:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - uses: actions/setup-node@v3
        with:
          node-version: '22'
      - run: composer install
      - run: npm install playwright@latest && npx playwright install --with-deps
      - run: ./vendor/bin/pest --group=visual
```

## Troubleshooting

### Mappa collassa in fullscreen
Verificare che i controlli siano dentro il container della mappa (vedi farmshops.eu pattern)

### Screenshot inconsistenti
- Usa stesso OS/ambiente per baseline e test
- Disabilita font antialiasing se possibile
- Usa `maxDiffPixels` tolerance

### Elementi dinamici
- Usa `waitForSelector` prima di screenshot
- Nascondi elementi dinamici (orario, user ID) via CSS `stylePath`
