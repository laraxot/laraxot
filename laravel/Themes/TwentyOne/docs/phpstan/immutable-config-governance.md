# PHPStan Immutable Config Governance

## Regola attiva

Nel repository `base_predict_fila5` il file `laravel/phpstan.neon` e' parte della governance condivisa e non va modificato nelle normali wave di remediation.

## Impatto pratico

- Il quality gate da rispettare e' sempre `max`.
- Gli errori emersi da `./vendor/bin/phpstan analyse Modules` si correggono nel codice dei moduli, dei temi e nella loro documentazione tecnica.
- Non si abbassa il livello, non si sposta il problema nel file di configurazione e non si altera il contratto dell'analizzatore.

## Workflow richiesto

1. Studiare le cartelle `docs/` del modulo o tema coinvolto.
2. Cercare fix omologhi negli altri progetti sotto `/var/www/_bases`.
3. Documentare il pattern riusato.
4. Applicare il fix nel codice.
5. Verificare con `phpstan`, `phpmd`, `phpinsights` e `pest`.
