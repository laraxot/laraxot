# Story: phpstan-fleet-remediation-remains
**Status**: ready-for-dev
**Modulo**: Xot (coordinatore fleet-wide)
**Epic**: 5.124 bmad-user-module-perfection-study

## AC
- [ ] Correggere errori PHPStan residui su IndennitaCondizioniLavoro (CreatesApplication.php non esiste)
- [ ] Correggere errori PHPStan su Incentivi (#[Override] orfani in getFormSchemaOld)
- [ ] Correggere errori PHPStan su Performance (Cannot override final method getFormSchema)
- [ ] Correggere errori PHPStan su Pdnd (C015Service TypeError)
- [ ] Verificare phpstan analyse Modules -> 0 errori / exit 0
- [ ] Creare file mancanti o rimuovere riferimenti phpstan.path

## Note
- Lock: /tmp/phpstan-swarm-${mod}.lock per ogni modulo
- Non usare migrate:fresh, --force, RefreshDatabase
- Host 10.100.200.15: nessun test
