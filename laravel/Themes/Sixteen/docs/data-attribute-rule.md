---
title: "Data Attribute Rule - nessun data-* col nome del tema"
type: rule
theme: Sixteen
tags: [design-comuni, html-parity, data-attributes, mobile-nav, rule]
created: 2026-10-29
updated: 2026-10-29
qmd: "Sixteen data attribute rule data-sixteen prefisso tema html parity design comuni data-element data-bs mobile-nav dataset"
issues: []
discussions: []
related:
  - ./body-class-rule.md
  - ./html-parity-workflow.md
  - ./wiki/concepts/mobile-navigation-contract.md
  - ./stories/data-attribute-parity.story.md
  - ../../../../docs/wiki/rules/013-design-comuni-html-match.md
status: stable
---

# Data Attribute Rule - HTML Parity Enforcement

**Status**: Active
**Created**: 2026-10-29
**Category**: HTML Parity / Design Comuni
**Gemella di**: [body-class-rule.md](./body-class-rule.md) (stesso principio, sugli attributi invece che sulle classi del `<body>`)

## Rule Statement

**MAI** dare a un attributo `data-*` un nome che contiene il nome del tema, di un progetto o di un modulo.

```html
<!-- SBAGLIATO -->
<div class="it-nav-wrapper" data-sixteen-mobile-nav>
<button data-sixteen-mobile-nav-toggle data-sixteen-mobile-nav-target="#nav">

<!-- GIUSTO: aggancio del riferimento, nessun attributo proprio -->
<button class="custom-navbar-toggler" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">

<!-- GIUSTO quando serve un aggancio che il riferimento non ha: nome del comportamento, non del tema -->
<div class="it-nav-wrapper" data-mobile-nav>
```

Vale per ogni sorgente del tema: Blade, JS (`getAttribute`, `hasAttribute`, `dataset.*`), CSS (`[data-...]`) e test. Il guard e' `tests/Unit/DataAttributeParityContractTest.php`.

## Perche'

Il tema Sixteen e' la copia 1:1 del kit [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/). La regola [013](../../../../docs/wiki/rules/013-design-comuni-html-match.md) (CRITICAL) dice che l'HTML dentro `<body>` deve essere uguale a quello del riferimento. Un `data-sixteen-*` e' HTML che il riferimento non ha: e' drift, come una classe custom sul `<body>`.

Inventario dell'intero riferimento (33 pagine, **12 nomi** `data-*` distinti, 1780 occorrenze, **nessuno** con il nome di un tema o di un progetto):

| Attributo | Occorrenze | Pagine | A cosa serve |
|---|---:|---:|---|
| `data-element` | 1069 | 33 | ruolo dell'elemento (`main-navigation`, `personal-area-login`, `feedback-rating-answer`...): il pattern nativo per test e analytics |
| `data-bs-toggle` | 278 | 33 | Bootstrap Italia: `modal`, `collapse`, `dropdown`, `navbarcollapsible` |
| `data-bs-target` | 259 | 33 | Bootstrap Italia: pannello comandato (`#nav4`, `#search-modal`, `#header-nav-wrapper`) |
| `data-step` | 60 | 20 | numero del passo dello stepper |
| `data-bs-autocomplete` | 43 | 33 | campo di ricerca |
| `data-bs-parent` | 29 | 3 | accordion |
| `data-bs-navscroll` | 15 | 14 | scorrimento della navigazione |
| `data-bs-dismiss` | 9 | 5 | chiusura modale |
| `data-audio` | 7 | 2 | lettore audio |
| `data-focus-mouse` | 6 | 2 | gestione del focus |
| `data-bs-carousel-splide` | 4 | 4 | carosello |
| `data-bs-validate` | 1 | 1 | validazione del form |

Rigenerare l'inventario (sola lettura, 34 GET sul sito pubblico; il 2026-10-29 da' esattamente i numeri sopra):

```bash
B=https://italia.github.io/design-comuni-pagine-statiche/sito
curl -s "$B/" | grep -oE 'href="[^"#?]+\.html"' | sed 's/href="//;s/"$//' | sort -u \
  | while read p; do curl -sL --max-time 30 "$B/$p"; done \
  | grep -oE ' data-[a-zA-Z0-9_-]+' | sort | uniq -c | sort -rn
```

Il menu mobile del riferimento non ha nessun attributo proprio: `div.it-nav-wrapper` > `div.it-header-navbar-wrapper#header-nav-wrapper` > `button.custom-navbar-toggler[data-bs-target="#nav4"][data-bs-toggle="navbarcollapsible"]` e `div.navbar-collapsable#nav4` con `div.overlay` e `button.btn.close-menu`.

