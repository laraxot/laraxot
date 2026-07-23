# LLM Wiki Second Brain Update - 2026-06-06

## ✅ PHPStan Fix Resolution

### Problematic Pattern Identified
```php
// ❌ Before (phpstan error)
#[Override]
public function getFormSchema(): array

// ✅ After (fixed)
public function getFormSchema(): array
```

### Rule Learned
Quando un metodo esiste già nel parent, l'attributo `#[Override]` è **ridondante e causa errore**. Rimuovere sempre l'attributo in questi casi.

### Files Modified
- `Modules/Employee/app/Filament/Widgets/AttendanceOverviewWidget.php`

## Module Architecture Rules

### Rule: 1 Migration per 1 Model
- Ogni modello deve avere esattamente una migrazione
- Schema: `2024_01_01_000001_create_{model}_table.php`

## Composer.json Structure Rule

### Dependency Placement Principle
```bash
# Examples:
laravel/folio → Modules/Cms/composer.json
spatie/laravel-activitylog → Modules/Activity/composer.json
```

Installation commands:
```bash
# Enter module directory
cd Modules/{ModuleName}

# Install package
php -d memory_limit=-1 composer.phar require {package}

# Return to laravel root for analysis
cd ../..
composer dump-autoload
```

## Testing Commands

```bash
./vendor/bin/phpstan analyse Modules --memory-limit=-1
./vendor/bin/pest --filter={ModuleName}
```

---

🕐 Ultimo aggiornamento: 2026-06-06 14:42 UTC