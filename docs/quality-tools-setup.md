# Setup Strumenti Qualità del Codice

**Ultimo aggiornamento**: 2025-01-27

---

## 🔧 Strumenti Disponibili

### PHPStan
- **Location**: `laravel/vendor/bin/phpstan`
- **Config**: `laravel/phpstan.neon`
- **Livello**: 9 (target: max/10)
- **Comando**: `./vendor/bin/phpstan analyse Modules --level=9 --memory-limit=2G`

### PHPMD
- **Location**: `laravel/phpmd.phar` (scaricato)
- **Alternative**: `laravel/vendor/bin/phpmd`
- **Versione**: 2.15.0snapshot202312110823
- **Comando**: `php phpmd.phar Modules/{Modulo} text cleancode,codesize,design,naming,unusedcode`

### PHPInsights
- **Location**: `laravel/phpinsights.phar` (symlink a vendor/bin/phpinsights)
- **Alternative**: `laravel/vendor/bin/phpinsights`
- **Comando**: `./vendor/bin/phpinsights analyse Modules/{Modulo} --format=table --no-interaction --min-quality=70`

### Laravel Pint
- **Location**: `laravel/vendor/bin/pint`
- **Comando**: `./vendor/bin/pint Modules/{Modulo} --test`

---

## 📜 Script Disponibili

### Controllo Singolo Modulo
```bash
bashscripts/quality/check-module-quality.sh <nome_modulo>
```

**Esempio**:
```bash
bashscripts/quality/check-module-quality.sh Tenant
```

### Controllo Tutti i Moduli
```bash
bashscripts/quality/check-all-modules.sh
```

**Output**: Reports salvati in `laravel/storage/logs/quality-reports/`

---

## 📋 Workflow CI/CD

Tutti i moduli hanno workflow GitHub Actions che eseguono:
1. PHPStan (Level 10)
2. PHPMD
3. Pint
4. PHPInsights

**Location**: `Modules/{Modulo}/.github/workflows/quality.yml`

---

## 🎯 Metriche Target

- **PHPStan**: 0 errori (Level 9+)
- **PHPInsights**: Quality score > 70%
- **PHPMD**: 
  - CyclomaticComplexity < 10
  - NPathComplexity < 200
  - MethodLength < 100 righe
- **Pint**: Nessun warning (PSR-12)

---

## 📝 Note

- I warnings **StaticAccess** per facades Laravel sono normali e accettabili
- La complessità ciclomatica alta indica necessità di refactoring
- NPath complexity elevata suggerisce logica condizionale troppo annidata

---

## 🔗 Collegamenti

- [PHPStan Analysis](laravel/Modules/Xot/docs/phpstan-analysis-2025-01-27.md)
- [Quality Guide](laravel/Modules/Xot/docs/php-quality-guide.md)
- [Tenant Quality Analysis](laravel/Modules/Tenant/docs/quality-analysis.md)
