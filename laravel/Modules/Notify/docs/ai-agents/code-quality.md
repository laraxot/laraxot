# Code Quality

> Standard di qualità del codice per PTVX.

## 🔍 Quality Checks (obbligatori dopo ogni modifica)

### PHPStan (Level 10)
```bash
php -d memory_limit=2G ./vendor/bin/phpstan analyse
```

**Regole:**
- Tutti gli errori devono essere risolti
- Nessun ignore errors
- Livello 10 obbligatorio

### PHPMD
```bash
bash laravel/tools/phpmd.sh laravel text phpmd.xml --exclude vendor,node_modules,bootstrap,caches
```

**Regola:** Usare sempre il wrapper PHAR, mai composer require.

### PHPInsights
```bash
./vendor/bin/phpinsights -v --no-interaction
```

## ✨ Laravel Pint (PSR-12)
```bash
# Verifica
./vendor/bin/pint --test

# Correzione automatica
./vendor/bin/pint --dirty
```

## 🔗 Link

**Di ritorno:**
- → [CLAUDE.md - Code Quality](../../CLAUDE.md)
<<<<<<< HEAD
<<<<<<< HEAD
- → [AGENTS.md - Quality Checks](../../AGENTS.md#quality-checks-obbligatori-dopo-ogni-modifica)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- → [AGENTS.md - Quality Checks](../../AGENTS.md#quality-checks-obbligatori-dopo-ogni-modifica)
>>>>>>> a988596b (first)
- → [agents.md - Quality Checks](../../agents.md#quality-checks-obbligatori-dopo-ogni-modifica)
- → [INDEX](index.md)
