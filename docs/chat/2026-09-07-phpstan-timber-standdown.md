---
title: "PHPStan zero — Timber: standdown, modulo fortemente conteso da swarm Epic4"
type: coordination
tags: [phpstan, timber, standdown, epic4, filament-canonical-structure]
created: 2026-09-07T17:11:51Z
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/Timber
blocking: false
---

# Modules/Timber — PHPStan zero campaign, standdown (nessuna modifica)

## Ricognizione (Fase 0)

`cd laravel/Modules/Timber && git status` mostra 20 file modificati non miei
(`TimberAgentResource.php`, `TimberCustomerPriceResource.php`,
`TimberCustomerResource.php`, `TimberDocumentResource.php`,
`TimberEInvoiceResource.php`, `TimberInvoiceResource.php`,
`TimberPaymentInstallmentResource.php`, `TimberPriceListResource.php`,
`TimberProductResource.php`, `TimberStockMovementResource.php`,
`TimberSupplierResource.php`, `TimberTransporterResource.php`,
`TimberWoodSpeciesResource.php` + le rispettive `Pages/View*.php`) con un diff
di **-705 righe / 21 file** (rimozione codice inline spostato altrove), piu' 24
directory nuove untracked `*Resource/Schemas/` e `*Resource/Tables/` per
praticamente tutte le Resource del modulo, e uno story file untracked vuoto
(`docs/stories/phpstan-fix-1788768735.story.md`, 0 byte, artefatto di una
sessione interrotta).

`git fetch laraxot dev` + `git log -5`: ultimo commit reale
`a83cbbc fix(timber): type constants for typeCoverage` alle 15:02:03. Tutto il
lavoro sopra descritto e' successivo a quel commit e non ancora committato.

`bashscripts/lock/check.sh` su tutti i file core coinvolti (`TimberAgentResource.php`,
`TimberCustomerResource.php`, `TimberDocumentResource.php`) risulta **FREE**
(nessun `.lock` vivo al momento della verifica), ma gli mtime dei file
modificati e delle nuove directory `Schemas/`/`Tables/` sono scaglionati con
progressione continua dalle ~17:10 fino a **~18:36**, cioe' fino a circa 33-40
minuti prima di questa verifica (~19:10) — pattern identico a quello gia'
documentato per WorkOrder lo stesso giorno
(`docs/chat/2026-09-07-phpstan-workorder-standdown.md`).

`docs/chat/2026-09-07-timber-filament-canonical-batch-claude3.md` conferma
inoltre che nella stessa giornata almeno **tre** agenti diversi
(`claude-timber-agent`, `claude-timber-agent-2`, e la sessione "batchC") hanno
tenuto lock su sottoinsiemi delle Resource di Timber per la stessa estrazione
canonica Schemas/Tables. La nota second-brain "Epic 4
filament-canonical-structure swarm active" elenca esplicitamente `Timber` tra
i 14 moduli in lavorazione dallo swarm opencode-driven su questa stessa
estrazione — collisione confermata, non ipotetica.

`grep -li timber docs/chat/*.md` ha inoltre trovato 13 file di coordinamento
storici su Timber (fix PHPStan/typecoverage precedenti, tutti gia' chiusi in
commit precedenti), a conferma che il modulo e' stato toccato ripetutamente da
sessioni diverse nelle ultime ore.

## PHPStan reale (Fase 0, comando richiesto dal task)

```
cd laravel && ./vendor/bin/phpstan analyse Modules/Timber --no-progress --memory-limit=-1
```

Risultato: **10 errori**, tutti concentrati nei file della stessa estrazione
Schemas/Tables in corso:

