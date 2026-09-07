---
name: catalog-basemodel-missing-connection-fix
description: BaseModel Catalog mancava $connection = 'catalog', violava convenzione universale dei 28 moduli
metadata:
  type: story
  status: done
  module: Catalog
  date: 2026-09-07
  claimed_by: claude-sonnet-5 (this session)
---

# Story: fix `Modules\Catalog\Models\BaseModel` — connessione DB mancante

## Richiesta utente (2026-09-07, seconda iterazione)

Dopo la mia prima verifica (story
`product-basemodel-xotbasemodel-chain-verify.story.md`, chiusa come "nessuna
modifica necessaria"), l'utente ha segnalato che `BaseModel.php` mancava di:

```php
/**
 * The connection name for the model.
 */
protected $connection = 'catalog';
```

Ha chiesto esplicitamente di capire PRIMA come mai ho fatto questo errore, poi
correggere, usando di nuovo BMAD e controllando se un altro agente aveva gia'
preso il task.

## Owned File/Module Scope

- `laravel/Modules/Catalog/app/Models/BaseModel.php`
- `laravel/Modules/Catalog/docs/coverage.md`

## Controllo altri agenti (obbligatorio, non fidarsi)

Trovato `docs/chat/2026-09-07-catalog-basemodel-added-already-done.md`:
un agente precedente (commit `9a67667`, oggi) aveva GIA' creato questo
`BaseModel.php` con la stessa mancanza, giustificandola esplicitamente:
"no dedicated DB connection — none configured for catalog, not guessed".
Quella giustificazione e' quella che ha ingannato anche me nella prima
iterazione: ho visto il file gia' nello stato "corretto per la richiesta
letterale" e non ho controllato oltre.

## Perche' l'errore (analisi causa radice, come richiesto)

1. **Ho verificato solo la richiesta letterale dell'utente** (Product extends
   BaseModel, BaseModel extends XotBaseModel) e mi sono fermato li', senza
   applicare la regola Laravel Boost del progetto: "check sibling files for
   the correct structure, approach, naming" prima di dichiarare un file
   corretto/completo.
2. **Non ho controllato i 28 moduli sibling.** Ogni singolo altro
   `Modules/<Mod>/app/Models/BaseModel.php` (Activity, AiAssistant, Blog,
   Cms, Comment, Costing, Customer, Document, Email, Employee, EnergyBroker,
   Gdpr, Geo, Intervention, Job, Lang, Media, MetalWork, Notify, Quotation,
   Rating, Signage, Signature, Tenant, Timber, UI, User, Vehicle, WhatsApp,
   WorkOrder, Wts, Xot) imposta `protected $connection = <module_snake_name>`.
   Catalog era l'unico senza. Un controllo comparativo l'avrebbe rivelato
   subito.
3. **Ho trattato il messaggio di commit di un altro agente come prova**,
   invece di verificare il codice runtime. Standing order pillar 3: "verifica
   sul codice (git log -S), non sulla documentazione" — il commit diceva
   "none configured for catalog", ma non ho controllato
   `Modules/Tenant/app/Providers/TenantServiceProvider.php`.
4. **La giustificazione era comunque falsa.**
   `TenantServiceProvider::mergeModuleConnections()` clona la connessione di
   default per OGNI modulo abilitato, chiave = `$module->getSnakeName()`
   (quindi `catalog` esiste dinamicamente a runtime, anche se assente da
   `config/database.php` in modo statico — pattern identico per `blog`,
   `comment`, che infatti hanno alias statici in `config/database.php` con
   un commento che spiega proprio questo meccanismo).

## Fix applicato

```php
abstract class BaseModel extends XotBaseModel
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'catalog';
}
```

## Verifica (obbligatoria prima di dichiarare fatto)

- Lock preso su `app/Models/BaseModel.php` prima dell'edit
  (`bashscripts/lock/lock.sh`), rilasciato a fine task.
- `phpstan analyse Modules/Catalog` (cache pulita): 0 errori.
- `./tools/phpmd.sh Modules/Catalog/app/Models/BaseModel.php text
  Modules/Xot/phpmd.ruleset.xml` (nessun ruleset locale in Catalog, uso
  quello di Xot come fallback condiviso): 0 violazioni.
- `phpinsights analyse Modules/Catalog` (config ephemeral `/tmp`, vedi
  second brain): Code 92.8 / Complexity 96.5 / Architecture 88.2 / Style
  88.0 — tutti sopra soglia 70.
- `pest Modules/Catalog/tests --no-coverage`: confrontato before/after con
  `git stash` — 6 failed/5 passed (prima) -> 5 failed/6 passed (dopo).
  Nessuna regressione, un test in piu' passa. Le 5 failure residue sono
  `Base table or view not found` sul DB `workorder_data_test` per la
  connessione `catalog` — problema pre-esistente di migrazioni mancanti sul
  DB di test, non introdotto da questo fix. Dettagli in
  `docs/coverage.md`.

## Esito

Fix committato nel repo del modulo Catalog e pushato su
`laraxot/dev`. Second brain aggiornato: memoria precedente
(`pattern_module_basemodel_intermediate_xotbasemodel.md`) corretta,
nuova memoria feedback su come evitare questo errore in futuro.
