# BMAD Story — Larastan Version Mismatch in Modules/Xot/vendor

## Understand
- **Problema**: PHPStan Internal Error: `Call to undefined method Larastan\Larastan\Reflection\EloquentBuilderMethodReflection::isForwardedQueryBuilderMethod()`
- **Causa root**: `Modules/Xot/vendor/larastan/` contiene versione vecchia di Larastan (senza `isForwardedQueryBuilderMethod()`) mentre `vendor/larastan/` nel root contiene v3.11.0 (con il metodo)
- **Meccanismo**: Quando PHPStan analizza i file in `Modules/Xot/`, carica `EloquentBuilderMethodReflection` dalla versione vecchia in Xot/vendor. Poi `EloquentBuilderExtension` dalla versione nuova nel root chiama `isForwardedQueryBuilderMethod()` → errore
- **Regola**: `phpstan.neon` non si modifica (solo l'owner può farlo); `docs/` sempre aggiornati

## Plan
1. Rimuovere `Modules/Xot/vendor/larastan/` (vecchia versione non necessaria)
2. Verificare che nessun modulo dipenda dalla vecchia versione in Xot/vendor
3. Rieseguire PHPStan su Modules
4. Se persite, il fix richiede modifica `excludePaths` in `phpstan.neon` (richiede owner)

## Implement
### Step 1: Verifica dipendenze
```bash
grep -rn "larastan" Modules/Xot/vendor/*/composer.json 2>/dev/null
```
### Step 2: Backup e rimozione
```bash
# Backup
cp -r Modules/Xot/vendor/larastan /tmp/larastan_backup_xot

# Rimozione
rm -rf Modules/Xot/vendor/larastan
```
### Step 3: Rieseguire PHPStan
```bash
./vendor/bin/phpstan analyse Modules --error-format=table
```

## Verify
- [x] `composer update` eseguito - larastan v3.11.0 confermata
- [x] PHPStan non ha più errori `isForwardedQueryBuilderMethod`
- [x] Errore bootstrap PhpSpreadsheet risolto rimuovendo vecchia versione
- [ ] Da verificare con strumenti completi (phpmd, phpinsights, pest)

## Document
- Questa story documenta il fix
- Rimuovere `Modules/Xot/vendor/larastan/` non è necessario dopo composer update - basta `composer update --with-all-dependencies`
- Aggiornare `docs/CODE_QUALITY_STANDARDS.md` con nota su vendor conflict

## Status
- [x] Root cause identificata (vecchia versione larastan in Modules/Xot/vendor)
- [x] Fix applicato (composer update --with-all-dependencies)
- [x] PHPStan funziona - ~1000+ errori da sistemare
