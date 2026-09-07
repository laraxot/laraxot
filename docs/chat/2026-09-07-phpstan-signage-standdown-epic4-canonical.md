---
title: "PHPStan zero — Signage: standdown, modulo gia' a 0 errori e fortemente conteso da swarm Epic4 canonical (story 4.14.9)"
type: coordination
tags: [phpstan, signage, standdown, epic4, canonical-structure, story-4.14.9]
created: 2026-09-07T19:12:00+02:00
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/Signage
blocking: false
---

# Modules/Signage — PHPStan zero campaign, standdown (nessuna modifica)

## Ricognizione (Fase 0)

`cd laravel/Modules/Signage && git status` mostra HEAD allineato a `laraxot/dev`
(nessun commit in arrivo/da integrare), ma working tree pesantemente dirty:

- 12 file modificati non miei: 5 Filament Resources
  (`SignCatalogItemResource.php`, `SignEstimationRuleResource.php`,
  `SignPriceListItemResource.php`, `SignPriceListResource.php`,
  `SignWorkCenterResource.php`) + 2 relative Pages (`ViewSignCatalogItem.php`,
  `ViewSignPriceListItem.php`) + `ExtractSignComponentsFromArtworkAction.php`,
  `SignArtworkQuotationPage.php`, `SignageCosting.php`, `SignageDemoSeeder.php`,
  `docs/coverage.md`, `docs/stories/3.2.phpstan-l10-signage-undefined-methods-fix.story.md`.
- 10 cartelle/file nuovi untracked: `Schemas/` + `Tables/` per tutti e 5 i
  Resources sopra, `docs/stories/4.14.9.signage-canonical.story.md`,
  `docs/prompts/`, `phpmd.ruleset.xml`.
- `composer.json.lock` risulta cancellato (probabilmente artefatto locale, non
  toccato).

`bashscripts/lock/check.sh` su 4 file core (`SignCatalogItemResource.php`,
`ExtractSignComponentsFromArtworkAction.php`, `SignArtworkQuotationPage.php`,
`SignageCosting.php`) → tutti **FREE** (questo swarm non usa il lock system),
ma gli mtime dei file dirty sono scaglionati con progressione continua da
12:11 a 18:42 di oggi, l'ultimo a **~30 minuti** dal momento della verifica
(19:12) — lavoro genuinamente vivo, non residuo stale.

La nuova story untracked `docs/stories/4.14.9.signage-canonical.story.md`
(status: `active`, creata oggi) conferma: e' l'estrazione canonica
Schemas/Tables/Infolist per i 5 Resources sopra, stesso pattern di
`Billing/docs/stories/4.1.filament-resource-gap-billing.story.md`, riferita a
`docs/stories/5.3.monorepo-filament-canonical-structure-audit-2026-09-07.md` e
issue `laraxot/bashscripts_fila5#10` — esattamente lo swarm Epic4
"filament-canonical-structure" gia' noto in second-brain
(`project_epic4_filament_canonical_structure_swarm_active.md`), che a quanto
pare ha ora raggiunto anche Signage (non era nella lista dei 14 moduli
originari).

## PHPStan reale (Fase 0, comando richiesto dal task)

```
cd laravel && ./vendor/bin/phpstan clear-result-cache
./vendor/bin/phpstan analyse Modules/Signage --no-progress --memory-limit=-1
```

Risultato, con result-cache pulita, **incluso** tutto il working tree dirty
sopra (le cartelle `Schemas/`/`Tables/` nuove sono file reali su disco, non
solo entry di git, quindi PHPStan le analizza comunque a prescindere dal
tracking git):

```
[OK] No errors
```

**Zero errori reali**, anche a modulo pesantemente in WIP. `php -l` su tutti i
file dirty conferma inoltre nessun errore di sintassi (nessun problema con
l'ipotetico gotcha "commento con apostrofo" citato nel brief — non presente
nello stato attuale di `SignCatalogItemResource.php`, probabilmente gia'
risolto da un passaggio precedente).

