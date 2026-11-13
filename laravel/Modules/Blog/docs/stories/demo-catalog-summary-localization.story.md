---
title: "[STORY] Catalogo demo del Blog: la sintesi è una sola stringa in italiano"
type: story
status: ready-for-decision
module: Blog
priority: low
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, ecc, blog, demo, seeder, i18n, f-08]
qmd: "DemoArticleCatalog summary stringa unica it en de es card novita sintesi italiana seeder demo localizzazione"
related:
  - ./front-office-demo-content-population.story.md
  - ./front-office-demo-content-type-backfill.story.md
  - ./inbound-demo-content-seeder.story.md
  - ../../../Cms/docs/stories/untranslated-detail-pages-indexing.story.md
  - ../../../../Themes/Sixteen/docs/stories/front-office-production-readiness-audit.story.md
  - ../../../../Themes/Sixteen/docs/stories/home-news-grid-specificity.story.md
issues: []
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/95"]
---

# [STORY] Catalogo demo del Blog: la sintesi è una sola stringa in italiano

**Discussione (decisioni e alternative scartate):** [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95), complementare a [#93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93) e [#94](https://github.com/laraxot/theme_sixteen_fila5/discussions/94).

## Fase BMAD

**Analysis** chiusa con misura; **decisione aperta** (PO). Nessun codice scritto, **nessun seed rilanciato**. Origine: difetto `F-08` dell'audit anonimo
([story madre](../../../../Themes/Sixteen/docs/stories/front-office-production-readiness-audit.story.md)). È il lato **dati** di [F-07](../../../Cms/docs/stories/untranslated-detail-pages-indexing.story.md), che è il lato regola.

## Misura (2026-11-12)

In `Modules/Blog/database/seeders/Demo/DemoArticleCatalog.php` ogni voce ha:

| Campo | Forma | Lingue presenti |
|---|---|---|
| `title` | `array{it: string, en: string}` | it, en |
| `body` | `array{it: list<string>, en: list<string>}` | it, en |
| `summary` | **`string`** (una sola) | solo italiano |

Il commento della classe dice «testi civici credibili in it/en».

Effetto misurato sul dev: il testo della sintesi italiana («Da lunedì partono i lavori…») compare nell'HTML di `/it/blog`, **`/en/blog`**, **`/de/blog`**, `/it` e **`/en`**.
Quindi chi legge in inglese vede titolo inglese e sintesi italiana nella stessa card; in tedesco e spagnolo l'intera voce è italiana (titolo e corpo ricadono sull'italiano: vedi F-07).

## Perché succede

La sintesi è stata scritta come stringa semplice mentre titolo e corpo erano già mappe per lingua: il campo ha una forma diversa dai suoi vicini e nessun controllo
confronta le lingue presenti nei tre campi. Difetto muto, solo dati demo.

## Opzioni

| Opzione | Cosa cambia | Pro | Contro |
|---|---|---|---|
| **A. `summary` come mappa `it`/`en`** (stessa forma di `title`) | catalogo + chi legge il campo | Allinea i tre campi, sparisce l'italiano nelle card inglesi, cambio piccolo | de/es restano in italiano |
| **B. Aggiungere de/es a titolo, sintesi e corpo** | solo dati | Demo coerente in 4 lingue | Testi da tradurre e rivedere; i contenuti reali sono dell'ente, non dei demo |
| **C. Togliere `summary` e derivarla dal primo paragrafo del corpo** | meno dati e meno codice | Niente più divergenza fra campi | Da verificare se il modello usa una colonna sintesi propria; sintesi e inizio del corpo non sono la stessa cosa |
| **D. Lasciare com'è** (solo demo) e affidarsi alla regola di F-07 | niente | Zero lavoro | In inglese resta una card mista |

**Proposta:** **A** subito (corregge ciò che il cittadino inglese vede); **B** solo se il PO vuole il demo in quattro lingue; **D** è accettabile se i demo non andranno in produzione.
Prima di scegliere **C** va letto il modello dell'articolo per vedere come usa la sintesi.

## Attenzione ai dati

Il catalogo vale solo per **i prossimi seed**. Le righe già nel DB di sviluppo non cambiano da sole: aggiornarle è un'azione su dati.
**Non rilanciare il seed senza richiesta esplicita dell'utente** e prima controllare che non ci sia un `db:seed` in corso.

## Accettazione (quando si implementa)

- [ ] Esito del PO nel registro DCP-200 (`DEC-62` e seguenti) con data.
- [ ] Test da file nel modulo (`Modules/Blog/tests`): `title`, `summary` e `body` di ogni voce hanno le **stesse** lingue; rosso se se ne toglie una.
- [ ] Sonda: `/en/blog` e `/en` non contengono la sintesi italiana.
- [ ] Aggiornamento delle righe già presenti nel DB di sviluppo solo con richiesta esplicita.
