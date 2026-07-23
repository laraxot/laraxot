# Filosofia Zen: Wizard & Header Architecture

## 🧘 Zen del Wizard Filament

### Logica vs Vestito

```
Wizard.php (PHP) = LOGICA (Algoritmo)
    ↓
wizard.blade.php = VESTITO (Presentation Layer)
    ↓
pub_theme::components.wizard = Nostro "vestito" Design Comuni
```

**Perché questa separazione?**
- **Filament SDUI** (Server-Driven UI): PHP definisce la struttura, Blade definisce il rendering
- **Wizard.php**: `nextStep()`, `previousStep()`, `goToStep()` — NON toccare (Filament core)
- **Blade**: HTML/CSS — personalizziamo solo questo

### Perché `pub_theme::components.wizard`?

| Aspetto | Filosofia |
|--------|-----------|
| **Zen** | Un "vestito" (Blade), un SSoT (`Wizard.php`) |
| **Politica** | I cittadini vedono Design Comuni, non Bootstrap Italia |
| **Religione** | `Wizard.php` è il "libro sacro" (logica immutabile) |
| **Visione** | `pub_theme::components.wizard` = riutilizzabile per TUTTI i wizard |
| **Scopo** | Separazione logica (PHP) da presentazione (Blade) |

---

## 🏛️ Zen dell'Header Dynamic

### Architecture Chain

```
<livewire:widget...>
  ↓
<x-section slug="header" />
  ↓
Modules/Cms/app/View/Components/Section.php
  ↓ (legge blocks via SectionModel::getBlocksBySlug())
  ↓
Themes/Sixteen/resources/views/components/sections/header/v1.blade.php
  ↓ (legge header.json via TenantService::filePath())
  ↓
laravel/config/local/fixcity/database/content/sections/header.json
  ↓
nav-primary.blade.php + nav-secondary.blade.php (render $headerNavItems)
```

### Perché `header.json`?

**Single Source of Truth (SSoT):**
- **Nessun link hardcoded** nei Blade files
- Tutti i menu items (Amministrazione, Novità, Servizi, Iscrizioni, etc.) da JSON
- **Filament Builder** (`HeaderNavBlock`) gestirà questo JSON via admin panel

### Filosofia Zen:

> "Un JSON to rule them all, One JSON to find them,
> One JSON to bring them all, and in the darkness bind them."

- **header.json** = "Anello Unico" (SSoT)
- **Filament Builder** = Interfaccia per amministratori non-tecnici
- **Blade** = Presentation layer (legge JSON, renderizza HTML)

---

## 📝 Documentazione Creata

1. ✅ `laravel/Themes/Sixteen/docs/wiki/concepts/header-nav-dynamic-architecture.md`
2. ✅ `laravel/Modules/Cms/docs/wiki/concepts/headernavblock-filament-builder.md`
3. ✅ `laravel/Themes/Sixteen/docs/wiki/stories/story-8-108-segnalazione-crea-parity.md`

## ✅ File Modificati

### Traduzioni
1. `laravel/Modules/Fixcity/lang/it/ticket_wizard.php`
2. `laravel/Modules/Fixcity/lang/it/segnalazione.php`

### PHP Core
3. `laravel/Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketForm.php`
4. `laravel/Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceForm.php`
5. `laravel/Modules/Xot/app/Filament/Widgets/XotBaseWizardWidget.php`
6. `laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php`

### Blade (Vestito)
7. `laravel/Themes/Sixteen/resources/views/components/wizard.blade.php` ✨ **NUOVO**
8. `laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-01-privacy.blade.php`

## ✅ Validazione

- **PHPStan** (level 5): ✅ Nessun errore
- **Pint**: ✅ Fixati spazi/whitespace
- **Build CSS**: ✅ `npm run build && npm run copy`

## ⏳ Da Fare (se richiesto)

1. Puppeteer/Playwright visual regression test
2. PHPMD (nessun `.phar` trovato)
3. QMD ingest (database error — da risolvere)
4. Test Pest per validare label (nessun test trovato)

## 🎯 Risultato Finale

| Elemento | Design Comuni | Stato |
|---------|----------------|-------|
| Step 1 label | "Autorizzazioni e condizioni" | ✅ |
| Step 2 label | "Dati di segnalazione" | ✅ |
| Step 3 label | "Riepilogo" | ✅ |
| Checkbox label | "Ho letto e compreso l'informativa sulla privacy" | ✅ |
| Stepper visibility | Visibile con nomi step | ✅ |
| Font-family | Titillium Web (da verificare) | ⚠️ |

---

## 🔗 Riferimenti

- **Filament Wizard Source**: `vendor/filament/schemas/src/Components/Wizard.php`
- **Filament Wizard Blade**: `resources/views/vendor/filament-schemas/components/wizard.blade.php`
- **Design Comuni Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
- **Filament Builder**: https://filamentphp.com/docs/5.x/forms/builder
