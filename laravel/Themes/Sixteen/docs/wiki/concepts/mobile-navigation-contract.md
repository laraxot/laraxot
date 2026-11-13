---
created: 2026-10-28
updated: 2026-10-29
qmd: "Sixteen mobile navigation responsive viewport focus trap touch targets map controls"
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/383
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/392
title: "Sixteen: contratto navigazione mobile"
type: concept
theme: Sixteen
tags: [mobile, navigation, accessibility, responsive, design-comuni]
status: stable
---

# Sixteen: contratto navigazione mobile

Il renderer canonico dell'header è `resources/views/components/sections/header/v1.blade.php`.
La navigazione mobile è gestita dal controller condiviso
`resources/js/theme/header-mobile-nav.js`.

Il markup deve restare quello canonico Design Comuni e deve esporre gli hook già
previsti dal riferimento:

- `data-bs-target="#nav4"` e `data-bs-toggle="navbarcollapsible"` sul toggler;
- `id="nav4"` e classe `navbar-collapsable` sul pannello;
- classe `overlay` per il backdrop e `close-menu` per la chiusura.

Sixteen non introduce attributi nominati dal tema: la parità
HTML con Design Comuni è parte della funzionalità, non solo un obiettivo visivo.
Il renderer canonico `header/v1` usa quindi gli attributi `data-bs-*` del reference;
gli hook generici `data-mobile-nav-*` restano soltanto un adapter per viste legacy
che non espongono ancora il markup completo del reference.

Il controller aggiorna `aria-expanded`, blocca lo scroll del documento, chiude con
overlay/Escape, mantiene il focus dentro il pannello aperto e riporta lo stato al
breakpoint desktop. Non aggiungere un secondo controller Alpine o una variante
inline nella Blade.

## Varianti non canoniche (stato verificato il 2026-10-29)

Regola e inventario del riferimento: [data-attribute-rule.md](../../data-attribute-rule.md).

- Il tenant `local/techplanner` renderizza l'header con `pub_theme::components.sections.header.v1` (`sections/header.json`): solo agganci del riferimento, nessun `data-mobile-nav-*`.
- Il controller prova prima l'aggancio del riferimento (`.custom-navbar-toggler[data-bs-target="#nav4"]`, `#nav4.navbar-collapsable`, `.navbar-collapsable > .overlay`, `.navbar-collapsable .close-menu`) e solo dopo `[data-mobile-nav-*]`. Sulle pagine con `header.it-header-wrapper` valgono anche `[data-bs-toggle="navbarcollapsible"]` e `.navbar-collapsable`.
- Sei varianti emettono `data-mobile-nav-*`: `ui/app/header`, `layout/design-comuni-header`, `sections/header_bi5` (pannello `#nav4`), `blocks/header/nav-wrapper` (`#nav-main`), `ui/header` e `agid/header` (`#nav`). Nessuna ha `data-bs-toggle="navbarcollapsible"`, quindi per loro `data-mobile-nav-toggle` e' l'unico attributo indispensabile e `data-mobile-nav-target` serve dove il pannello non e' `#nav4`; pannello, overlay e chiusura si agganciano gia' per classe.
- Una sola e' renderizzata nel codice (`<x-ui.app.header />` in `Modules/UI`, che ha un file omonimo: non verificato quale risolva). `ui/header` non compila (apice non escapato alle righe 59 e 66). Le altre quattro non hanno consumatori trovati: probabile codice morto, da decidere prima di rifinirle.
- Il bundle servito (`public/assets/theme-behavior-gLWDHyak.js`) e' pulito: build effettuato il 2026-10-29, i vecchi asset con `data-sixteen-*` sono stati eliminati (`npm run build` + rimozione manuale dei file stale).

## Viewport stretti e controlli mappa

Le coordinate del riferimento Design Comuni sono campioni visuali, non vincoli
assoluti: il pulsante di chiusura deve restare ancorato al bordo sicuro destro e
non usare `left: 330px`/`left: 360px`. Il test browser copre anche 320px.

I controlli interattivi della mappa hanno almeno 44px di area tattile sui device
touch. Gli input delle coordinate restano a 16px per evitare lo zoom automatico
di iOS; la dimensione visiva non deve prevalere sull'usabilità.

## Lezione di manutenzione

Il selettore `data-bs-toggle="navbarcollapsible"` resta il contratto canonico del
reference. Nei renderer non canonici, quando servono hook aggiuntivi, si usano attributi
`data-mobile-nav-*` generici: rendono il comportamento testabile senza legarlo al
tema. Non aggiungerli al markup canonico se il relativo attributo `data-bs-*` è già
disponibile. Sul tag `<header>` l'attributo di parity e' `data-bs-target="#header-nav-wrapper"`,
non `data-mobile-nav-target` (tre varianti non lo hanno ancora: vedi la rule).
