# URL Mapping: Target Site vs Local Development 🗺️

**Data**: 2026-02-07
**Importanza**: ⚠️ CRITICO - Tutti gli agenti AI devono conoscere queste corrispondenze

---

## 🎯 Corrispondenze URL

Le pagine del sito target (https://lightseagreen-dogfish-560272.hostingersite.com/) corrispondono alle pagine locali seguendo un pattern preciso.

### Tabella di Corrispondenza

| Pagina Target | URL Locale | Slug JSON | Descrizione |
|---------------|------------|-----------|-------------|
| `/` | `/it` | `home.json` | Homepage |
| `/chi-siamo` | `/it/pages/about` | `about.json` | Chi Siamo / About Us |
| `/servizi` | `/it/pages/services` | `services.json` | Servizi |
| `/blog` | `/it/pages/blog` | `blog.json` | Blog |
| `/faq` | `/it/pages/faq` | `faq.json` | FAQ |
| `/contatti` | `/it/pages/contacts` | `contacts.json` | Contatti |

---

## 🔑 Regola Fondamentale

**Tutte le pagine "semplici" del target site (non nested) diventano `/it/pages/{slug}` in locale.**

### Pattern

```
Target:  /{nome-pagina}
Local:  /it/pages/{nome-pagina}
JSON:   config/local/techplanner/database/content/pages/{nome-pagina}.json
File:   /resources/views/pages/pages/[slug].blade.php (gestore unico!)
```

### Esempio Concreto: Chi Siamo

**Target Site**:
```
URL: https://lightseagreen-dogfish-560272.hostingersite.com/chi-siamo
Contenuto: Pagina "Chi Siamo" con info su Marco Sottana, servizi, risultati, contatti
```

**Locale Development**:
```
URL:  http://127.0.0.1:8000/it/pages/about
JSON: config/local/techplanner/database/content/pages/about.json
Gestore: /resources/views/pages/pages/[slug].blade.php (con $slug = 'about')
```

**NOTA IMPORTANTE**: Non creare file `/resources/views/pages/pages/about/index.blade.php`! ❌
Usa SEMPRE il gestore `[slug].blade.php` e il JSON `about.json`. ✅

---

## 📝 Struttura JSON

Ogni pagina ha il suo file JSON con la struttura:

```json
{
  "id": "5",
  "title": {
    "it": "Chi Siamo - Marco Sottana Consulenza Sicurezza",
    "en": "About Us - Marco Sottana Safety Consulting"
  },
  "slug": "about",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "slug": "about-hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.about",
          "title": "Chi Siamo",
          ...
        }
      }
    ],
    "en": [...]
  }
}
```

---

## 🔗 Link Interni

Quando crei link interni, usa sempre `LaravelLocalization::localizeUrl()`:

```blade
<!-- Link a Chi Siamo -->
<a href="{{ LaravelLocalization::localizeUrl('/pages/about') }}">Chi Siamo</a>

<!-- Link a Servizi -->
<a href="{{ LaravelLocalization::localizeUrl('/pages/services') }}">Servizi</a>

<!-- Link a Contatti -->
<a href="{{ LaravelLocalization::localizeUrl('/pages/contacts') }}">Contatti</a>
```

**MAI usare**:
```blade
<a href="/chi-siamo">  <!-- ❌ Errato: senza prefisso lingua -->
<a href="/pages/about">  <!-- ❌ Errato: senza prefisso lingua -->
<a href="{{ url('/pages/about') }}">  <!-- ❌ Errato: senza localizzazione -->
```

---

## ⚠️ Errori Comuni da Evitare

### Errore #1: Creare file duplicati
```bash
# ❌ ERRATO
mkdir -p resources/views/pages/pages/about
vi resources/views/pages/pages/about/index.blade.php

# ✅ CORRETTO
# Usa SOLO [slug].blade.php + about.json
```

### Errore #2: Link senza localizzazione
```blade
<!-- ❌ ERRATO -->
<a href="/chi-siamo">Chi Siamo</a>

<!-- ✅ CORRETTO -->
<a href="{{ LaravelLocalization::localizeUrl('/pages/about') }}">Chi Siamo</a>
```

### Errore #3: Non conoscere la corrispondenza
```bash
# Target: /chi-siamo → Local: /it/pages/about (NON /it/chi-siamo!)
# Target: /servizi → Local: /it/pages/services (NON /it/servizi!)
# Target: /blog → Local: /it/pages/blog (NON /it/blog!)
```

---

## 🎯 Creare Nuove Pagine

Quando devi creare una nuova pagina basata sul target site:

1. **Identifica l'URL target** (es. `/privacy`)
2. **Determina lo slug locale** (es. `privacy`)
3. **Crea il JSON** in `config/local/techplanner/database/content/pages/privacy.json`
4. **Fatto!** L'URL locale sarà automaticamente `/it/pages/privacy`

**NON creare file Blade duplicati!**

---

## 📚 Riferimenti

- [Folio Dynamic Pages Philosophy](./folio-dynamic-pages-philosophy.md)
- [Folio Pages Documentation](../../Modules/Cms/docs/folio_pages.md)
- [Laravel Localization](../../Modules/Cms/docs/folio-routing-locale.md)

---

**Status**: ⚠️ CRITICO - Tutti gli agenti AI devono consultare questa guida prima di lavorare sulle pagine!
**Last updated**: 2026-02-07