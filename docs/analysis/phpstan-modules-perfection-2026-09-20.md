---
title: "Analisi PHPStan Modules — perfezione 2026-09-20"
type: analysis
status: active
created: 2026-09-20
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/23
---
# Outcome

Portare il comando canonico `./vendor/bin/phpstan analyse Modules --no-progress` a zero errori preservando il comportamento funzionale dei moduli.

# Evidenze iniziali

- PHPStan carica `laravel/phpstan.neon`, che resta immutabile e modificabile solo dall'utente.
- La scansione globale termina prima dell'analisi con un parse error in `Modules/User/app/Models/Traits/HasTeams.php:11`.
- Il file nel working tree è una versione corrotta/minificata e il diff locale riporta 0 inserimenti e 1249 eliminazioni.
- Anche la versione `HEAD` contiene marker di conflitto ed è sintatticamente invalida; quindi non è una sorgente valida da ripristinare.
- `laravel/phpstan.targets.neon` è presente oltre al config canonico. Non sarà modificato dall'agente; la scansione canonica osservata continua comunque a caricare `phpstan.neon`.
- Il repository `Modules/User` contiene numerose modifiche preesistenti: ogni intervento deve essere limitato ai file esplicitamente assegnati e preceduto da confronto con storia e coordinamento.
- Gli script wrapper second-brain indicati dallo stub non esistono nel checkout; la ricerca QMD MCP non ha restituito documenti. Le fonti locali correnti sono quindi la base verificata.

# Invarianti funzionali

1. Correggere il tipo o il contratto alla sorgente, senza cast ciechi, ignore PHPStan, baseline o riduzione del livello.
2. Conservare semantica pubblica, relazioni Eloquent, policy, autorizzazioni e contratti Filament.
3. Non modificare `phpstan.neon` né creare configurazioni alternative.
4. Non usare restore, checkout, reset, revert o rollback; ricostruire in avanti soltanto da fonti storiche valide e codice corrente.
5. Non sovrascrivere WIP altrui; lavorare per scope disgiunti e verificare ogni file prima dell'edit.
6. Gli array associativi multilinea toccati mantengono una chiave per riga.

# Strategia di diagnosi

1. Sbloccare il parser ricostruendo `HasTeams.php` dalla più recente revisione storica sintatticamente valida, confrontandola con API e chiamanti correnti.
2. Rieseguire la scansione globale in formato machine-readable per inventariare file, moduli e identifier.
3. Distribuire in ordine pseudo-casuale batch disgiunti per modulo; nessun worker modifica configurazioni, documentazione root o file di altri worker.
4. Rieseguire PHPStan globale dopo ogni wave e correggere centralmente i soli errori cross-module.
5. Validare ogni file PHP modificato con `php -l`, Pint, PHPStan mirato, test mirati e gli strumenti di qualità realmente disponibili.