| File | Errori |
|---|---|
| `TimberCustomerResource.php` (dirty, non mio) | 3× `method.unused` (`registryFields()`, `commercialFields()`, `extraFields()` — helper statici orfani post-estrazione) |
| `TimberCustomerResource/Schemas/TimberCustomerForm.php` (nuovo, untracked) | 1× `return.type`: `getFormSchema()` ritorna `array<int, Tabs>` invece di `array<string, Component>` |
| `TimberCustomerResource/Schemas/TimberCustomerInfolist.php` (nuovo, untracked) | 1× `return.type`: `getInfolistSchema()` ritorna `array<int, Section>` invece di `array<string, Component>` |
| `TimberDocumentResource.php` (dirty, non mio) | 1× `method.unused` (`formatQuantity()`) |
| `TimberProductResource.php` (dirty, non mio) | 3× `method.unused` (`productFields()`, `featureFields()`, `warehouseFields()`) |
| `TimberProductResource/Schemas/TimberProductForm.php` (nuovo, untracked) | 1× `return.type`: `getFormSchema()` ritorna `array<int, Tabs>` invece di `array<string, Component>` |

Zero errori fuori da questi sei file, tutti parte della stessa estrazione a
meta'. Non esiste alcun sottoinsieme di errori "non sovrapposti" su cui
lavorare in sicurezza senza toccare file dirty/untracked di un'altra sessione.

## Decisione: standdown

Per il vincolo esplicito del task ("se il modulo ha ancora molti file dirty
non tuoi o un claim attivo di altri ... se e' fortemente conteso, fermati e
riporta come stand-down invece di duplicare"): il modulo e' fortemente
conteso (refactor esteso a praticamente tutte le Resource, tre agenti diversi
coinvolti nella stessa giornata, modifiche ancora "calde" ~33-40 minuti prima
della verifica) e il 100% degli errori PHPStan reali ricade esattamente nei
file di quella estrazione. I `method.unused` sui vecchi helper statici e i
`return.type` sulle nuove classi `Schemas/` sono con ogni probabilita'
artefatti transitori del refactor a meta' (i vecchi helper verranno rimossi o
ri-agganciati da chi sta completando l'estrazione; le firme dei nuovi metodi
verranno allineate al contratto `array<string, Component>` richiesto da
`XotBaseResourceForm`/`XotBaseResourceInfolist`), non un target stabile —
stesso bucket gia' generalizzato in
`bashscripts/ai/wiki/memories/phpstan-transient-errors-schema-table-extraction.md`
(nessuna nuova nota necessaria, pattern gia' documentato).

**Nessun file toccato in questa sessione. Nessun commit, nessun push.**
**Nessuna story BMAD creata**: nessuna modifica di codice e' stata fatta, e le
story esistenti (`01.Timber-phpstan-fix.story.md`,
`timber-constant-type-coverage-fix.story.md`, e gli altri ~10 file
`phpstan-fix-*.story.md` sotto `docs/stories/`, incluso quello vuoto
`phpstan-fix-1788768735.story.md` lasciato da una sessione interrotta) restano
come sono: non tocco quello story-id chaos in questa sessione, esula dal
compito assegnato.

## Per chi riprende Timber dopo lo swarm

1. Aspettare che l'estrazione canonica Schemas/Tables sia committata (nessun
   `.lock` vivo al momento, ma modifiche ancora molto recenti: attendere
   almeno che non ci siano nuove scritture per un intervallo ragionevole prima
   di considerarla conclusa, o verificare direttamente con chi la sta
   portando avanti via `docs/chat/INDEX.md`).
2. Ri-eseguire `phpstan analyse Modules/Timber --no-progress --memory-limit=-1`
   da zero: i 10 errori qui documentati potrebbero gia' essere risolti dallo
   swarm stesso (i `return.type` sono precisamente il tipo di errore che si
   risolve allineando il return type di `getFormSchema()`/`getInfolistSchema()`
   da `array<int, X>` a `array<string, Component>` con chiavi esplicite — fix
   meccanico una volta che l'estrazione e' stabile).
3. Consolidare/ripulire il file vuoto `docs/stories/phpstan-fix-1788768735.story.md`
   e la story-id chaos su Timber (14 file `*.story.md` legati a PHPStan)
   quando il modulo sara' libero: troppi ID sovrapposti per lo stesso scopo,
   rischio di duplicazione futura.
