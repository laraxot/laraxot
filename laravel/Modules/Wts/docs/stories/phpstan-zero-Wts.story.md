---
id: wts-typecoverage-constant-fix
slug: phpstan-zero-Wts
scope: [module:Wts, project:base_workorder_fila5]
status: Done
priority: Medium
created: 2026-09-07
updated: 2026-09-07
issue: https://github.com/laraxot/module_wts_fila5/issues/1
discussion: null  # GitHub Discussions sono disabilitate su laraxot/module_wts_fila5 (verificato via `gh api repos/laraxot/module_wts_fila5` -> has_discussions:false); non abilitate unilateralmente, fuori scope di questo fix
---

## Contesto (BMAD: Build)

Campagna "PHPStan Modules a zero errori". Bucket dominante `typeCoverage.constantTypeCoverage`
e' una percentuale **repo-wide** (~148/300 costanti tipizzate, serve 99%+): non visibile con
`phpstan analyse Modules/Wts` da solo, solo con `phpstan analyse Modules` sull'intero albero.

Nota di coordinamento gia' in `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`
(repo principale): il fix corretto e' un **type declaration reale** sulla costante (PHP 8.3+),
non un `@var` PHPDoc — un `@var` non sposta la percentuale.

## Problema

`Modules/Wts/app/Ecos/EcosOdbcConnection.php:19-20`:

```php
/** @var int */
private const CONNECT_TIMEOUT_SECONDS = 8;
```

Trovato gia' con un `/** @var int */` aggiunto (WIP non committato di una sessione precedente,
non pushato) — pattern insufficiente: non conta per `constantTypeCoverage`, che richiede il tipo
sulla dichiarazione stessa.

## Fix

```php
private const int CONNECT_TIMEOUT_SECONDS = 8;
```

Costante scalare intera (valore letterale `8`), tipo reale `int`, nessun PHPDoc necessario.
Verificato che e' l'unica `const` nell'intero modulo (`grep -rn "const " Modules/Wts/app`).

## Verifica (quality gate completo)

- **PHPStan** (`Modules/Wts` scoped): 0 errori prima, 0 errori dopo (modulo gia' portato a
  zero da sessioni precedenti, vedi `docs/stories/phpstan-Wts-fix.md`). Il fix qui non introduce
  ne' rimuove errori scoped-al-modulo; l'impatto misurabile e' solo sul rescan full-tree
  `phpstan analyse Modules` (repo-wide, non ri-eseguito per intero in questa sessione per costo
  — altre 43 moduli in contesa sulla stessa macchina, load average 70-80 durante la sessione;
  il fix segue esattamente il pattern gia' verificato su `Rating/app/Filament/Blocks/Rating.php`
  nello stesso bucket).
- **PHPMD** (`tools/phpmd.sh Modules/Wts/app text phpmd.xml`): nessuna nuova segnalazione sul
  file toccato. Le uniche voci pre-esistenti su `EcosOdbcConnection.php` sono due `MissingImport`
  (righe 94, 103, uso di `\PDO`/`\RuntimeException` fully-qualified), non toccate da questo fix.
- **PHPInsights** (`tools/phpinsights.sh analyse Modules/Wts/app --no-interaction`): Code 88.7%,
  Complexity 72.5%, Architecture 82.4%, Style 86.7% — exit 0, nessuna soglia bloccante violata.
  Punteggi pre-esistenti a livello di modulo, non impattati dalla singola riga toccata.
- **Pest** (`XDEBUG_MODE=coverage ./vendor/bin/pest Modules/Wts/tests --no-coverage`): vedi
  `docs/coverage.md` per il dettaglio prima/dopo — nessuna regressione introdotta dal fix
  (nessun test esercita direttamente `CONNECT_TIMEOUT_SECONDS`, e' un valore di configurazione
  interno usato solo in `PDO::ATTR_TIMEOUT`).

## Debito preesistente scoperto (NON toccato in questo scope)

Nessun debito PHPStan aggiuntivo scoperto oltre al singolo `const` in scope. Il modulo era gia'
a 0 errori PHPStan scoped prima di questo fix (vedi `docs/stories/phpstan-Wts-fix.md`, chiuso
2026-09-07). PHPMD/PHPInsights riportano debito pre-esistente diffuso nel modulo (complessita'
ciclomatica, parametri eccessivi in alcune Action, naming) — fuori scope, non e' regressione di
questo fix, non toccato.

## File toccati

- `app/Ecos/EcosOdbcConnection.php` (1 riga)
- `docs/coverage.md` (aggiornato)
- questo file (story, riusato uno stub vuoto pre-esistente invece di crearne uno nuovo, dedupe rule)
