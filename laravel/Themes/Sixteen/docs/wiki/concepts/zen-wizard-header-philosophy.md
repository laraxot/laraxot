---
title: "zen wizard header philosophy"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-15
qmd: "zen wizard header philosophy"
issues: []
discussions: []
status: draft
---

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

### Architecture Chain (verificata 2026-10-15)

```
layout FO (`components/layouts/app` → home)
  ↓
<x-section slug="header" tpl="design-comuni" />   {{-- tpl omesso → v1 → tailwind --}}
  ↓
Modules/Cms/View/Components/Section.php
  ↓ SectionModel::getBlocksBySlug('header')  (Sushi/JSON tenant)
  ↓
laravel/config/local/fixcity/database/content/sections/header.json
  ↓ blocks[locale][0].data  → brand.tagline (slogan), navigation, account, languages, search…
  ↓
pub_theme::components.sections.header.{tpl}   {{-- SOLO vestito: markup + FrontofficeUrl --}}
```

### Perché `header.json`?

**Single Source of Truth (SSoT) — D11 BMAD:**
- Slogan, nome, logo, link, social, lingue, etichette search/menu: **solo JSON**
- Blade = vestito (HTML/CSS/Alpine); **mai** testi istituzionali hardcoded
- Path localizzati nel vestito con `FrontofficeUrl` (il JSON tiene path senza lingua)
- **Filament** `HeaderNavBlock` / SectionResource: admin modifica lo stesso JSON

### Filosofia Zen:

> "Un JSON to rule them all" — contenuto tenant vs presentazione tema.

- **header.json** = Anello Unico (SSoT)
- **Filament Blueprint / Builder** = piano e UI admin senza toccare Blade
- **Blade** = vestito (D11: un renderer Tailwind/Alpine per tpl)

### Footer (stesso contratto, 2026-10-15)

```
<x-section slug="footer" tpl="design-comuni|full">
  → Section::getBlocksBySlug('footer')
  → sections/footer.json  (brand.name, brand.description=slogan, groups/columns, contact, legal, social)
  → pub_theme::components.sections.footer.{tpl}  (solo vestito + FrontofficeUrl)
```

- Slogan e link: **solo JSON** (DCP-222 / DCP-198 A5)
- URL vuoti/`#` → non renderizzati come link
- Social / logo UE assenti finche' l'ente non fornisce dati reali

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
