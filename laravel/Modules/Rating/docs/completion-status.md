---
title: "Rating: cosa manca per completare"
type: status
module: Rating
updated: 2026-10-28
repository: "https://github.com/provtv/base_ptv_fila5_mono"
github_issue: "https://github.com/provtv/base_ptv_fila5_mono/issues"
github_discussion: "https://github.com/provtv/base_ptv_fila5_mono/discussions"
related:
  - ../../Xot/docs/project-completion-status.md
---

# Rating: cosa manca per completare

Fotografia misurata il 2026-10-28 (sola lettura). Si rigenera con gli stessi controlli: story nel frontmatter,
PHPStan livello 10, blade troncate, traduzioni, test. Riepilogo di progetto: `Modules/Xot/docs/project-completion-status.md`.

## Indicatori

| Indicatore | Valore |
|---|---|
| File PHP in `app/` | 73 |
| Errori PHPStan livello 10 | 0 |
| Blade (troncate in testa) | 16 (0) |
| File di test (`*Test.php`) | 29 |
| TODO/FIXME nel codice | 0 |
| Traduzioni it / en (solo in it) | 23 / 0 (23) |
| Story/epic BMAD (aperte) | 149 (89) |

## Problemi noti e prossimi passi

| Priorita' | Problema | Dove si lavora |
|---|---|---|
| ERRORE | Relazioni ad albero senza `parent_id` in alcune tabelle ratings. | `stories/rating-parent-id-column.story.md` |

Problemi trasversali (valgono per tutto il progetto): vedi `project-completion-status.md` in Xot.

## Story aperte (per stato)

