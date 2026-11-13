---
title: "Second Brain — Sixteen Theme Knowledge Base"
type: note
status: draft
updated: 2026-10-20
tags: [docs, sixteen, second-brain]
qmd: "second brain   sixteen theme knowledge base sixteen"
---
# Second Brain — Sixteen Theme Knowledge Base

## Key Concepts
- **Header Section** (`x-section slug="header"`) → `Modules/Cms/View/Components/Section` → `components/sections/header/v1.blade.php` → `header/tailwind.blade.php`
- **Footer Section** (`x-section slug="footer" tpl="full/slim"`) → `Modules/Cms/View/Components/Section` → `components/sections/footer/full.blade.php` → `footer/v1.blade.php`
- **Layout** (`x-layouts.app` / `.test` / `.guest`) → `components/layouts/app.blade.php` → incluyen header + footer
- **Ownership**: Sixteen è il tema Design Comuni; non creare namespace o layout `design-comuni` paralleli.
- **Data**: header/footer leggono i blocchi localizzati da `config/local/fixcity/database/content/sections/*.json`; il renderer non possiede contenuti tenant.
- **Fallback**: quando un renderer viene testato senza blocchi, usa identità FixCity e link localizzati validi, mai `#` o placeholder del comune.
- **Regola**: Un solo header, un solo footer, CMS-driven, `layout: "test"` per pagina
- **Prompt 04**: orchestratore FO (shell, inbound blog/eventi, auth, ticket, a11y) in
  `docs/prompts/04.md` — JSON=contenuto, Blade=vestito; eventi FO = `Article.content_type=event`.
- **Tipografia hero (D18)**: H1 home = Lora Regular 400 + `font-serif`; sans UI = Titillium ≤ 600.
  Guardia: `FontsCssGuardTest`.

## Regole (canonicali)
| ID | Regola | Descrizione |
|---|---|---|
| R001 | `no-bootstrap-runtime` | Usare solo Tailwind/DaisyUI/Flowbite |
| R002 | `layout-json-rule` | JSON layout determina componente |
| R003 | `no-ridondanze` | Sixteen non chiama se stesso |

## Block Catalog
- `components.blocks.homepage` — Hero, news cards, CTA
- `components.blocks.segnalazione-dettaglio` — Privacy, dati, riepilogo
- `components.blocks.template-index` — Indice template
- `auth.login` JSON — `pages/auth/login.blade.php.json` + `content/pages/auth.login.json`; slug `auth.login`; blade vestito, CMS contenuto; nessuna Resource Filament tema
- `XotBaseMigration` — ogni migrazione che estende deve dichiarare esplicitamente `protected ?string $model_class`; nome file deve seguire `create_<table>_table.php`; se tabella esiste già, `up()` vuota + commento BMAD
- Regola preventiva (D34): `grep -L 'model_class' ...` deve essere vuoto per tutte le migrazioni XotBaseMigration; commento BMAD obbligatorio in cima; nessun controller
- Regola inviolabile D35 — dati sacri: mai `migrate:fresh`, `migrate --force`, `migrate:refresh`; nei test mai `RefreshDatabase`; documento primario `Modules/Fixcity/docs/REGOLA_DATI_SACRI.md`
- Regola D36 — `make:filament-user` / UUID: ownership su connessione `user` (`fixcity_user`), non alterare `fixcity.users` legacy. Canonico: `Modules/Fixcity/docs/bmad/filament-user-uuid-connection-audit.md` (la story Sixteen `discrepanza-db-codice-users-id` è superseded)
- Regola D37 — localizzazione mcamara: usare `localizeURL($url, $locale?)`; `getLocalizedURL($locale, $url)` ha il locale per primo. Mai `getLocalizedURL('/it')` né `getLocalizedURL('/')`. Vedi `docs/bmad/locale-unsupported-correction.story.md`
- Regola D38 — login i18n: contratto `user::auth.login.*.text` + `pub_theme::accessibility.title.text`; DE non deve riscrivere `login.google` senza `button`; helper `user_form` non inquinano il login FO

## QMD Index
```bash
qmd search "header footer sections contract" -c sixteen-theme -n 5
qmd get qmd://sixteen-theme/bmad/header-footer-sections-contract.md
```

## Verifica corrente

- `php artisan view:cache`: pass
- test `FooterLocalizationTest` e `HeaderTenantFallbackTest`: 5 pass / 34 assertions
- Playwright 375/768/1440: HTTP 200, no page errors, no overflow, no Bootstrap asset
- smoke JSON sections (2026-10-11): `/it/tests` e `/it/tests/homepage` mostrano
  `Nome del Comune`, `Nome della Regione`, le colonne footer e zero `href="#"`
  nella shell; nessun page error e nessun overflow.

## Elementi section documentati

Header: top bar regionale, link utility, selettore lingua Alpine, accesso area
personale, logo, nome ente, slogan, ricerca, hamburger responsive e navigazione
primaria. Footer: brand, colonne di link, contatti con email/telefono, orari,
social esterni sicuri, privacy/accessibilità/note legali e copyright. Il
contratto dati completo è `bmad/header-footer-sections-contract.md`.
Il modello dettagliato e la matrice elemento/chiave sono in
`bmad/header-footer-data-model.md`; la motivazione della propagazione di
`blocks` è in `second-brain/header-footer-rationale.md`.
