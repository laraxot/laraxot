---
title: "Sixteen: ingresso alla documentazione"
type: readme
tags: [documentation, sixteen]
created: 2026-09-26
updated: 2026-10-27
qmd: "sixteen theme readme stato pub_theme vivo contenuti conteggi build npm limiti rotte dormienti layout design-comuni"
issues: []
discussions: []
status: active
---

# Sixteen

**Scopo:** Sixteen e' il vestito Design Comuni del FO Fixcity: compone Folio/Volt e monta widget dei moduli owner (User, Cms, Comment, …) senza possedere Resource Filament ne' Actions di dominio. Hub BMAD: [bmad/decision-log.md](./bmad/decision-log.md), [bmad/filament-blueprint.md](./bmad/filament-blueprint.md).

## Operational guides

- [Verified recovery, accessibility corrections and evidence](./bmad/verification-recovery.md)
- [Lista categorie: contratto di rendering](./bmad/lista-categorie-rendering-contract.md)

- [Documentation index](./index.md)
- [Documentation naming and frontmatter rules](../../../../bashscripts/ai/wiki/rules/markdown-file-naming-and-frontmatter.md)
- [Dependency refresh and verification](./bmad/dependency-refresh-and-verification.md)
- [Typography remediation](./bmad/typography-comunidesign-remediation-bmad.md)
- [FAQ route ownership](./bmad/faq-route-ownership.md)
- [FAQ CSS remediation plan](./bmad/plans/css-data-page-elimination-plan.md)
- [Login UX and reusable CSS contract](./auth/login-ux-fixes.md)
- [Testing boundary](./testing-convention.md)

### Regola prima di modificare una pagina pubblica

La risoluzione canonica è `URL → Folio shell → slug CMS → JSON tenant → block
view → action del modulo`. Non aggiungere una Blade in `pages/<slug>/` per
correggere una pagina già coperta dallo shell dinamico: per FAQ ciò significa
che `resources/views/pages/domande-frequenti/index.blade.php` deve restare
assente. Il test di contratto lo verifica; il dettaglio è in
[faq-route-ownership.md](./bmad/faq-route-ownership.md).

Tema pubblico (front office) di Fixcity: resa Design Comuni su Laravel 13, PHP 8.4, Filament 5. Biglietto da visita: [IT](../README.md), [EN](./readme-en.md). L'indice operativo e' [00-INDEX.md](00-INDEX.md).

`README.md` è l'ingresso canonico del tema. `00-INDEX.md` è l'indice operativo
(documenti e cartelle): orienta verso i documenti, ma non duplica i contratti.
`index.md` è un router storico; `INDEX.md` non esiste più (rimosso da git).

## Stato verificato (2026-10-14)