| Stato | Story |
|---|---|
| in-progress | [8.24: PHPStan Level 10 fleet fix parallel (10 modules)](docs/bmad/stories/8.24-phpstan-l10-fleet-fix-parallel-level-modules.story.md) |
| in-progress | [8.24: PHPStan Level 10 fleet fix parallel (10 modules)](docs/bmad/stories/8.24-phpstan-l10-fleet-fix-parallel.story.md) |
| in_progress | [Cluster 3: PHPStan L10 Rating + Xot modules](docs/bmad/stories/cluster-3-rating-xot-phpstan-l10.story.md) |
| in_progress | [Conflict marker resolution: PHPStan bootstrap unblock (story 8.23)](docs/bmad/stories/conflict-marker-resolution-phpstan-bootstrap.story.md) |
| in-progress | [Audit traduzioni .navigation: modulo Rating](docs/bmad/stories/navigation-translations-audit.story.md) |
| in-progress | [HasRatingsTrait: Apply user correction: replace resolveRatingClass with getClassName](docs/stories/01-41-hasratin-gs-trait-correction.story.md) |
| in-progress | [HasRatingsTrait: Apply user correction: replace resolveRatingClass with getClassName](docs/stories/01-41-hasratin​gs-trait-correction.story.md) |
| in-progress | [Verifica frontmatters: tutte le story BMAD hanno GitHub links corretti](docs/stories/01-42-verify-frontmatters.story.md) |
| in-progress | [Rating README agnostic + .zshrc improvements](docs/stories/01.30.rating-readme-agnostic-zshrc.story.md) |
| in-progress | [Rating 2.6: rimozione silenziatori PHPStan e ripristino fix static-call regredito](docs/stories/2.6.phpstan-silencer-removal-regression-fix.story.md) |
| in-progress | [RatingContract formalizzazione e uso nei consumatori](docs/stories/5.87.rating-contract-usage.story.md) |
| in-progress | [5.93: RatingContract al posto di BaseRating: un'astrazione sola o nessuna](docs/stories/5.93.rating-contract-al-posto-di-baserating.story.md) |
| in-progress | [Rating: resolve git conflict markers in the trait, column and trait tests](docs/stories/resolve-conflict-markers-rating.story.md) |
| review | [Rating: marker nidificati in README](docs/bmad/stories/readme-conflict-markers.story.md) |
| review | [Swarm cluster-4 PHPStan: Rating: orphan ignore + leaf DRY (story 8.22)](docs/bmad/stories/swarm-cluster4-phpstan-rating.story.md) |
| review | [Rating: contratti statici di HasRatingsTrait nei test](docs/stories/2.2.phpstan-rating-test-contracts.story.md) |
| review | [Rating: contratti statici della coda test](docs/stories/2.3.phpstan-rating-tail-contracts.story.md) |
| ready-for-dev | [RatingsHostStub: harness getClassName senza backtrace fragile](docs/bmad/stories/18.59-ratings-hoststub-getclassname-harness.story.md) |
| ready-for-dev | [D-8: HasRatingValuesFilter: note filled conta come valutata](docs/bmad/stories/5.141-has-rating-values-filter-note.story.md) |
| ready-for-dev | [Select del criterio deve essere required (codice commentato mai attivato)](docs/bmad/stories/5.152-select-required.story.md) |
| ready-for-dev | [Ratings table PDF: componente Blade riutilizzabile nel modulo Rating](docs/bmad/stories/5.226-ratings-table-pdf-component.story.md) |
| ready-for-dev | [Chiudere 5.240 closures + allineare indice 5.235](docs/bmad/stories/5.242-close-ratingcontract-closures-and-align-index.story.md) |
| ready-for-dev | [Residuo sprint/architecture post-RatingData (trait-centric)](docs/bmad/stories/5.243-sprint-architecture-post-ratingdata-residual.story.md) |
| ready-for-dev | [Pack continuazione domani: post prompt 04 / RatingData](docs/bmad/stories/5.244-continuazione-domani-post-prompt-04-pack.story.md) |
| ready-for-dev | [Verificare/chiudere harness ratings_by_id + getClassName (18.59)](docs/bmad/stories/5.245-ratings-by-id-hoststub-harness-verify.story.md) |
| ready-for-dev | [Rating: baseline BMAD e analisi completa](docs/bmad/stories/module-bmad-audit.story.md) |
| ready-for-dev | [Rating: adottare il contratto Pest condiviso (XotBasePest) e alzare la coverage](docs/stories/2.1.pest-contract-xotbasepest.story.md) |
| ready-for-dev | [5.92: getRatingsFormSchema() in HasRatingsTrait: il contratto sì, le deduzioni da stringa](docs/stories/5.92.get-ratings-form-schema-nel-trait.story.md) |
| ready-for-dev | [Design: opzione 'altro' con textarea obbligatoria nel Select dei rating figli](docs/stories/rating-altro-option-conditional-textarea-design.story.md) |
| ready-for-dev | [Colonna Ratings: introduzione di RatingsColumn in BaseSchedasTable](docs/stories/ratings-column-bmad-story.story.md) |
| open | [Rimuovere docs/graphify tracciato in Rating](docs/stories/rating-docs-graphify-tracked.story.md) |
| backlog | [Continuazione domani](docs/bmad/stories/continuazione-domani.story.md) |
| backlog | [Rating: bonifica .md con data/timestamp nel nome file](docs/bmad/stories/docs-filename-no-date-bonifica-rating.story.md) |
| backlog | [Create Scheda](docs/stories/1.1.create-scheda.story.md) |
| backlog | [Scheda Search + Filter (Employee, Year, Status)](docs/stories/1.10.search-filter.story.md) |
| backlog | [Draft → Submitted State Flow](docs/stories/1.3.draft-submitted-flow.story.md) |
| backlog | [Soft-delete + Restore Within 30 Days](docs/stories/1.4.soft-delete-restore.story.md) |
| backlog | [Bulk Create From CSV](docs/stories/1.5.bulk-create-csv.story.md) |
| backlog | [Edit Submitted Scheda (Audit Trail)](docs/stories/1.8.edit-submitted-audit-trail.story.md) |
| backlog | [Approval Workflow (Manager Sign-off)](docs/stories/1.9.approval-workflow-signoff.story.md) |
| backlog | [Adoption case study: RatingResource reordering](docs/stories/5.96-rating-resource-reordering-adoption.story.md) |
| backlog | [8.17: Il tetto di un criterio è un dato, non uno fra quattro valori di un enum](docs/stories/8.17.tetto-criterio-come-dato-non-enum.story.md) |
| backlog | [8.18: Due model_type per la stessa entità: un punteggio che non entra nel totale](docs/stories/8.18.due-model-type-punteggio-invisibile.story.md) |
| backlog | [Colonna parent_id per gli alberi di criteri](docs/stories/rating-parent-id-column.story.md) |
| draft | [cleanup rating 2026 09 22.story](docs/bmad/stories/cleanup-rating.story.md) |
| draft | [devin session bmad gaps root cause.story](docs/bmad/stories/devin-session-bmad-gaps-root-cause.story.md) |
| draft | [Story: Indice docs canonico Rating](docs/bmad/stories/docs-reorg-domain.story.md) |
| draft | [rating assert subclassof to ratingcontract.story](docs/bmad/stories/rating-assert-subclassof-to-ratingcontract.story.md) |
| draft | [rating contract assert subclass.story](docs/bmad/stories/rating-contract-assert-subclass.story.md) |
| draft | [rating query with extra attributes scope.story](docs/bmad/stories/rating-query-with-extra-attributes-scope.story.md) |
| draft | [rating statics to ratingdata consolidation.story](docs/bmad/stories/rating-statics-to-ratingdata-consolidation.story.md) |
| draft | [rating table component reusable.story](docs/bmad/stories/rating-table-component-reusable.story.md) |
| draft | [rating xls fields move to ratingdata.story](docs/bmad/stories/rating-xls-fields-move-to-ratingdata.story.md) |
| draft | [rating xls fields ratingclass required.story](docs/bmad/stories/rating-xls-fields-ratingclass-required.story.md) |
| draft | [ratingdata formfieldlabel contract.story](docs/bmad/stories/ratingdata-formfieldlabel-contract.story.md) |
| draft | [ratingdata ratingcontract typing.story](docs/bmad/stories/ratingdata-ratingcontract-typing.story.md) |
| superseded-pointer | [Bozza storica: refactor host->trait (pointer)](docs/bmad/stories/5.145-refactor-host-rating-to-trait.story.md) |
| blocking-lock | [5.146 select obbligatorio textarea req.story](docs/bmad/stories/5.146-select-obbligatorio-textarea-req.story.md) |
| pointer | [Puntatore: Pest gate obbligatorio (SSoT Xot/5.158)](docs/bmad/stories/5.158-pest-gate-mandatory-standing-order.story.md) |
| implemented-trait-only | [Select + Textarea (value/note); note required se altro](docs/bmad/stories/5.99-select-altro-note-obbligatoria.story.md) |

... e altre 29 (cercare `status:` diverso da done in `docs/**/*.story.md`).

27 story non hanno `status:` nel frontmatter: vanno classificate (done / backlog / superseded).
