---
id: dcp-450
title: "DCP-450: peso e cache degli asset del tema (CSS 3,34 MB, JS 1,47 MB anche sul login, max-age 3600)"
type: story
status: open
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, performance, css, js, leaflet, cache, vite, asset]
qmd: "peso asset tema app css 3,34 MB 270 KB gzip js 1,47 MB leaflet filament auth login 7 css bloccanti max-age 3600 ETag compressione UX-03 UX-10"
related:
  - ./front-office-production-readiness-audit.story.md
  - ./dcp-449-salto-di-layout-del-wizard-tickets-create.story.md
  - ./dcp-452-residui-di-ux-accessibilita-e-messa-in-produzione-del-tema.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/102"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94"]
---

# DCP-450: peso e cache degli asset del tema

Status: **aperta** (2026-11-12). Fase BMAD: Analysis (audit UX-03 e UX-10); Solution da progettare. Nessuna modifica fatta.

## Cosa si misura (audit, 2026-11-11)

| Voce | Misura |
|---|---|
| CSS | `app-*.css` 3,34 MB (**270 KB** gzip); CSS totale 3,4 MB; **7 CSS bloccanti** |
| JS | 1,47 MB, con la mappa Leaflet e gli asset Filament caricati **anche su `/auth/login`** |
| Cache (dev) | `max-age=3600`, senza `ETag` né compressione |

**Come leggere i numeri.** Sono del dev con Debugbar e carico macchina 30-40: i **tempi** non valgono (F-05), i **byte** sì. La cache e la compressione del dev dicono poco di ciò che farà il server di produzione (UX-10 è «aperto, server»): va misurato sul server vero.

## Perché non l'ho corretto

Ridurre il bundle vuol dire scegliere cosa togliere (purge del CSS, bundle per rotta, `theme-map` solo dove c'è una mappa) e un tema che oggi si ricostruisce con un build delicato (vedi DCP-447: il build era rotto da import mancanti). Un purge sbagliato rompe pagine in silenzio, con classi che spariscono solo in alcune pagine.
Serve prima una rete di sicurezza (confronto visivo o spec delle pagine chiave), poi il taglio.

## Proposta (non decisione)

1. Misurare a macchina libera e su un build di produzione: byte per rotta (home, login, wizard, blog), non solo il totale.
2. Separare i bundle: la mappa Leaflet solo nelle pagine con mappa; gli asset Filament fuori dal login pubblico.
3. Purge del CSS con verifica su un elenco di pagine; confrontare gli screenshot prima e dopo.
4. Cache: `max-age` lungo e `immutable` per i file con hash nel nome (il manifest Vite li ha già), compressione lato server; verifica con `curl -I` sul server di destinazione (go-live gate, DCP-154).

Obiettivi numerici: **solo una proposta di lavoro** (nessun valore è stato deciso dal PO); da fissare quando si ha la misura di produzione.

## Criteri di accettazione (quando si lavora)

- [ ] Misura di partenza per rotta su build di produzione e macchina libera, scritta qui.
- [ ] Obiettivo concordato (CSS, JS, richieste bloccanti) nel registro DCP-200.
- [ ] `/auth/login` senza Leaflet né asset Filament.
- [ ] Header di cache e compressione verificati sul server di destinazione.
- [ ] Nessuna regressione visiva sulle pagine chiave (screenshot prima/dopo) e `npm run build` verde.

## Verifica

Nessuna correzione in questa story; le cifre sono quelle dell'audit.