| Domanda | Risposta | Prova |
|---|---|---|
| E' il tema in uso? | si, vivo | `'pub_theme' => 'Sixteen'` in tutti e 4 i `laravel/config/**/xra.php`; `public_html/themes/Sixteen` e' un link a `laravel/Themes/Sixteen/public/` |
| Dove vive il codice? | repo git separato (submodule) | `gitmodules.ini`, remote `git@github.com:laraxot/theme_sixteen_fila5.git`; Composer `laraxot/theme_sixteen_fila5`, namespace `Themes\Sixteen\` (app/), provider `Providers\ThemeServiceProvider` che estende `XotBaseThemeServiceProvider` |
| Altri temi | `TwentyOne` (importato da Predict, non usato), `Barthelemy` e `Meetup` (gusci senza codice); al 2026-10-28 `laravel/Themes/` contiene solo Meetup, Sixteen, TwentyOne, Two, Zero: `Barthelemy` non è presente | [Meetup/docs](../../Meetup/docs/00-index.md) |

## Cosa contiene (conteggi del 2026-10-14)

- `resources/views/`: 957 file Blade. `layouts/` (11), `components/` (795, di cui `components/blocks/` con 166 voci e 508 Blade, piu' `header/`, `footer/`, `wizard/`, `layouts/`), `pages/` (Folio: `[container0]/`, `tests/[slug]`), `sections/` (footer), `partials/` (accessibility-bar, cookie-bar).
- `resources/css/` (95 file) e `resources/js/` (21 file); punti d'ingresso Vite: `app.css`, `app-test.css`, `app.js` e i Lit di Geo (`map-lit.js`).
- `app/` (79 file PHP): Actions (menu, SPID, CIE, blocchi, asset, URL), Enums (24), Models (13, di cui 9 in `Municipal/`), Http (5 controller, 2 middleware, 1 componente Livewire), Providers, Support, View.
- `lang/` it (43 file), en (43), de (16), es (15).
- `tests/`: 15 test Unit, 6 Feature, 10 spec Playwright in `tests/browser/`.
- `scripts/`: smoke e verifiche di parity (`map-*-smoke.cjs`, `auth-register-smoke.cjs`, `visual-parity.mjs`).

## Come si costruisce (da `package.json`)

<<<<<<< HEAD
- `npm run build`: `npm run prepare:static-assets` (`resources/scripts/prepare-static-assets.mjs`) e poi `vite build` (uscita in `public/`, collegata a `public_html/themes/Sixteen`). Il package non espone uno script `copy`: il webroot è già collegato alla cartella `public/` del tema.
=======
- `npm run build`: `npm run prepare:static-assets` (`resources/scripts/prepare-static-assets.mjs`) e poi `vite build` (uscita in `public/`, collegata a `public_html/themes/Sixteen`). `build:with-webroot` e' `vite build` + `npm run copy`; la vecchia catena `vite build` + `copy` + `php artisan view:clear` e' ora `build1`.
>>>>>>> f08c6884 (.)
- `npm run dev`: server Vite. `npm test`: Jest. `npm run lint` e `npm run format`.
- Smoke del browser: `test:map-picker-smoke`, `test:map-lit-smoke`, `test:map-lit-stress`, `test:auth-register-smoke`.
- Riferimento esteso: le 14 guide Vite in [build/](build/) (nessun indice proprio; partire da [vite-configuration-guide.md](build/vite-configuration-guide.md)).

## Limiti noti (verificati)

- Il tema contiene controller e rotte (`app/Http/Controllers/`, `routes/web.php`, `routes/auth.php`) che a runtime non risultano registrati: `php artisan route:list` (461 rotte, senza route cache) non ha ne' `comune/*` ne' `auth/spid` ne' `auth/cie`. Sono codice dormiente, non la via con cui il tema serve le pagine.
- Restano viste legacy non usate dal CSS principale: `resources/views/bootstrap-italia/` (16 Blade), `layouts/bootstrap-italia*.blade.php` (4), `pages_old/` (45); gli `@import` di Bootstrap Italia in `app.css` sono commentati.
- Il layout pubblico canonico è `components/layouts/app.blade.php`: Sixteen è già Design Comuni. `design-comuni-authenticated.blade.php` e `design-comuni-bare.blade.php` restano solo template specializzati per le pagine di test che li dichiarano esplicitamente.

## Ownership e stack canonico

Sixteen è già il tema Design Comuni: non si crea un secondo tema, layout o
namespace `design-comuni`. Il rendering usa TailwindCSS (`@apply`), Alpine.js,
Lit, DaisyUI e Flowbite; Bootstrap Italia non è una dipendenza runtime.

Sixteen non possiede `app/Jobs/`, `app/Services/` o `app/Servces/`: i flussi
asincroni restano nelle Queueable Actions del modulo owner. Il tema si limita a
comporre viste e a invocare il contratto dell'Action.

I data object appartengono invece al modulo owner: quando il tema consuma un
contratto dati, importa il namespace canonico `Modules\\<Module>\\Datas` e non
crea copie in `Themes/Sixteen/app/Data(s)`. La cartella `Datas` distingue il
catalogo di oggetti di trasferimento dal codice visuale del tema e impedisce
che una view diventi proprietaria della logica di dominio.

- contenuti: JSON CMS, con view block riusabili;
- shell pubblica: `x-layouts.app` e, quando dichiarato dal contratto pagina,
  `x-layouts.test`;
- routing pubblico: Folio e `PageSlugMiddleware`;
- accessibilità: WCAG 2.1 AA come requisito minimo, con verifica manuale e automatizzata;
- build: Vite, con asset generati nella public del tema.

I temi **non** contano modelli/migrazioni, ma gli agenti che toccano Fixcity/User devono rispettare:

- [Parità modulo N=N](../../../../bashscripts/ai/wiki/bmad/architecture-module-model-artifact-parity.md) — audit: `bashscripts/tools/audit-module-artifact-parity.sh Fixcity`
- [Dati sacri](../../../../bashscripts/ai/wiki/rules/data-sacred-no-destructive-db.md) — solo `php artisan migrate`
- Wiki tema: [docs/wiki/](./wiki/) · ingest: `bashscripts/docs/llm-wiki-qmd.sh update`

Nessun nuovo contenuto va in `docs/archive/`; le storie non sono da duplicare
fuori da BMAD e la memoria wiki storica resta in `docs/wiki/_archive/`. Regola
Second Brain: [no-docs-archive](../../../../bashscripts/ai/wiki/concepts/no-docs-archive-rule.md).

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./readme-en.md) |
| 📚 Wiki tecnica | [./wiki/](./wiki/) |
| 💶 Sostenibilità FO | [project-sustainability-front-office.md](./project-sustainability-front-office.md) |
| 📣 Inbound blog/eventi | [inbound-marketing-blog-eventi.md](./inbound-marketing-blog-eventi.md) |
| 🧭 Dipendenze e font | [Sixteen dependency maintenance](./bmad/dependency-refresh-and-verification.md) |
| 🧠 Moduli e temi (repo git separati) | [Multi-repo map](../../../../docs/wiki/rules/multi-repo-modules-themes-map.md) |

## Verifica 2026-10-19

Il pipeline font canonico usa `laravel-vite-plugin/fonts` (`google(...)`) in
`vite.config.js` per Titillium Web, Lora e Roboto Mono. Vite emette il
`fonts-manifest.json` e gli asset hashati sotto `public/assets/`; il build
obbligatorio è passato con `npm run build`. La pagina di riferimento per la
tipografia è il Design Comuni, mentre Bootstrap Italia resta solo riferimento
normativo/stilistico e non dipendenza runtime.

---
**Tema** `Sixteen` · **Laraxot** · **FixCity Platform** · PHPStan `level: max` (`laravel/phpstan.neon`) · Filament 5
