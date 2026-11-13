---
id: dcp-453
title: "DCP-453: ritiro del codice morto del tema (ComuneControllerTest, pages_old, header-comune, FrontofficeSearchSuggestions): decisione"
type: story
status: needs-decision
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, codice-morto, test-rossi, pages_old, volt, decisione]
qmd: "codice morto sixteen ComuneControllerTest 18 rossi pages_old pages_old1 header-comune FrontofficeSearchSuggestions ritiro suffisso old DEC-70"
related:
  - ./front-office-production-readiness-audit.story.md
  - ./dcp-447-tema-ricostruibile-e-guardie-contro-i-mirror.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/105"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94", "https://github.com/laraxot/theme_sixteen_fila5/discussions/108"]
---

# DCP-453: ritiro del codice morto del tema

Status: **da decidere** (2026-11-12). Fase BMAD: Analysis fatta (audit L-06, L-15); nessuna cancellazione eseguita. Registro: DEC-70 in DCP-200.

## Perché esiste

Il triage dei test rossi del tema (audit L-15) ha lasciato un gruppo di file che **nessun codice vivo usa** e che esistono ancora solo perché li citano dei test. Cancellare
codice e test richiede approvazione (regola del progetto: non si eliminano test senza approvazione), quindi non l'ho fatto: l'ho solo reso innocuo e l'ho elencato qui.

| File o gruppo | Stato misurato | Perché è «morto» |
|---|---|---|
| `Themes/Sixteen/tests/Unit/ComuneControllerTest.php` | **18 rossi** (`RouteNotFoundException`) su 18 della suite Unit del tema: 18 failed, 4 skipped, 432 passed al 2026-11-11 | cluster di test su rotte che non esistono più. Prima del triage il file usava `RefreshDatabase` e faceva un **fatale** alla costruzione della suite (L-06): tolti trait e `migrate`, il file ora si carica |
| `Themes/Sixteen/resources/views/pages_old/` e `pages_old1/` | esistono (riverificato 2026-11-12) | cartelle di pagine vecchie; i loro frammenti Volt sono stati rinominati `pages-old.view` e `pages-old1.view` (DCP-447) per non sovrascrivere la pagina viva. Nessun file cancellato |
| `Themes/Sixteen/resources/views/components/header-comune.blade.php` | esiste (riverificato) | nessun consumer vivo; referenziato solo da test |
| `Themes/Sixteen/app/Presentation/Search/FrontofficeSearchSuggestions.php` | esiste (riverificato) | nessun consumer vivo; il modal di ricerca vivo (`search-modal/design-comuni`) è guidato dal CMS (`$d('suggestions')`) |

I conteggi dei test sono dell'11 novembre: non ho rilanciato la suite di Sixteen oggi.

## Alternative (DEC-70)

| Opzione | Effetto | Rischio |
|---|---|---|
| (a) **Ritirare con il suffisso `.old`** (convenzione già usata, `pages-old.*` di `0b1a4486`) | i file escono dall'autoload e dai test, restano nella storia e leggibili | basso; richiede di aggiornare i pochi test che li citano |
| (b) Cancellare | repository più pulito | non si recupera senza la storia; serve approvazione esplicita per i test |
| (c) Tenere | nessun lavoro | 18 rossi permanenti nella suite: nascondono i rossi veri |

**Proposta: (a).** Metodo: prima si guarda, per ogni file, chi lo richiama oggi (`grep` su `Modules`, `Themes`, `routes`, test), poi si ritira un gruppo alla volta con la suite
Sixteen prima e dopo.

## Cosa NON faccio

Non cancello codice né test; non cambio le rotte per far passare `ComuneControllerTest` (resusciterebbe codice morto per far passare un test vecchio: stesso criterio di
`bmad/decisions/contract-tests-follow-live-files.md`).

## Criteri di accettazione (quando si decide)

- [ ] DEC-70 decisa nel registro DCP-200.
- [ ] Suite `Themes/Sixteen/tests/Unit` senza i 18 rossi del cluster morto, e nessun altro rosso nuovo.
- [ ] I file ritirati sono elencati qui con il commit che li ha ritirati.

## Verifica

Nessuna correzione in questa story; esistenza dei file riverificata il 2026-11-12.
