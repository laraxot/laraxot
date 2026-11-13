---
title: "Sixteen theme documentation index"
type: index
theme: Sixteen
created: 2026-09-27
updated: 2026-10-14
qmd: "sixteen theme design comuni folio volt accessibility documentation index classificazione cartelle indici storici INDEX.md index.md"
status: active
tags: [docs, sixteen]
---

# Sixteen — indice canonico

Sixteen è il tema web/PWA pubblico di FixCity: è il proprietario della resa
Design Comuni, del markup e dei componenti visuali. Le pagine sono Folio +
Blade/Volt e le Actions di dominio appartengono al modulo owner. Per regola non
deve avere controller o rotte proprie, ma il codice le contiene ancora: 5
controller in `app/Http/Controllers/`, `routes/web.php` e `routes/auth.php`
(verificato 2026-10-14). A runtime non risultano registrate: `php artisan
route:list` non ha `comune/*`, `auth/spid` ne' `auth/cie`. Stato e conteggi in
[README.md](README.md).

Questo file è l'indice operativo unico. Stato reale degli altri indici (verificato
2026-10-14): `00-index.old.md`, `documentation-index.old.md` e
`index.historical-bmad.old.md` sono storici (suffisso `.old`); `INDEX.md` (la
mappa delle sottocartelle, DCP-112) è stato rimosso da git (commit
`52268d5b`): per le cartelle usa la tabella "Classificazione delle cartelle" qui
sotto. `index.md` è l'indice BMAD/QMD più vecchio (router storico) e
`master-documentation.md` è un resoconto del 2026-04-01, entrambi senza suffisso
`.old`; restano anche `00-INDEX.md.tmp` (residuo del 2026-09-24),
`00-homepage-replication-index.md`, `design-comuni-replication-index.md`,
`replikate-master-index.md` e `SCRIPTS_INDEX.md`. Non usarli per decidere una
nuova struttura.

## Riorganizzazione docs 2026-10-13

- `build/` — tutti i 14 documenti Vite/build (erano sparsi alla root come
  `vite-*.md`/`VITE_*.md`); `build/INDEX.md` non esiste: ordine di lettura da
  [vite-configuration-guide.md](build/vite-configuration-guide.md);
- `visual-parity/` — struttura consolidata: `reports/`, `screenshots/pages/`
  (evidenza per pagina), `screenshots/legacy-2026-04/` (evidenza storica),
  `scripts/` (bug del path annidato `scripts/docs/visual-parity/scripts/`
  eliminato);
- `visual-parity-screenshots/` accorpato in `visual-parity/screenshots/pages/`;
- `visual-comparison/` resta evidence storica cross-referenziata.

## Classificazione delle cartelle (file presenti il 2026-10-14)

| Ruolo | Cartelle (n. file) |
|---|---|
| Canoniche | `bmad/` (410, story in `bmad/stories/`, decisioni in `bmad/decisions/`), `stories/` (37), `decision-log/` (14), `wiki/` (278), `architecture/` (19), `rules/` (3), `i18n/` (3), `compliance/` (1), `build/` (15), `design-system/` (6), `components/` (8), `pages/` (334, contratti per pagina), `design-comuni/` (643, vincoli visuali; i suoi `*/raw` sono evidence) |
| Supporto | `prompts/` (338, coordinamento tra agenti, es. `prompts/homepage/`), `scripts/` (16), `llm-wiki/` (9) |
| Evidence (immutabili o storiche) | `screenshots/` (260), `visual-parity/` (280), `visual-comparison/` (224), `body-structure-comparison/` (156), `analysis/` (142), `comparisons/` (83), `visual-analysis/` (53), `html-parity-reports/` (29), `html-structure-comparison/` (21), `html-compare/` (12), `Main_files/` (47), `raw/` (16), `design-comuni/*/raw` |
| Generate | `graphify-out/` e `qmd-index/`: non presenti in `docs/` al 2026-10-28 |
| Legacy o da triare (DOC-004, DOC-006) | i `.md` di radice (405, con 25 collisioni di maiuscole nel perimetro), `ui-fixes/` (14), i report di batch in `design-comuni/` (`batch-structure-audit*.md`: 108 link non risolti ciascuno, `batch-pages-analysis.md`: 98) |
| Mappa delle cartelle | questa tabella (il vecchio `INDEX.md` è stato rimosso); questo file è anche l'indice dei documenti |

