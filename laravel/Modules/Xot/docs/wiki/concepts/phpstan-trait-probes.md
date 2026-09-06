---
title: "PHPStan trait probes — perché sono vietati"
type: concept
module: Xot
tags: [phpstan, trait, probe, xot, second-brain, anti-pattern, governance]
created: 2026-06-30
updated: 2026-07-27
qmd: "phpstan trait probe unused trait PhpstanProbeModel PhpstanTraitProbe app/Phpstan vietato anti-pattern"
related:
  - ./phpstan-fixes-log.md
  - ../memories/phpstan-remediation-swarm.md
  - ../../../../../../bashscripts/ai/wiki/rules/no-phpstan-probe-models.md
  - ../../../User/docs/wiki/concepts/trait-alias-conflict-resolution.md
---

# PHPStan trait probes — perché sono vietati

> **Regola operativa**: [`no-phpstan-probe-models`](../../../../../../bashscripts/ai/wiki/rules/no-phpstan-probe-models.md).
> Questo documento spiega il **perché**; la regola dice il **cosa**.

## Divieto

Non devono esistere, in nessun modulo o tema:

- classi il cui nome finisce per `PhpstanProbeModel` (es. `GeoPhpstanProbeModel`, `TenantPhpstanProbeModel`);
- classi il cui nome finisce per `PhpstanTraitProbe` / `PhpstanProbe` o varianti simili;
- directory `app/Phpstan/` dentro un modulo.

## Storia del problema (perché esisteva il pattern probe)

PHPStan segnala `trait.unused` quando un trait di libreria non ha alcun `use TraitName;`
visibile nello scope analizzato — tipico per trait usati solo nei test, tramite
composizione dinamica (`belongsToManyX`, Filament widget discovery, Sushi, ecc.) o
consumati da un modulo sibling non incluso nello stesso run di analisi.

Tra 2026-06 e 2026-07 la soluzione tentata è stata un **probe host + registry**:
una classe astratta finta (`XPhpstanProbeModel extends BaseModel`, tabella
inventata) più N classi concrete che facevano solo `use TraitName;`, elencate in un
registry centrale (`Xot/helpers/Helper.php` → `xotPhpstanTraitProbeClasses()`) incluso
in `phpstan.neon` via `scanFiles`. Il registry è stato **rimosso**: oggi `Helper.php`
non contiene più quella funzione. Il pattern è **superato e vietato**, non più
"la soluzione" — questo documento lo mantiene solo come memoria storica di cosa
NON rifare.

## Perché è vietato (le cinque letture)

### Logica

Un probe non testa e non dimostra nulla: è una classe fittizia il cui unico scopo è
convincere un linter che un `use` esiste. Non asserisce comportamento, non copre un
caso reale, non fallisce mai. È un placebo per l'analizzatore statico, non codice.

Peggio: la storia stessa del pattern (sezione "Anti-pattern" più sotto) dimostra che
**risolvere un falso positivo aggiungendo un consumer finto ha prodotto difetti veri**
— collisioni di scope reali quando il trait veniva agganciato anche a modelli di
produzione per "dargli un consumer legittimo". Il rimedio era più pericoloso del
sintomo.

Durante la bonifica 2026-07-27 di questo stesso documento sono emerse conferme dirette:

- `Modules\Job\Traits\FormatSeconds` era già usato da 3 widget Filament reali
  (`use FormatSeconds;` diretto, visibile a PHPStan) — il probe `FormatSecondsPhpstanProbe`
  era pura zavorra, creato prima del refactor dei widget e mai rimosso dopo.
- `Modules\Tenant\Models\Traits\SushiToCsv` e `SushiToPhpArray` avevano **già**
  `@phpstan-ignore trait.unused` sul trait stesso — i probe corrispondenti duplicavano
  un fix già applicato altrove, senza saperlo.
- In `Modules/Geo/tests/` esistevano **due cartelle gemelle a solo case diverso**
  (`Fixtures/Traits/` PascalCase e `fixtures/traits/` lowercase) con probe quasi
  identici: il sintomo tipico di scaffolding non governato che si duplica perché
  nessuno sa più dove sia "il posto giusto".