## Scala di scelta (si ferma al primo gradino che regge)

1. **Aggancio del riferimento**: classe, `id` o `data-bs-*` che il riferimento gia' ha. Il renderer canonico dell'header (`components/sections/header/v1.blade.php`) vive solo di questi.
2. **`data-element="<ruolo>"`**: se serve un aggancio stabile per test o analytics, e' il modo in cui il riferimento stesso li marca. Il valore e' il ruolo dell'elemento, non il tema.
3. **`data-<ruolo-del-comportamento>`**: solo se i gradini 1 e 2 non bastano (es. `data-mobile-nav`). Il nome descrive cosa fa il componente, mai chi lo ospita. Niente prefisso di tema, modulo o progetto.
4. **Stato solo-runtime via `dataset`**: un flag che serve al JS (es. `mobileNavBound`) non va nell'HTML renderizzato dal server, quindi non compare nel confronto con il riferimento.

## Sbagliato -> Giusto (rinomina applicata il 2026-10-29)

| Sbagliato | Giusto |
|---|---|
| `data-sixteen-mobile-nav` | `data-mobile-nav` |
| `data-sixteen-mobile-nav-target` | `data-mobile-nav-target` |
| `data-sixteen-mobile-nav-toggle` | `data-mobile-nav-toggle` |
| `data-sixteen-mobile-nav-panel` | `data-mobile-nav-panel` |
| `data-sixteen-mobile-nav-overlay` | `data-mobile-nav-overlay` |
| `data-sixteen-mobile-nav-close` | `data-mobile-nav-close` |
| `data-sixteen-alpine-components` (su `<html>`, scritto da `resources/js/app.js`) | `data-alpine-components` |
| `data-sixteen-mobile-nav-target="#searchModal"` (trigger di modale, in `ui/header`) | `data-bs-target="#searchModal"` (il riferimento ha `data-bs-toggle="modal" data-bs-target="#search-modal"`) |

**Aperto: il tag `<header>`.** Nel riferimento il tag e' `<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">` (`homepage.html` riga 21). 7 varianti Sixteen su 10 lo hanno gia' cosi' (tra cui il canonico `sections/header/v1`, `layout/design-comuni-header` e `header-comune`). Tre portano invece `data-mobile-nav-target="#header-nav-wrapper"` dopo la rinomina: `ui/header.blade.php:11`, `ui/app/header.blade.php:6`, `sections/header_bi5.blade.php:1`. Su quei tre l'attributo di parity corretto e' `data-bs-target`, non un nome generico: la rinomina `sixteen-` -> niente prefisso e' il minimo, non il traguardo. Sono file in lavorazione di un altro agente (task `data-attribute-parity`): decisione sua.

## Da quali varianti di header dipende davvero il nome generico

`resources/js/theme/header-mobile-nav.js` cerca sempre per primo l'aggancio del riferimento (`.custom-navbar-toggler[data-bs-target="#nav4"]`, `#nav4.navbar-collapsable`, `.navbar-collapsable > .overlay`, `.navbar-collapsable .close-menu`) e solo dopo quello generico. Sulle pagine con `header.it-header-wrapper` valgono anche `[data-bs-toggle="navbarcollapsible"]` e `.navbar-collapsable`.

Nessuna delle 6 varianti legacy ha `data-bs-toggle="navbarcollapsible"` sul toggler. Quindi l'unico attributo **indispensabile** e' `data-mobile-nav-toggle` sul toggler; `data-mobile-nav-target` serve dove il pannello non e' `#nav4`. Pannello, overlay e chiusura si agganciano gia' per classe quando la pagina ha `header.it-header-wrapper`.