Questo riconferma quanto gia' documentato dalle due claim precedenti
dello stesso giorno:
- `docs/chat/2026-09-07-phpstan-signage-claim.md` (commit `d3fdc43`, 26→10→0
  errori sul metodo/argomento-tipo, poi risolto anche il residuo
  smalot/pdfparser da altra sessione).
- `docs/chat/signage-typecoverage-and-regression-fix.md` (commit `28d7bd4`,
  `typeCoverage.constantTypeCoverage` fix, isolato `Modules/Signage`: 0 errori
  prima e dopo).

## Decisione: standdown

Per il vincolo esplicito del task ("se il modulo... e' fortemente conteso,
fermati e riporta come stand-down invece di duplicare" e "se... gia' chiuso da
poco (0 errori reali gia' verificati da un commit recentissimo), fermati e
riporta"): **entrambe le condizioni sono vere contemporaneamente**. Il modulo
e' gia' a 0 errori PHPStan reali (verificato ora stesso con cache pulita) e
al contempo e' sotto riscrittura strutturale attiva (story 4.14.9, ultimo
tocco ~30 minuti fa) sugli stessi identici file che andrebbero toccati per
qualunque fix. Non esiste alcun errore residuo su cui lavorare, ne' un
sottoinsieme "non sovrapposto" — non c'e' nulla da fixare.

**Nessun file toccato in questa sessione. Nessun commit, nessun push.** Non
creata nuova story (la 4.14.9 gia' copre lo scope strutturale in corso; la 3.2
gia' copre lo storico PHPStan). Quality gate completo (PHPMD/PHPInsights/Pest)
non eseguito: non essendoci un fix da validare non c'e' un Definition of Done
da chiudere; eseguirli ora fotograferebbe solo lo stato a meta' del refactor
altrui.

## Heads-up per chi possiede la story 4.14.9 (non verificato/risolto da questa sessione)

Durante la ricognizione ho notato che il working tree attuale **rimuove
contenuto storico gia' committato** in due file non-codice:

- `docs/coverage.md`: la sezione "typeCoverage.constantTypeCoverage fix + @var
  regression correction" (commit `28d7bd4`, ~90 righe di dettaglio
  before/after/concorrenza) risulta cancellata nel diff attuale, sostituita da
  un header piu' corto.
- `docs/stories/3.2.phpstan-l10-signage-undefined-methods-fix.story.md`: la
  voce Dev Agent Record corrispondente allo stesso follow-up (~55 righe)
  risulta anch'essa cancellata nel diff attuale.

Non ho toccato questi file ne' verificato l'intento (potrebbe essere una
riorganizzazione voluta contestuale alla ristrutturazione canonica, es.
spostamento del contenuto altrove, o una troncatura non intenzionale di uno
script di estrazione). Lo segnalo qui per la regola "non cancellare la storia
BMAD, marcare superseded" (`bashscripts/ai/wiki/rules/00-ON_DEMAND_PATTERN.md`)
— chi chiude la story 4.14.9 dovrebbe verificare prima del commit che quel
contenuto sia stato spostato/superseded esplicitamente e non semplicemente
perso.

## Per chi riprende Signage dopo lo swarm

1. Aspettare che la story `4.14.9.signage-canonical` risulti chiusa (i 5
   Resources + relative Pages si sono stabilizzati).
2. Ri-eseguire `phpstan analyse Modules/Signage --no-progress --memory-limit=-1`
   da zero con cache pulita: al momento di questa verifica risultava gia' a 0
   errori, quindi ci si aspetta che resti tale a meno di regressioni introdotte
   dall'estrazione stessa.
3. Verificare il possibile ripristino/perdita di contenuto in `docs/coverage.md`
   e nella story 3.2 segnalato sopra prima di considerare la documentazione
   del modulo a posto.