### Politica (governance modulare)

La struttura di un modulo Laraxot (`app/Models`, `app/Actions`, `app/Filament`, …) è
un contratto: ogni cartella ha un significato di dominio noto (vedi
`namespace-structure-rules.md`, `directory_structure.md`). `app/Phpstan/` non è
in quella mappa — è una cartella-modulo dentro il modulo che risponde a nessuna
convenzione, nessuna policy di traduzioni, nessuna migration, nessun test contract.

Il registry centralizzato aggravava il problema: **Xot — il modulo base da cui
tutti gli altri dipendono — arrivava a dipendere da classi analysis-only di Geo,
Lang, Notify, Job**. È l'esatto contrario della direzione di dipendenza che l'intero
framework impone (i moduli dipendono da Xot, mai il contrario). Un problema di
lint locale a un modulo era stato trasformato in un accoppiamento strutturale
verso l'alto.

In un progetto lavorato da più agenti in parallelo (vedi
`multi-agent-coordination-critical.md`), scaffolding ungoverned è esattamente ciò
che diverge senza che nessuno se ne accorga — vedi il case-duplicate di Geo sopra.

### Filosofia (DRY / KISS)

L'informazione "questo trait è intenzionalmente non usato qui" ha un solo posto
corretto dove vivere: il docblock del trait stesso, tramite
`@phpstan-ignore trait.unused`. Un probe la ripete altrove, in una terza sede, senza
aggiungere alcuna informazione nuova — è duplicazione pura, quindi una violazione
diretta di DRY.

