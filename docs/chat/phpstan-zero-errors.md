# Coordinamento agenti AI: PHPStan zero errori su Modules/

Canale di coordinamento tra sessioni/agenti AI paralleli che lavorano sullo stesso obiettivo
(vedi anche `laravel/Modules/Xot/docs/phpstan-zero-errors-status.md` per l'inventario tecnico completo).

## Protocollo

- Prima di editare `laravel/Modules/<Modulo>/.../File.php`, controllare che non esista
  `laravel/Modules/<Modulo>/.../File.php.lock`. Se esiste, saltare quel file.
- Se non esiste, crearlo (`touch File.php.lock`), editare, verificare
  (`php -l`, `phpstan analyse <file>`, `pint <file>`, pest se pertinente), poi cancellare il lock.
- Se cancelli un file, ricontrolla con phpstan/phpinsights l'intero modulo che lo conteneva.
- Non modificare `phpstan.neon`. Nessun `ignoreErrors`/baseline/`@phpstan-ignore`/cast per zittire errori.
- Se un test cerca qualcosa che non esiste, correggi il TEST, non inventare codice di produzione.
- Aggiorna questa entry con timestamp + agente + modulo/file quando inizi/finisci un batch, per evitare
  lavoro duplicato tra sessioni diverse.

## Log sessioni

### 2026-07-03 — sessione Claude (agente principale, no id esposto)

- Bonificati 20 file con syntax error puri in `Modules/UI` (refactor troncati: firme metodo mancanti,
  closure non chiuse). Tutti verificati a zero errori phpstan. Dettaglio in
  `laravel/Modules/UI/docs/` (da completare con nota specifica se non gia' presente).
- Root cause fix `Modules/Xot/app/Models/Traits/HasXotFactory.php` (trait non generico) → 6 file puliti
  in Activity/Job/Media.
- Root cause fix `Modules/Xot/app/Contracts/HasRecursiveRelationshipsContract.php` (23 errori
  missingType.generics sulle relation Staudenmeir AdjacencyList) → risolto.
- Tentativo di parallelizzare via subagent Task in background FALLITO (0 tool eseguiti, ritorno
  immediato) — sospetto problema di routing dello stack locale (CCR/DS4/Ollama). Non affidabile in
  questo ambiente: procedere in modalita' diretta, un file alla volta, protocollo lock.
- Inventario totale baseline: 1891 errori / 693 file / 17 moduli. Non completabile in una sessione.
- phpmd non installato (solo phpmd.xml presente, nessun binario in vendor/bin) — non utilizzabile come
  gate finche' non aggiunto a composer.json (richiede approvazione utente, nuova dipendenza).
- Repository moduli sono repo git separati (vedi `git remote -v` in ciascuna cartella `Modules/<X>`,
  org `laraxot` su GitHub) — root repo e' `laraxot/base_techplanner_fila5`.
- Issue di coordinamento aperta: https://github.com/laraxot/base_techplanner_fila5/issues/34
- `gh` CLI e' disponibile e autenticato (account marco76tv) — usato per l'issue sopra. NON ho
  installato nuovi MCP server ne' nuove dipendenze composer (es. phpmd) senza conferma esplicita
  dell'utente: sono modifiche a `composer.json`/infrastruttura che richiedono approvazione per le
  regole del progetto (CLAUDE.md: "Do not change the application's dependencies without approval").
- `Modules/Xot`: 465 -> 147 errori in questa sessione. Fix root-cause aggiuntivi:
  - `Modules/Xot/app/Contracts/ExtraContract.php`: 14 errori missingType.generics sui tag `@method`
    (Builder senza `<Model>`) — risolto con `Builder<Model>|ExtraContract` su tutti i tag.
  - `Modules/Xot/app/Exports/CollectionExport.php`: generics su WithMapping/Collection/array shape —
    risolto completamente (Collection contiene sempre Model in questo export).
  - `Modules/Xot/app/Exports/LazyCollectionExport.php`: stesso pattern, piu' un caso di invarianza dei
    generics di Collection (Laravel Collection non e' `@template-covariant`, quindi passare
    `Collection<int, int|string>` a un metodo tipizzato `Collection<int|string, mixed>` fallisce anche
    se il tipo e' un sottotipo valido). Convenzione gia' presente nel file gemello
    `Modules/Xot/app/Exports/QueryExport.php`: un `@var Collection<int|string, mixed>` inline immediatamente
    prima della chiamata, non per "zittire un bug" ma per dichiarare il contratto generico reale quando
    il tipo concreto e' un sottoinsieme legittimo. Riutilizzato lo stesso pattern.
  - `Modules/Xot/app/Actions/Pdf/MakePdfSpatieTestAction.php`: BLOCCATO, richiede `composer require
    spatie/laravel-pdf` (solo `spatie/browsershot` e' installato). Non toccato, documentato nell'issue.
  - `Modules/Xot/app/Contracts/UserContract.php`: trovato gia' LOCKATO da un altro processo/agente
    durante questa sessione — saltato correttamente per protocollo, non ancora verificato se
    completato da chi lo aveva in carico.
- Prossimi file ad alto impatto in Xot (dopo questa sessione): `Modules/Xot/app/Exports/QueryExport.php`
  (5 errori, verificare se il pattern gia' presente e' completo), `Modules/Xot/helpers/Helper.php` (7),
  `Modules/Xot/app/Exceptions/Handlers/HandlersRepository.php` (6), `Modules/Xot/app/Relations/CustomRelation.php` (5).
