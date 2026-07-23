# Story 8-108: Segnalazione-Crea Parity con Design Comuni

## Obiettivo
Fix `/it/tests/segnalazione-crea` (conversione di `segnalazione-01-privacy.html`) per raggiungere la parità visiva con Design Comuni.

## Cosa è stato fatto

### ✅ Step 1: Traduzioni e Label
- **TicketForm.php**: `getStepByName()` ora usa le traduzioni corrette da `fixcity::ticket_wizard.steps.*.label`
- **XotBaseWizardWidget.php**: Fix per `getStepByName()` — label dinamiche invece di key statica
- **tocket_wizard.php (lang)**: Corrette label in italiano:
  - `privacy` → "Autorizzazioni e condizioni" (match esatto col riferimento)
  - `data` → "Dati di segnalazione"
  - `summary` → "Riepilogo"

### ✅ Step 2: Checkbox Label
- **segnalazione.php (lang)**: Label checkbox → "Ho letto e compreso l'informativa sulla privacy"
- **TicketForm.php**: `getPrivacySchema()` usa la traduzione corretta

### ✅ Step 3: CSS Build
- `npm run build` in `laravel/Themes/Sixteen` → successo
- `npm run copy` → assets copiati in `public_html/themes/Sixteen/`

### ✅ Step 4: Validazione Codice
- **PHPStan** (level 5): ✅ No errors su file modificati
- **Pint**: ✅ Fixati spazi/whitespace in 3 file
- **HeaderNavBlock.php**: ✅ PHPStan passed

### ✅ Step 5: Documentazione
- Creato: `laravel/Themes/Sixteen/docs/wiki/concepts/header-nav-dynamic-architecture.md`
- Creato: `laravel/Modules/Cms/docs/wiki/concepts/headernavblock-filament-builder.md`

## Verifica Visiva (Manuale)

| Elemento | Riferimento Design Comuni | Stato |
|---------|----------------------------|-------|
| Step 1 label | "Autorizzazioni e condizioni" | ✅ |
| Step 2 label | "Dati di segnalazione" | ✅ |
| Step 3 label | "Riepilogo" | ✅ |
| Checkbox label | "Ho letto e compreso l'informativa sulla privacy" | ✅ |
| Font-family | Titillium Web | ⚠️ (da verificare) |
| Stepper visibility | Visibile con nome step | ✅ |

## Architettura Header (Filosofia Zen)

```
header.json (SSoT)
    ↓
Section.php → v1.blade.php
    ↓
nav-primary.blade.php + nav-secondary.blade.php
    ↓
HeaderNavBlock (Filament Builder per admin)
```

**Filosofia**: Unica fonte di verità (`header.json`). Nessun link hardcoded. Filament Builder permetterà agli amministratori di gestire la navigazione senza toccare codice.

## File Modificati

1. `laravel/Modules/Fixcity/lang/it/ticket_wizard.php`
2. `laravel/Modules/Fixcity/lang/it/segnalazione.php`
3. `laravel/Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketForm.php`
4. `laravel/Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceForm.php`
5. `laravel/Modules/Xot/app/Filament/Widgets/XotBaseWizardWidget.php`

## Prossimi Passi

1. ✅ PHPStan → fatto
2. ✅ Pint → fatto
3. ⏳ Puppeteer/Playwright visual check (da fare)
4. ⏳ PHPMD (nessun .phar trovato)
5. ⏳ QMD ingest (errore DB — da risolvere)
6. ⏳ Test Pest per validare label (nessun test trovato)

## Comandi per Verifica Rapida

```bash
# Verifica step labels
curl -s http://127.0.0.1:8000/it/tests/segnalazione-crea | grep "Autorizzazioni e condizioni"

# Verifica checkbox
curl -s http://127.0.0.1:8000/it/tests/segnalazione-crea | grep "Ho letto e compreso"

# PHPStan
cd laravel && php -d memory_limit=512M vendor/bin/phpstan analyse --level=5

# Pint
cd laravel && php vendor/bin/pint --dirty --format=summary
```

## Riferimenti

- **Design Comuni**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
- **Repo**: https://github.com/italia/design-comuni-pagine-statiche
- **Filament Builder**: https://filamentphp.com/docs/5.x/forms/builder
