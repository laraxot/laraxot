# Visione e obiettivi - Tema Sixteen

## Mission

Il tema Sixteen e il tema ufficiale per applicazioni Laravel destinate alla Pubblica
Amministrazione italiana. Implementa le Linee Guida di Design per i Servizi Digitali della PA
(AGID) e si integra con il Design System di Bootstrap Italia v2.16.

**A chi si rivolge**: comuni, enti pubblici, portali istituzionali, applicazioni di servizi
civici (segnalazioni, appuntamenti, documenti pubblici, eventi municipali).

**Scopo principale**: fornire un tema produttivo, conforme AGID, accessibile WCAG 2.1 AA,
integrabile con il pannello Filament 5.x e il modulo CMS.

---

## Stack tecnologico

### CSS e design

| Tecnologia | Versione | Ruolo |
|------------|----------|-------|
| Bootstrap Italia | 2.16.0 | Design System AGID ufficiale |
| Tailwind CSS | 4.1.13 | Utility-first CSS, personalizzazioni |
| DaisyUI | 4.12.22 | Componenti Tailwind estesi |
| Flowbite | 2.5.1 | Componenti interattivi Tailwind |
| PostCSS | 8.5.6 | Pipeline CSS |

**Font AGID ufficiali**:
- Titillium Web (font primario PA)
- Roboto Mono (font monospace)
Entrambi caricati da Google Fonts con `display=swap`.

**Colori AGID**:
- Verde PA primario: `#00814A` (Design Comuni)
- Blu istituzionale: `#0066CC`
- Rosso errore: `#D9364F`
- Giallo warning: `#F5A623`
- Verde successo: `#00B373`

### JavaScript

| Tecnologia | Versione | Ruolo |
|------------|----------|-------|
| Alpine.js | via Filament | Interattivita componenti |
| Swiper | 11.1.10 | Slider e carousel |
| Flowbite JS | 2.5.1 | Dropdown, modal, tooltip |
| vanilla-cookieconsent | 3.0.1 | Banner consenso cookie GDPR |
| Vite | 7.0.7 | Build tool |
| laravel-vite-plugin | 2.0.0 | Integrazione Laravel |

### PHP e Laravel

| Tecnologia | Versione |
|------------|----------|
| PHP | 8.3+ |
| Laravel | 12.x |
| Filament | 5.x |
| Livewire | 4.x |

### Integrazioni speciali

- **SPID** (Sistema Pubblico di Identita Digitale): controller e servizi dedicati
  (`SpidAuthController`, `SpidAuthService`, eventi `SpidAuthenticated/SpidLoggedOut`)
- **CIE** (Carta d'Identita Elettronica): controller e servizi dedicati
  (`CieAuthController`, `CieAuthService`, eventi `CieAuthenticated/CieLoggedOut`)
- **PWA** (Progressive Web App): middleware `PWAMiddleware`, service worker, pagina offline

---

## Struttura delle dipendenze PHP

Il tema dichiara dipendenza su tutto il pacchetto Filament:
- `filament/filament`, `filament/support`, `filament/forms`
- `filament/tables`, `filament/actions`, `filament/widgets`
- `filament/notifications`, `filament/infolists`

Il `ThemeServiceProvider` estende `XotBaseThemeServiceProvider` dal modulo Xot
e registra: Menu Builder, MenuFilter chain (Href/Active/Gate), servizi SPID/CIE,
view composers, comandi Artisan, route di autenticazione.

---

## Architettura dei componenti

### Categorie di componenti Blade (374 view totali)

| Categoria | Numero componenti | Descrizione |
|-----------|------------------|-------------|
| blocks/alerts | 4 | Alert, toast, messaggi |
| blocks/blog | 3+ | Griglia articoli, filtri |
| blocks/cards | 5+ | Card generiche e tematiche |
| blocks/cta | 3+ | Call to action |
| blocks/features | 3+ | Sezioni funzionalita |
| blocks/forms | 4+ | Form contatto, ricerca |
| blocks/hero | 3+ | Hero sections |
| blocks/navigation | 4+ | Menu, breadcrumb |
| blocks/services | 3+ | Card servizi PA |
| blocks/stats | 3+ | Statistiche e counter |
| agid/ | 3 | Header, footer, breadcrumb AGID |
| accessibility/ | 3 | Skip links, font-size, contrasto |
| municipal/ | 8 | Modelli entita municipali |
| auth/ | 6 | Form autenticazione |
| layout/ | 5+ | Layout strutturali |
| layouts/ | 5 | App, auth, base, guest, navigation |

### Modelli entita municipali

Il tema include modelli PHP per entita tipiche dei comuni:
- `MunicipalEvent`, `MunicipalNews`, `MunicipalService`
- `MunicipalLocation`, `ContactPoint`, `OrganizationalUnit`
- `PublicDocument`, `PublicPerson`
- `Appointment` (con Livewire component `CreateAppointment`)

### Pagine Folio (pages/)

Routing file-based con Laravel Folio:
- `[slug].blade.php`, `home.blade.php`, `index.blade.php`
- `auth/login.blade.php` (e login1-5 per varianti AGID)
- `dashboard/index.blade.php`
- `segnalazioni/create.blade.php`
- `services/index.blade.php`
- `news/index.blade.php`
- `profile/edit.blade.php`

---

## Localizzazione

Lingue supportate: italiano (it), inglese (en), tedesco (de).
Namespace traduzioni: `pub_theme`.

---

## Moduli dipendenti

| Modulo | Dipendenza |
|--------|------------|
| Xot | Base ThemeServiceProvider, utility blade |
| Cms | CMS sections/blocks rendering |
| UI | Componenti UI condivisi |
| Lang | Gestione localizzazione |
| Gdpr | Cookie consent integration |
| User | Autenticazione, profilo |

---

## KPI e metriche target

### Lighthouse (pagina homepage)

| Categoria | Attuale | Target |
|-----------|---------|--------|
| Performance | 78 | 95+ |
| Accessibility | 82 | 100 |
| Best Practices | 85 | 100 |
| SEO | 90 | 100 |

### Core Web Vitals

| Metrica | Attuale | Target | Soglia buona |
|---------|---------|--------|--------------|
| FCP (First Contentful Paint) | 2.1s | <1.5s | <1.8s |
| LCP (Largest Contentful Paint) | 3.2s | <2.5s | <2.5s |
| TBT (Total Blocking Time) | 320ms | <100ms | <200ms |
| CLS (Cumulative Layout Shift) | 0.12 | <0.05 | <0.1 |
| INP (Interaction to Next Paint) | 210ms | <100ms | <200ms |

### Bundle size

| Asset | Attuale | Target | Riduzione |
|-------|---------|--------|-----------|
| app.js | 850kb | 250kb | -71% |
| app.css | 450kb | 120kb | -73% |
| vendor.js | 1.2MB | 300kb | -75% |
| Totale gzipped | ~400kb | <200kb | -50% |

### Codebase

| Metrica | Attuale | Target |
|---------|---------|--------|
| Dimensione repo | 347MB | 45MB |
| Test coverage JS | 0% | 60% |
| Test coverage PHP | 15% | 80% |
| PHPStan level | non configurato | max |

### Conformita AGID

| Requisito | Status |
|-----------|--------|
| WCAG 2.1 AA | 60% |
| Contrasto colori 4.5:1 | Parziale |
| Focus visibile | Implementato |
| Skip links | Implementato |
| Screen reader support | Parziale |
| Titillium Web font | Implementato |
| Colori AGID ufficiali | Implementato |
