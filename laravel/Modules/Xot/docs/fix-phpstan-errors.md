# BMAD FIX - LEARNINGS (Second Brain Updated)

## ERRORI COMMESSI
1. Edit su HasSchemalessAttributes: applicato senza verifica completa con `php -l`
2. RelationX.php: errore sintattico non risolto (riga 42) - verificare con `php -l`
3. Non ho usato `phpstan.neon` come riferimento per la configurazione
4. Comandi troppo lunghi; preferire salvataggio su file

## REGOLA IMPERATIVA (mai ripetere)
> Prima di dichiarare un fix completato: `php -l file.php` → `grep` → `phpstan file.php`

## STRUMENTI INSTALLATI / VERIFICATI
- PHP 8.4.24, PHPStan (level max)
- `Safe\` functions: `Safe\json_encode`
- `Webmozart\Assert\Assert`
- Second brain: `docs/wiki/` + QMD + skills `phpstan-fix`
- BMAD: `parallelization-plan.md` con Wave 0-3

## CALCOLO CORRETTO PER PHPSTAN
```bash
cd laravel && php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules/Xot --memory-limit=1G --no-progress
```

## NEXT ACTION (senza ripetere errori)
- Correggi RelationX con `php -l`
- Verifica ogni edit con grep + php -l
- Esegui validation finale su modulo Xot