In termini di KISS: la correzione giusta è **una riga** di annotazione sul trait.
Il pattern vietato era un intero sotto-sistema: classe probe + entry di registry +
wiring `scanFiles` in `Helper.php` + doc per modulo che ne spiegava l'esistenza.
Questo progetto vieta esplicitamente le astrazioni premature ("tre righe simili
sono meglio di un'astrazione prematura") — un registry per aggirare un warning
del linter è l'astrazione prematura per eccellenza.

### Religione (un solo punto di verità)

*Non avrai altro modello all'infuori di quelli che rappresentano un'entità di
dominio reale.* Un Model — o qualunque classe sotto `app/` — esiste per
rappresentare qualcosa che esiste davvero: una tabella, una risorsa, un concetto
di business. Un probe model non rappresenta nulla: è un golem eretto solo perché
un analizzatore statico lo conti.

Il rito di assoluzione corretto è la confessione inline: `@phpstan-ignore
trait.unused` scritto nel punto esatto del "peccato" (il trait stesso), non un
'idolo' costruito altrove per ingannare il confessore (PHPStan).

### Zen (serenità del codice)

Chi trova `Modules/Geo/app/Phpstan/TraitProbes.php` fra sei mesi deve fermarsi e
chiedersi: *cosa fa, perché esiste, è sicuro cancellarlo?* — dubbio e carico
cognitivo iniettati nel codice per zero beneficio comportamentale. Rimuovere il
probe e mettere l'annotazione inline sul trait elimina quel dubbio per sempre: la
spiegazione è già lì, esattamente dove si sta già guardando.

## Cosa fare invece

| Situazione | Azione |
|---|---|
| `trait.unused` su trait usato solo in test o via composizione dinamica | `/** @phpstan-ignore trait.unused */` sopra `trait X` (dopo l'eventuale docblock descrittivo, come riga separata) |
| Serve davvero esercitare il trait in un test | Classe anonima **dentro il test stesso**, oppure modello reale del modulo con `XotBaseTestCase` |
| Trait già su un modello di produzione | Nessuna azione — è già "usato", nessun ignore necessario |
| Prima di aggiungere un trait a un modello di produzione solo per "dargli un consumer" | **Non farlo** — rischio di collisione di scope reale (vedi anti-pattern sotto) |

```php
/**
 * Trait HasExample.
 */
/** @phpstan-ignore trait.unused */
trait HasExample
{
    // ...
}
```

## Anti-pattern storici (mai ripetere)

- `HasCommonScopes` agganciato a `XotBaseModel` per "dargli un consumer" → collisione
  reale con `scopePublished()` su `Blog\Article`. Fix: `@phpstan-ignore trait.unused`
  sul trait, nessun wiring su modelli di produzione.
- `TypedHasRecursiveRelationships` — trait rimosso (STORY-346); mai ricreare un probe.
- Probe Rating legacy (`HasRatingsTrait`, `RatingTrait`) → ~54 errori a cascata.
- Probe notifiche Notify (`HasTenantNotifications`, …) → contesto tenant mancante nel
  probe; risolto con `@phpstan-ignore trait.unused` diretto sui trait.
- **2026-07-27**: rimossi definitivamente `Geo/app/Phpstan/`, `Job/app/Phpstan/`,
  probe fixture in `Geo/tests/` (incluso il duplicato case-only `fixtures/`) e in
  `Tenant/tests/Fixtures/Traits/`. Aggiunto `@phpstan-ignore trait.unused` a
  `GeoTrait`, `HasAddress`, `HasAddresses`, `HasPlaceTrait` (Geo) — genuinamente non
  consumati in produzione. `FormatSeconds` (Job) e `SushiToJsons`/`SushiToCsv`/
  `SushiToPhpArray` (Tenant) non necessitavano di alcuna azione sul trait: erano già
  usati in produzione o già annotati — il probe era pura zavorra.

### Guard script

```bash
bash bashscripts/tools/archive-invalid-phpstan-probes.sh
```

Archivia in-place (`.bak`) probe invalidi noti. Non sostituisce l'audit manuale:
la lista al suo interno è storica, non esaustiva.

## Verifica

```bash
# Audit: non deve restituire nulla
grep -rl "PhpstanProbeModel\|PhpstanTraitProbe" laravel/Modules laravel/Themes --include="*.php"
find laravel/Modules laravel/Themes -type d -iname "Phpstan"

cd laravel
./vendor/bin/phpstan clear-result-cache
./vendor/bin/phpstan analyse Modules --no-progress
```

### Attributi Eloquent nei trait riusabili

Un trait non deve presumere che ogni host dichiari in PHPDoc le sue proprietà
magiche. Leggere l'attributo con `getAttribute()` e restringerne subito il tipo
mantiene il contratto nel trait e rende coerenti modello di produzione e fixture
di test. Aggiungere `@property` soltanto alla fixture nasconde invece il difetto
nel punto sbagliato.

```php
$publishedAt = $this->getAttribute('published_at');

return $publishedAt instanceof Carbon && $publishedAt->isPast();
```

### Fix correlati (2026-06-30)

| Area | Fix |
|------|-----|
| `xotSeedModelOnce` | `GetFactoryAction` istanziato direttamente (no `app()` mixed) + `createOne()` |
| `XotBaseTestCase` | Bug ricorsivi: `createUnitMock`, `assertDatabase*Row`, `skipTest` → delega PHPUnit |
| `RatingFactory` (Predict) | `$model = Predict\Models\Rating` (non Rating module base) |
| Test factory | `fix-test-factory-createone.php` — `create()` → `createOne()` dove N=1 |

## Collegamenti

- [Regola: no-phpstan-probe-models](../../../../../../bashscripts/ai/wiki/rules/no-phpstan-probe-models.md)
- [phpstan-fixes-log](./phpstan-fixes-log.md)
- [phpstan-remediation-swarm](../memories/phpstan-remediation-swarm.md)
- [User trait alias conflict](../../../User/docs/wiki/concepts/trait-alias-conflict-resolution.md)
- Policy per modulo: `Modules/Job/docs/no-phpstan-probe-policy.md`, `Modules/Lang/docs/no-phpstan-probe-policy.md`, `Modules/Geo/docs/no-phpstan-probe-policy.md`, `Modules/Tenant/docs/no-phpstan-probe-policy.md`, `Themes/Zero/docs/no-phpstan-probe-policy.md`