## Percorsi di lettura

1. [README](README.md) — identità, ownership e stack;
2. [knowledge-map](knowledge-map.md) — mappa per QMD/Graphify/Second Brain;
3. [nomi e frontmatter dei .md](../../../../bashscripts/ai/wiki/rules/markdown-file-naming-and-frontmatter.md) — regole cross-project;
4. [docs knowledge architecture](bmad/docs-knowledge-architecture.md) — regole BMAD;
5. [header/footer contract](bmad/header-footer-sections-contract.md) — dati e renderer;
6. [header/footer parity](bmad/header-footer-parity-report.md) — gate Playwright;
7. [header/footer data model](bmad/header-footer-data-model.md) — elementi, forma JSON e AC;
8. [header/footer rationale](second-brain/header-footer-rationale.md) — decisione e perché;
9. [front-office readiness plan](bmad/front-office-production-readiness-plan.md) — copertura guest/auth e gate;
10. [front-office readiness brainstorming](bmad/front-office-readiness-brainstorming.md) — otto tecniche BMAD;
11. [Cms Head adoption plan](../../../Modules/Cms/docs/bmad/head-component-adoption-plan.md) — ownership, decisioni e parita';
12. [wiki](wiki/README.md) — conoscenza consolidata e regole on-demand;
13. [design-comuni](design-comuni/design-comuni-canonical.md) — vincoli visuali;
14. [design-system](design-system/README.md) — token e componenti del tema;
15. [pages](pages/) — contratti delle pagine e parity;
16. [stories](stories/) — storie BMAD e acceptance criteria;
17. [visual-parity](visual-parity/) — verifiche, screenshot per pagina e report;
18. [investigation](investigation/) — analisi riproducibili, non decisioni canoniche.

Per le pagine CMS di primo livello, leggere anche il contratto
[FAQ route ownership](bmad/faq-route-ownership.md): una pagina come
`domande-frequenti` usa lo shell Folio generico e il JSON del tenant; non si
crea `resources/views/pages/domande-frequenti/index.blade.php`.
Il perché: [why-domande-frequenti-blade-php-must-not-exist.md](bmad/knowledge/why-domande-frequenti-blade-php-must-not-exist.md).

## SSoT per area

| Area | Canonico | Non usare come SSoT |
|---|---|---|
| Layout/Folio | `layout-runtime-contract.md` + `docs/wiki/rules/no-parallel-design-comuni-layout.md` | `design-comuni` come nome di layout runtime; i valori CMS storici vengono risolti da `x-layouts.app`, mentre `-authenticated` e `-bare` restano template specializzati di test |
| Accessibilità | `accessibility.md` + contratto mobile | claim “100%” senza audit |
| Design Comuni | `design-comuni/` + documenti parity + `design-system/` | screenshot raw o report duplicati |
| Componenti | `components/` | copie in `blocks/` non indicizzate |
| Build asset | `build/vite-configuration-guide.md` + `vite.config.js` | log di singole sessioni |
| FixCity journeys | `Modules/Fixcity/docs/actor-flows.md` | duplicati nel tema |

## Regola di consolidamento

Le cartelle `analysis/`, `comparisons/`, `html-*`, `body-structure-comparison/` e
`design-comuni/*/raw` sono evidence storica o generated output. Non creare nuovi report
paralleli: aggiornare il canonico con finding, data, viewport e link al codice. Prima di
eliminare o spostare file, verificare i riferimenti con `rg` e registrare la sostituzione
nel log Second Brain.

I nuovi `.md` usano nomi lowercase senza date/timestamp e senza suffissi numerici
sequenziali (`-1`, `-2`, `-3`). I file storici restano tali finché una mappa di rinomina
verificata non garantisce tutti i riferimenti.

