---
title: "Parity degli attributi data-*: via il nome del tema dal markup di Sixteen"
type: story
theme: Sixteen
status: review
created: 2026-10-29
updated: 2026-10-29
tags: [sixteen, design-comuni, html-parity, data-attributes, mobile-nav, bmad]
qmd: "Sixteen data-sixteen data-mobile-nav html parity design comuni attributi data rinomina header varianti legacy"
issues: []
discussions: []
related:
  - ../data-attribute-rule.md
  - ../wiki/concepts/mobile-navigation-contract.md
  - ./mobile-ux-pass.story.md
  - ../body-class-rule.md
  - ../../../../../docs/wiki/rules/013-design-comuni-html-match.md
---

# Parity degli attributi data-*: via il nome del tema dal markup di Sixteen

**Fase BMAD**: Dev + Docs (rinomina fatta dall'agente `codex`, task `data-attribute-parity`; documentazione e guardia qui).

## Richiesta dell'utente

> «nel tema sixteen introdurre data-sixteen-mobile-nav e' sbagliato ! il tema sixteen deve essere con altissima html parity con designcomuni https://github.com/italia/design-comuni-pagine-statiche https://italia.github.io/design-comuni-pagine-statiche ed in questo progetto il tema da modificare era il two, cmq per spiegarti tutti i data-sixteen-* dovevano essere semplicemente data-* , cerca di capire la regola, la convensione, il senso , va a fondo, ragiona , aggiorna studia e migliora le cartelle docs dentro i moduli e dentro i temi»

## Causa

1. **Due errori in uno.** La passata mobile del 2026-10-28 e' partita dalla premessa sbagliata «il tema da migliorare e' Sixteen»: il tema servito dal tenant `local/techplanner` e' **Two**, e Sixteen e' il clone Design Comuni del progetto Fixcity, dove si corregge solo la parity verificata sul riferimento. Poi, su un tema che deve essere 1:1 col riferimento, si e' lavorato sopra un attributo che il riferimento non ha.
2. **L'attributo non e' nato oggi.** `data-sixteen-mobile-nav*` e' nei sorgenti del tema dal 2026-05-28 (commit `8fea4370`), `data-sixteen-alpine-components` dal 2026-04-14 (`5d09338a`); l'ultima variazione committata e' del 2026-10-15 (`354a3cb8`). Il diff non committato di oggi contiene 63 righe che *tolgono* `data-sixteen-*` e 0 che lo aggiungono. Ma e' stata una passata di oggi a lavorarci sopra senza metterlo in discussione.
3. **La regola c'era, ma non lo diceva.** [013](../../../../../docs/wiki/rules/013-design-comuni-html-match.md) (CRITICAL) impone che l'HTML dentro `<body>` sia uguale al riferimento, e `body-class-rule.md` lo applica alle classi del `<body>`. Nessuna delle due nominava gli attributi `data-*`. In piu' la 013 era richiamata dalla trigger map solo nell'indice finale (riga ~579): nessuna riga task «UI/UX mobile / header / Sixteen» la caricava.
4. **Due guide dicevano il contrario.** `html-parity-workflow.md` (righe 160 e 208) e `block-implementation-guide.md` (riga 64) dicevano «MAI usare `data-bs-*`», mentre il riferimento ha `data-bs-toggle` 278 volte e `data-bs-target` 259 e il canonico `sections/header/v1` li usa (con il runtime del tema al posto di Bootstrap JS, che non e' caricato). Chi seguiva la guida non poteva copiare l'aggancio del riferimento e ne inventava uno proprio. Corrette il 2026-10-29.
5. **Il riferimento e' chiaro.** 33 pagine, 12 nomi `data-*`, 1780 occorrenze, nessuno col nome di un tema o di un progetto; il menu mobile non ne ha di propri (inventario e comando di rigenerazione in [data-attribute-rule.md](../data-attribute-rule.md)).

## Cosa e' cambiato

| Dove | Cambiamento | Chi |
|---|---|---|
| 10 file (`git diff -G'data-sixteen'`): le 6 Blade di header, `resources/js/theme/header-mobile-nav.js`, `resources/js/app.js`, `css/app/12-wizard-dc-form-shell.css`, `css/app/13-final-runtime-overrides.css` | `data-sixteen-X` -> `data-X` (stesso suffisso); `data-sixteen-alpine-components` -> `data-alpine-components`; flag runtime `dataset.mobileNavBound`; il trigger di modale `data-sixteen-mobile-nav-target="#searchModal"` -> `data-bs-target="#searchModal"`. I due test `HeaderV1SlimDropdownContractTest.php` e `header-menus-keyboard.spec.mjs` sono modificati nello stesso task, senza righe `data-sixteen` nel diff | `codex` (task `data-attribute-parity`) |
| `components/blocks/segnalazioni/layout.blade.php:288` | tolti `loading="lazy" decoding="async"` aggiunti dalla passata mobile: sono markup che il riferimento non ha | questa sessione |
| `docs/data-attribute-rule.md` (nuovo) | la regola, la scala di scelta, l'inventario del riferimento, la mappa Sbagliato -> Giusto | questa sessione |
| `docs/wiki/concepts/mobile-navigation-contract.md` | stato verificato delle varianti e dei selettori; rimando alla rule | questa sessione |
| `docs/stories/mobile-ux-pass.story.md` | premessa corretta (tema effettivo = Two), difetto 5 annullato | questa sessione |
| `docs/html-parity-workflow.md`, `docs/block-implementation-guide.md` | tolto «MAI usare `data-bs-*`»; nuova sezione «Attributi data-*» con rimando alla rule | questa sessione |
| `tests/Unit/DataAttributeParityContractTest.php` (nuovo) | guardia: nessun `data-sixteen/two/fixcity/techplanner` ne' `dataset.sixteen*` in Blade, JS, CSS; ogni `data-mobile-nav*` emesso ha un consumatore in JS/CSS | questa sessione |

## Verifica

- `grep -rIn "data-sixteen\|dataset\.sixteen" resources app tests` (esclusi i `.lock`): **0** righe.
- Guardia, eseguita con un esecutore minimo (Pest non si lancia: `.env.testing` e' MySQL con dati sacri): sul tema reale **2 test su 2 verdi**. Su fixture temporanee fallisce dove deve: `data-sixteen-*` in una Blade e `dataset.sixteenFoo` in un JS (con la lista dei file); un `data-mobile-nav-ghost` che nessun JS legge; un `data-mobile-nav` base che il JS non legge anche se legge `data-mobile-nav-toggle`; albero vuoto (non passa a vuoto). Passa su fixture pulita anche con un `.lock` che contiene il nome vietato.
- Compilazione Blade + `php -l` delle 7 varianti di header: **6 su 7** compilano; `ui/header.blade.php` no (vedi sotto).
- Inventario del riferimento rigenerato con il comando della rule: 12 nomi, 1780 occorrenze, 0 con prefisso di tema.
- Il tenant attivo usa il canonico: `config/local/techplanner/database/content/sections/header.json` ha `"view": "pub_theme::components.sections.header.v1"`.

### Chi usa davvero le 6 varianti legacy di header

Ricerca in `Themes/Sixteen`, `Modules/*` e `laravel/config` di tag `<x-...>`, `@include`, `view()` e nomi in JSON di contenuto:

| Variante | Esito |
|---|---|
| `ui/app/header` | un solo consumatore: `<x-ui.app.header />` in `Modules/UI/resources/views/components/layouts/app.blade.php:7`. Il modulo UI ha un file omonimo (`components/ui/app/header.blade.php`): **non verificato** quale risolva |
| `layout/design-comuni-header` | nessun consumatore; citato solo da `HeaderAreaPersonaleLinksContractTest` (legge il sorgente) |
| `sections/header_bi5`, `blocks/header/nav-wrapper`, `agid/header` | nessun consumatore |
| `ui/header` | nessun consumatore e **non compila**: righe 59 e 66 `__('Accedi all'area personale')` con l'apice non escapato (PHP Parse error), dal commit `6ef52b0e` del 2026-09-24. Un mese senza che nessuno se ne accorga conferma che e' codice morto |

Un riferimento dinamico da dati nel database non e' escludibile da qui.

## Decisioni aperte

1. **`npm run build` in `Themes/Sixteen` (utente).** I bundle in `public/assets/` sono tracciati. Il chunk servito `theme-behavior-DTzH7RaW.js` ha ancora `data-sixteen-mobile-nav-toggle` (e `app-D3yWoKfy.css` il selettore `data-sixteen-mobile-nav`); finche' non si ricompila, il toggler delle varianti legacy non apre il menu. Il canonico non ne risente. Prima della build la guardia e il browser dicono cose diverse.
2. **Tag `<header>` su tre varianti (codex).** Il riferimento ha `<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">` (`homepage.html` riga 21) e 7 varianti su 10 lo hanno cosi'. Tre hanno `data-mobile-nav-target="#header-nav-wrapper"` dopo la rinomina: `ui/header.blade.php:11`, `ui/app/header.blade.php:6`, `sections/header_bi5.blade.php:1`. L'attributo di parity e' `data-bs-target`. Sono file nel lock di codex: non toccati qui.
3. **Le varianti legacy: tenerle o toglierle?** Quattro senza consumatori, una che non compila, una con un solo tag di rimando in un altro modulo. Rifinire codice morto e' lavoro sprecato; togliere file e' decisione dell'utente.
4. **Censire gli 83 attributi `data-*` custom** emessi dalle Blade di Sixteen (contro 12 nomi in tutto il riferimento). Molti stanno su pagine senza riferimento (`data-slug`, `data-side`, `data-url`...) e non vanno toccati senza mandato; vanno solo elencati, accanto al riferimento, per decidere quali sono pattern e quali drift.
5. **Issue nel repo del tema.** Non ne esiste una su questo tema in `laraxot/theme_sixteen_fila5` (le piu' vicine, #54 e #65, trattano altro); le #383 e #392 citate dal contratto mobile sono di `base_fixcity_fila5`. Aprirla e' un'azione verso l'esterno: da decidere.
6. **`docs/wiki/index.md` non aggiornato.** Ha un `.lock` vuoto (0 byte) del 2026-09-24, senza proprietario. Non l'ho rimosso: la regola di coordinamento dice di non forzare. Quando il lock viene liberato, aggiungere le voci per la rule e per questa story.
7. **Trigger map (root).** Aggiungere una riga task «header / menu mobile / UX / Sixteen / `data-*`» che carichi la 013 e la rule: e' la causa per cui la regola non e' stata letta. Fuori da questo tema; la gestisce il coordinatore.

## GitHub (tracciamento)

Nessuna issue o discussion del repo del tema (`laraxot/theme_sixteen_fila5`) copre questo lavoro: vedi la decisione 5. Riferimenti ereditati, di un altro repo: [base_fixcity_fila5#383](https://github.com/laraxot/base_fixcity_fila5/issues/383), [discussion #392](https://github.com/laraxot/base_fixcity_fila5/discussions/392).

## Lezione

Su un tema che deve restare uguale a un riferimento, il nome del tema nel markup e' gia' una differenza: prima di aggiungere un attributo si cerca nelle 33 pagine del riferimento, e il tema da modificare si decide leggendo `pub_theme` del tenant, non il nome della cartella piu' ricca di lavoro mobile.
