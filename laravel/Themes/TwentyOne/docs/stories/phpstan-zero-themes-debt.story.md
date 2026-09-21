# Story: PHPStan zero debt — tema TwentyOne (path mai coperto dal comando canonico)

Status: done
Tema: TwentyOne (Themes/ non e' in `phpstan.neon` paths, vedi `Themes/docs/phpstan-non-analizza-i-temi.md`)
Context: run manuale 2026-09-21 `phpstan analyse --memory-limit=-1 Themes/TwentyOne`: 54 errori su
3 file. Il giro 2026-08-31 aveva gia' segnalato `$this__('chiave')` (identificatore corrotto,
fatal) in ResolveFeaturedMarketsGridDataAction — verificare se e' ricomparso o mai stato corretto.
Dependencies: nessuna — Themes libero.
Accept Criteria: 0 errori phpstan sul path Themes/TwentyOne a livello max, nessun @phpstan-ignore /
baseline, nessun bug reale introdotto.
Regola: `phpstan.neon` e' fuori portata (owner-only).

## Log 2026-09-21

Identificatore corrotto `$this__('chiave')`: NON presente, gia' corretto prima di questo giro.
Nessuna regressione trovata.

Causa vera dei 54 errori: non era un gap di tipi ma una violazione di confine tema/modulo.
`ResolveCinematicHeroDataAction` e `ResolveFeaturedMarketsGridDataAction` chiamavano
`Modules\Predict\*` (Actions, model `Predict`) — classi che **non esistono in questo repo**
(`Modules/Predict` vive solo nel progetto gemello `base_predict_fila5`, vedi
`feedback_modules_agnostic_no_fixcity` nel second brain). Ogni esecuzione di queste due Action
in questo progetto avrebbe fatto fatal a runtime, non solo fallito PHPStan. Violava la regola
esplicita gia' scritta in `docs/NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md` / `NO_PREDICT_SPECIFIC_PAGES.md`.

Fix: rimosso l'accoppiamento a `Modules\Predict` da entrambe le Action, sostituito con default
locali/vuoti che rispettano lo stesso contratto di ritorno (statistiche a 0, `spotlightCards`/
`cards` vuoti) — un blocco/widget di modulo puo' rimpiazzare i dati reali passando parametri,
senza toccare il tema. Nessun caller live perde funzionalita' reale: prima falliva comunque.

File toccati: `app/Actions/Hero/ResolveCinematicHeroDataAction.php`,
`app/Actions/Markets/ResolveFeaturedMarketsGridDataAction.php`,
`tests/Unit/FolioPageMountContractTest.php` (uniformato a `Safe\file_get_contents`).

Verifica: `phpstan analyse --memory-limit=-1 --no-progress` sui 3 file → 0 errori.
`pest tests/Unit/FolioPageMountContractTest.php` → 2 passed. `pint` sui 3 file → nessuna modifica.

## Follow-up aperto (fuori scope di questa story)

Le due Action ora restituiscono sempre dati vuoti/zero — corretto per l'agnosticita' del tema,
ma se il modulo Predict *e'* installato in un progetto host, serve un meccanismo (blocco CMS,
widget, evento) che inietti i dati reali senza reintrodurre l'accoppiamento diretto. Non
implementato qui: nessun caller live lo richiedeva in questo repo.