## Definition of done visuale

Ogni modifica deve avere: mobile/tablet/desktop evidence, zero overflow, focus visibile,
target touch adeguato, contrasto e reduced-motion verificati. Un screenshot isolato non
chiude il gate.

## Studio docs e ingressi (2026-10-14)

- [Studio del tema reale e correzione degli ingressi](bmad/stories/docs-studio-stato-e-ingressi.story.md): stato vivo provato, conteggi, rotte dormienti, layout `design-comuni*`, indici storici.

## Head Cms e production readiness

- [Audit guest e autenticati](stories/front-office-production-readiness-audit.story.md)
- [Brainstorming BMAD a otto tecniche](bmad/front-office-readiness-brainstorming.md)
- [Piano di readiness](bmad/front-office-production-readiness-plan.md)
- [Decisioni Head e parita'](../../../Modules/Cms/docs/bmad/head-component-adoption-plan.md)
- [Discussion #93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93)

## Story BMAD recenti (standard di codice)

- [Parity degli attributi data-* (2026-10-29)](stories/data-attribute-parity.story.md) — via `data-sixteen-*` dal markup; regola in [data-attribute-rule.md](data-attribute-rule.md) (scala di scelta, inventario del riferimento: 12 nomi, nessuno col nome del tema); 6 varianti legacy di header, una sola con un consumatore.
- [Passata UX mobile (2026-10-28)](stories/mobile-ux-pass.story.md) — **in revisione**: il tema servito e' Two, non Sixteen; `loading="lazy"` annullato; viewport senza blocco zoom, campi a 16 px, mappa a schermo intero in `dvh` da confermare contro il riferimento.
- [Services -> Actions e const -> enum (2026-10-08)](stories/services-to-actions-sixteen.story.md) — il tema non ha piu' `app/Services/`; 31 const in enum con lang it/en.
- [Niente testi fissi nelle Blade (2026-10-30)](stories/blade-i18n-no-hardcoded-text.story.md) — chiavi `pub_theme::` in it, en, de, es; numeri, residui e regola dei link.

## Parity Design Comuni v2 (2026-10-13)
- [Epic DCP-00 e storie per la ripresa](bmad/stories/dcp-00-epic-design-comuni-parity-v2.story.md)
- [Perche' la parity non e' stata completata e guardrail](bmad/decisions/parity-root-cause-and-guardrails.md)
- [Dossier dei 14 difetti della homepage](bmad/homepage-visual-parity-defects/defects-d01-d14.md)
- [Coordinamento tra agenti](prompts/homepage/00-COORDINATION.md)
- [Zen del wizard segnalazione (logica/perche'/filosofia/religione/politica)](bmad/decisions/segnalazione-flow-parity-zen.md)
- [DCP-32 wizard step 1 privacy](bmad/stories/dcp-32-wizard-step1-privacy.story.md) · [DCP-33 step 2 dati + mappa](bmad/stories/dcp-33-wizard-step2-dati.story.md) · [DCP-34 step 3 riepilogo + mappa](bmad/stories/dcp-34-wizard-step3-riepilogo.story.md) · [DCP-35 conferma](bmad/stories/dcp-35-conferma-parity.story.md)
- Strumenti: `bashscripts/docs/visual-comparison/INDEX.md`

## Wizard /it/tickets/create: DCP-35 (Fixcity) e D15
- [DCP-35 (modulo Fixcity): passi 1 e 2 del wizard e coordinamento tra agenti](../../../Modules/Fixcity/docs/stories/dcp-35-tickets-create-privacy-dati-coordinamento.story.md): attenzione, la numerazione DCP-32..35 esiste anche nelle story del tema (voci sopra), con significato diverso (qui DCP-35 = conferma)
- [D15: il Volt della pagina generica ha una radice propria](decision-log/D15-volt-fragment-explicit-root.md): causa di `MethodNotFoundException` su «Avanti»; esiste un secondo D15, [conferma post-save](decision-log/D15-conferma-post-save.md)

## Story tickets/create lato tema (2026-10-13)
- [DCP-40 radice del Volt nella pagina generica](bmad/stories/dcp-40-volt-radice-widget-livewire.story.md)
- [DCP-50 guardia di compilazione dei template Blade](bmad/stories/dcp-50-guardia-compilazione-template-blade.story.md)
- [D15 radice del Volt (decisione)](decision-log/D15-volt-fragment-explicit-root.md)

## Multilingua it, en, de, es (DCP-120)
- [i18n/INDEX.md](i18n/INDEX.md): comportamento multilingua (lingua, fallback, selettore, link, formati) e glossario di traduzione
- [DCP-120 copertura de/es](bmad/stories/dcp-120-multilingua-comportamenti-e-copertura.story.md) · [DCP-123 lingua dichiarata e link localizzati](bmad/stories/dcp-123-lingua-dichiarata-e-link-localizzati.story.md)
- [Multilingua e dispositivi: comportamenti osservati](wiki/concepts/multilingua-e-dispositivi-comportamenti.md): misure su lingua, selettore, wizard, formati, menu e bersagli tattili a 375, 768, 1024, 1440 (studio in [DCP-55](bmad/stories/dcp-55-multilingua-e-dispositivi.story.md))

## Area personale (DCP-106, DCP-110)
- [DCP-110 area personale: Le mie segnalazioni e Segnalazioni che seguo](bmad/stories/dcp-110-area-personale-pratiche-e-seguite.story.md)

## Registro story e dettaglio ticket (2026-10-14)
- [Registro completo delle story](bmad/stories/00-registro-completo.md): le story di `stories/` e `bmad/stories/` che prima non avevano un indice (stato e titolo dichiarati nei file)
- [DCP-148 dettaglio pubblico e Segui](bmad/stories/dcp-148-dettaglio-pubblico-segnalazione-e-segui.story.md): blocco `components/blocks/ticket-detail/design-comuni.blade.php`
- [DCP-152 (modulo Geo): marker del dettaglio e pulsanti della mappa](../../../Modules/Geo/docs/stories/dcp-152-marker-dettaglio-ticket-e-pulsanti-mappa-su-pagine-dc.story.md): il blocco passa `marker-icon-url`, `marker-color`, `marker-label` al `coordinate-picker-lit` (vedi [coordinate-picker](wiki/coordinate-picker.md))

## Audit front office anonimo (2026-11-12)

- [Sprite delle icone: deve arrivare in produzione (F-01/F-02)](stories/icon-sprite-deployability.story.md): `.gitignore` a due livelli, `test -f` in `deploy.sh`, guardia che interroga git
- [Home «Novita'»: 3 card per riga (F-06)](stories/home-news-grid-specificity.story.md): specificita' dello scope contro il `<style>` dei blocchi
- [Sweep del front office anonimo e diff dell'head](how-to/front-office-anonymous-sweep.md): metodo, soglie, trappole
- [Piano di hardening: guardie e strumenti](plans/front-office-hardening-plan.md): onde per non ripetere i difetti muti
- [Brainstorming, sessione 85](bmad-brainstorming.md): 5 Perche', reverse brainstorming, matrice delle guardie
- Discussione con decisioni e alternative scartate: [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95)

## Decisione wizard privacy — 2026-11-12

- [DCP-456-Sixteen: informativa a due livelli](bmad/stories/ticket-create-privacy-design-comuni-decision.story.md)
- [Discussion #96](https://github.com/laraxot/theme_sixteen_fila5/discussions/96): differenze tra informativa integrale duplicata e avviso breve con approfondimento.

--- BMAD + second brain note (agent AI update 2026-10-14) ---
- [second brain: kp-174d15a58ff94901b2b0df0d86b8907a] Design Comuni parity
- [BMAD] Design Comuni scope, no Bootstrap Italia
- [AGENTS.md m00088] Prompt 98-daily-report: ruoli chiari AI