| Variante (`resources/views/components/...`) | Pannello | Toggler agganciato da | Chi la renderizza |
|---|---|---|---|
| `sections/header/v1` (canonico) | `#nav4` | `data-bs-target` + `data-bs-toggle` del riferimento | `sections/header.blade.php` via `@include`; il tenant `local/techplanner` ha `"view": "pub_theme::components.sections.header.v1"` in `sections/header.json` |
| `ui/app/header` | `#nav4` | `data-mobile-nav-toggle` | tag `<x-ui.app.header />` in `Modules/UI/.../layouts/app.blade.php:7`; il modulo UI ha un file omonimo, non verificato quale risolva |
| `layout/design-comuni-header` | `#nav4` | `data-mobile-nav-toggle` | nessun consumatore nel codice; citato solo da `HeaderAreaPersonaleLinksContractTest` (legge il sorgente) |
| `sections/header_bi5` | `#nav4` | `data-mobile-nav-toggle` | nessun consumatore |
| `blocks/header/nav-wrapper` | `#nav-main` | `data-mobile-nav-toggle` + `-target` | nessun consumatore (e' un frammento senza `header.it-header-wrapper`) |
| `ui/header` | `#nav` | `data-mobile-nav-toggle` + `-target` | nessun consumatore; **non compila**: righe 59 e 66 hanno `__('Accedi all'area personale')` con l'apice non escapato (PHP Parse error), dal commit `6ef52b0e` del 2026-09-24. Un mese senza che nessuno se ne accorga e' la prova piu' forte che sia codice morto |
| `agid/header` | `#nav` | `data-mobile-nav-toggle` + `-target` | nessun consumatore |

"Nessun consumatore" = nessun tag `<x-...>`, `@include`, `view()` o nome in JSON di contenuto in `Themes/Sixteen`, `Modules/*` e `laravel/config`. Un riferimento dinamico da dati nel database non e' escludibile da qui.

## Come verificare

```bash
# 1) nessun nome di tema nei sorgenti (deve stampare 0)
grep -rIn "data-sixteen\|dataset\.sixteen" laravel/Themes/Sixteen/resources laravel/Themes/Sixteen/app laravel/Themes/Sixteen/tests | grep -v '\.lock:' | wc -l

# 2) il guard (il test legge solo file, ma Pest parte con .env.testing, che qui punta a MySQL con dati sacri: lanciarlo solo dove esiste un DB di test)
cd laravel && ./vendor/bin/pest Themes/Sixteen/tests/Unit/DataAttributeParityContractTest.php

# 3) il confronto con il riferimento (regola 013)
bashscripts/html/html-structure-compare.sh <pagina>
```

Il guard non e' un test di browser: dice che il nome non e' scritto nei sorgenti, non che il menu si apra. La prova vera e' il browser a 390 px dopo la build.

## Nota build

I bundle in `public/assets/` sono tracciati in git e vengono dalla build di Vite. Finche' non si esegue `npm run build` in `laravel/Themes/Sixteen`, il tema serve ancora il vecchio JS: il chunk `theme-behavior-DTzH7RaW.js` contiene `data-sixteen-mobile-nav-toggle` e non `data-mobile-nav-toggle`, e `app-D3yWoKfy.css` ha ancora il selettore `data-sixteen-mobile-nav`. Con Blade nuove e bundle vecchio il toggler delle 6 varianti legacy non apre il menu; il renderer canonico `header/v1` non e' toccato perche' usa solo gli agganci del riferimento. La build e' una decisione dell'utente.

## Lezione

Un nome non e' neutro: `data-sixteen-*` sembrava un modo ordinato di evitare collisioni, ma in un tema che deve restare uguale al riferimento il nome del tema nel markup e' gia' una differenza. L'errore e' nato per tre ragioni: la regola 013 (HTML = riferimento) copriva il `<body>` e le classi ma non nominava gli attributi; nessuna riga della trigger map la caricava per un lavoro su header o UX mobile; e [html-parity-workflow.md](./html-parity-workflow.md) e [block-implementation-guide.md](./block-implementation-guide.md) dicevano «MAI usare `data-bs-*`», cosi' chi cercava un aggancio per il menu inventava un nome proprio invece di copiare `data-bs-target`/`data-bs-toggle` del riferimento (che il canonico `header/v1` usa, con il runtime del tema al posto di Bootstrap JS). Le due righe sono state corrette il 2026-10-29. Prima di aggiungere un attributo, cercarlo nelle 33 pagine del riferimento.

## GitHub (tracciamento)

Nel repo del tema (`laraxot/theme_sixteen_fila5`) non esiste ancora una issue o una discussion su questo argomento: le piu' vicine, [#54](https://github.com/laraxot/theme_sixteen_fila5/issues/54) (allineamento docs) e [#65](https://github.com/laraxot/theme_sixteen_fila5/issues/65) (route in Front Office), trattano altro e non le uso come tracciamento. Le [#383](https://github.com/laraxot/base_fixcity_fila5/issues/383) e [#392](https://github.com/laraxot/base_fixcity_fila5/discussions/392) citate dal [contratto di navigazione mobile](./wiki/concepts/mobile-navigation-contract.md) sono del repo `base_fixcity_fila5`. Aprire una issue nel repo del tema e' un'azione verso l'esterno: da decidere.
