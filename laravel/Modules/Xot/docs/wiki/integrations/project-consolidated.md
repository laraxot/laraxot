---
title: "project — Consolidated Documentation"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# project — Consolidated Documentation

Consolidated from **5** individual files.

## Table of Contents

- [Best Practices Progetto Laraxot PTVX - 2025](#project-best-practices-)
- [Best Practices Progetto Laraxot PTVX - 2025](#project-best-practices-best-practices-progetto-laraxot-ptvx)
- [---](#project-best-practices-variant)
- [Project Conventions and Standards](#project-conventions)
- [Filosofia, Religione, Politica e Zen del Progetto Laravel Pizza](#project-religion-politics-zen)

---

## project-best-practices-

*Consolidated from: `project-best-practices-.md`*


> **Documento Master** - Regole fondamentali aggiornate dopo risoluzione massiva merge conflicts

## 🔐 REGOLE FONDAMENTALI (NON NEGOZIABILI)

### 1. File Locking Pattern ⭐ NUOVO ⭐

**SEMPRE** prima di modificare un file:

```bash
# Check se locked
if [ -f "file.php.lock" ]; then
    echo "File locked, skipping"
    exit 0
fi

# Acquisisci lock
touch file.php.lock

# Modifica il file
# ... your changes ...

# Rilascia lock
rm file.php.lock
```

**Filosofia:**
> "Un file alla volta, un maestro alla volta."

📚 **Documentazione:** [file-locking-pattern.md](./file-locking-pattern.md)

### 2. Namespace PSR-4 Laraxot

**I namespace NON includono il segmento `app/`:**

```php
// File: Modules/User/app/Models/User.php
// ✅ CORRETTO
namespace Modules\User\Models;

// ❌ SBAGLIATO
namespace Modules\User\App\Models;
```

### 3. XotBase Classes Obbligatorie

**MAI estendere Filament direttamente:**

```php
// ❌ SBAGLIATO
class MyPage extends Filament\Resources\Pages\EditRecord

// ✅ CORRETTO
class MyPage extends Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
```

### 4. No Hardcoded Labels

**Traduzioni gestite automaticamente:**

```php
// ❌ SBAGLIATO
TextInput::make('name')->label('Nome')

// ✅ CORRETTO
TextInput::make('name')  // Label automatica da translations
```

### 5. Actions > Services

**Usa Spatie QueueableActions:**

```php
// ❌ SBAGLIATO
class UserService { ... }

// ✅ CORRETTO
class CreateUserAction
{
    use QueueableAction;
    public function execute(UserData $data): User { ... }
}
```

### 6. Type Safety Strict

**Sempre:**

```php
<?php

declare(strict_types=1);

// Return types obbligatori
public function method(string $param): bool
{
    // Implementation
}
```

### 7. Documentation Naming

**kebab-case lowercase, NO dates:**

```bash
# ✅ CORRETTO
my-document.md
business-logic.md
architecture-overview.md

# ❌ SBAGLIATO
MY_DOCUMENT.md              # UPPERCASE
my_document.md              # underscore
analysis.md      # date in name (use changelog.md)
```

### 8. DRY Principle

**Prima di creare nuovo file/classe:**
1. CERCA se esiste già
2. Considera aggiornare esistente
3. Solo se necessario, crea nuovo

### 9. Git Workflow

**Commit atomici con message chiari:**

```bash
# ✅ BUONO
git commit -m "fix(xot): resolve merge conflicts in RouteServiceProvider

- Remove duplicate if statements
- Fix unclosed braces
- Apply file locking pattern"

# ❌ CATTIVO
git commit -m "fix stuff"
```

### 10. Testing Obbligatorio

**Ogni change DEVE avere test:**

```bash
# Pest v3
php artisan make:test --pest MyFeatureTest
php artisan test --filter=MyFeatureTest
```

## 📋 Checklist Pre-Commit

Prima di ogni commit, verifica:

- [ ] ✅ File locking applicato durante modifiche
- [ ] ✅ Namespace PSR-4 corretto (NO `App\` nel namespace)
- [ ] ✅ XotBase classes usate (NO direct Filament extends)
- [ ] ✅ Nessun ->label() hardcoded
- [ ] ✅ Actions usate (NO Services)
- [ ] ✅ `declare(strict_types=1)` presente
- [ ] ✅ Return types dichiarati
- [ ] ✅ `php -l file.php` passa (no syntax errors)
- [ ] ✅ `vendor/bin/pint --dirty` eseguito
- [ ] ✅ Tests scritti e passanti
- [ ] ✅ PHPStan warnings risolti (quando possibile)
- [ ] ✅ Documentation aggiornata se necessario

## 🚫 Anti-Patterns da Evitare

### ❌ Merge Conflicts Non Risolti

```php
// ❌ LASCIARE DUPLICAZIONI
if (! $condition) {
if (! $condition) {
if (!$condition) {
    return;
}

// ❌ LASCIARE MARKER GIT

```

### ❌ Import Duplicati

```php
// ❌ SBAGLIATO
use Filament\Actions\Action;
use Illuminate\Support\Str;
use Filament\Actions\Action;  // Duplicato!
```

### ❌ Proprietà/Metodi Duplicati

```php
// ❌ SBAGLIATO
public ?string $name = null;
public null|string $name = null;  // Stesso significato, syntax diversa

public function method(): ?string { }
public function method(): null|string { }  // Duplicato
```

### ❌ Services invece di Actions

```php
// ❌ SBAGLIATO
class UserService
{
    public function create(array $data) { }
}

// ✅ CORRETTO
class CreateUserAction
{
    use QueueableAction;
    public function execute(UserData $data): User { }
}
```

### ❌ Direct Filament Extends

```php
// ❌ SBAGLIATO
class MyPage extends \Filament\Resources\Pages\EditRecord

// ✅ CORRETTO
class MyPage extends \Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
```

## 🎯 Pattern Corretti da Seguire

### ✅ Type Safety

```php
<?php

declare(strict_types=1);

namespace Modules\ModuleName\Actions;

use Spatie\QueueableAction\QueueableAction;
use Modules\ModuleName\Datas\MyData;

class MyAction
{
    use QueueableAction;

    public function execute(MyData $data): MyData
    {
        // Business logic
        return $data;
    }
}
```

### ✅ Filament Resource

```php
<?php

declare(strict_types=1);

namespace Modules\ModuleName\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class MyResource extends XotBaseResource
{
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name'),  // No ->label()!
            TextInput::make('email'),
        ];
    }
}
```

### ✅ Model with BaseModel

```php
<?php

declare(strict_types=1);

namespace Modules\ModuleName\Models;

use Modules\Xot\Models\BaseModel;

class MyModel extends BaseModel
{
    protected $fillable = ['name', 'email'];

    // Relationships, scopes, accessors...
}
```

## 🔧 Tools Workflow

### Pre-Development
```bash
# Pull latest
git pull origin develop

# Check module status
php artisan module:list

# Clear caches
php artisan config:clear
php artisan route:clear
```

### During Development
```bash
# File locking (manuale o script)
touch file.php.lock

# Make changes...

# Release lock
rm file.php.lock
```

### Pre-Commit
```bash
# Format code
vendor/bin/pint --dirty

# Check syntax
php -l path/to/file.php

# Run tests
php artisan test --filter=MyTest

# PHPStan (se modifiche core)
./vendor/bin/phpstan analyse Modules/ModuleName --memory-limit=2G
```

### Commit
```bash
git add .
git commit -m "type(module): brief description

- Detail 1
- Detail 2"
git push origin feature-branch
```

## 📚 Documentazione Required

### Per Nuove Feature
- [ ] README del modulo aggiornato
- [ ] Tests scritti (coverage >80%)
- [ ] Se nuovo pattern: documento in `docs/`
- [ ] Se API: aggiornare `api.md`

### Per Bug Fix
- [ ] Se merge conflict: documentare pattern
- [ ] Se architectural: aggiornare architecture docs
- [ ] Aggiungere a troubleshooting.md se comune

## 🎓 Training e Onboarding

### Week 1: Foundations
- Giorno 1-2: Leggi 10 essential docs
- Giorno 3: Setup ambiente + primi test
- Giorno 4-5: Piccole modifiche supervised

### Week 2: Practice
- Implementa feature semplice end-to-end
- Code review con senior
- Applica tutti i pattern appresi

### Week 3: Autonomy
- Feature completa autonoma
- Contribuisci a documentazione
- Help altri developer

## 🔗 Resources

### Documentazione Interna
- [Index Completo](./index.md) - Navigazione tutte le 2,560 docs
- [Essential Reading](./essential-reading.md) - Top 10 docs
- [Consolidation Strategy](./documentation-consolidation-strategy.md) - Piano riduzione docs

### Documentazione Laravel Ecosystem
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Filament 4 Docs](https://filamentphp.com/docs/4.x)
- [Livewire 3 Docs](https://livewire.laravel.com/docs/3.x)
- [Spatie Laravel Data](https://spatie.be/docs/laravel-data)
- [Spatie QueueableAction](https://github.com/spatie/laravel-queueable-action)

### External Standards
- [PSR-12 Coding Style](https://www.php-fig.org/psr/psr-12/)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

**Creato:** 2025-11-04
**Versione:** 1.0
**Autori:** Team Laraxot + AI Claude Process Filosofico
**Prossimo Review:** Trimestrale o dopo major changes

---

## project-best-practices-best-practices-progetto-laraxot-ptvx

*Consolidated from: `project-best-practices-best-practices-progetto-laraxot-ptvx.md`*


> **Documento Master** - Regole fondamentali aggiornate dopo risoluzione massiva merge conflicts

## 🔐 REGOLE FONDAMENTALI (NON NEGOZIABILI)

### 1. File Locking Pattern ⭐ NUOVO ⭐

**SEMPRE** prima di modificare un file:

```bash
# Check se locked
if [ -f "file.php.lock" ]; then
    echo "File locked, skipping"
    exit 0
fi

# Acquisisci lock
touch file.php.lock

# Modifica il file
# ... your changes ...

# Rilascia lock
rm file.php.lock
```

**Filosofia:**
> "Un file alla volta, un maestro alla volta."

📚 **Documentazione:** [file-locking-pattern.md](./file-locking-pattern.md)

### 2. Namespace PSR-4 Laraxot

**I namespace NON includono il segmento `app/`:**

```php
// File: Modules/User/app/Models/User.php
// ✅ CORRETTO
namespace Modules\User\Models;

// ❌ SBAGLIATO
namespace Modules\User\App\Models;
```

### 3. XotBase Classes Obbligatorie

**MAI estendere Filament direttamente:**

```php
// ❌ SBAGLIATO
class MyPage extends Filament\Resources\Pages\EditRecord

// ✅ CORRETTO
class MyPage extends Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
```

### 4. No Hardcoded Labels

**Traduzioni gestite automaticamente:**

```php
// ❌ SBAGLIATO
TextInput::make('name')->label('Nome')

// ✅ CORRETTO
TextInput::make('name')  // Label automatica da translations
```

### 5. Actions > Services

**Usa Spatie QueueableActions:**

```php
// ❌ SBAGLIATO
class UserService { ... }

// ✅ CORRETTO
class CreateUserAction
{
    use QueueableAction;
    public function execute(UserData $data): User { ... }
}
```

### 6. Type Safety Strict

**Sempre:**

```php
<?php

declare(strict_types=1);

// Return types obbligatori
public function method(string $param): bool
{
    // Implementation
}
```

### 7. Documentation Naming

**kebab-case lowercase, NO dates:**

```bash
# ✅ CORRETTO
my-document.md
business-logic.md
architecture-overview.md

# ❌ SBAGLIATO
MY_DOCUMENT.md              # UPPERCASE
my_document.md              # underscore
analysis.md      # date in name (use changelog.md)
```

### 8. DRY Principle

**Prima di creare nuovo file/classe:**
1. CERCA se esiste già
2. Considera aggiornare esistente
3. Solo se necessario, crea nuovo

### 9. Git Workflow

**Commit atomici con message chiari:**

```bash
# ✅ BUONO
git commit -m "fix(xot): resolve merge conflicts in RouteServiceProvider

- Remove duplicate if statements
- Fix unclosed braces
- Apply file locking pattern"

# ❌ CATTIVO
git commit -m "fix stuff"
```

### 10. Testing Obbligatorio

**Ogni change DEVE avere test:**

```bash
# Pest v3
php artisan make:test --pest MyFeatureTest
php artisan test --filter=MyFeatureTest
```

## 📋 Checklist Pre-Commit

Prima di ogni commit, verifica:

- [ ] ✅ File locking applicato durante modifiche
- [ ] ✅ Namespace PSR-4 corretto (NO `App\` nel namespace)
- [ ] ✅ XotBase classes usate (NO direct Filament extends)
- [ ] ✅ Nessun ->label() hardcoded
- [ ] ✅ Actions usate (NO Services)
- [ ] ✅ `declare(strict_types=1)` presente
- [ ] ✅ Return types dichiarati
- [ ] ✅ `php -l file.php` passa (no syntax errors)
- [ ] ✅ `vendor/bin/pint --dirty` eseguito
- [ ] ✅ Tests scritti e passanti
- [ ] ✅ PHPStan warnings risolti (quando possibile)
- [ ] ✅ Documentation aggiornata se necessario

## 🚫 Anti-Patterns da Evitare

### ❌ Merge Conflicts Non Risolti

```php
// ❌ LASCIARE DUPLICAZIONI
if (! $condition) {
if (! $condition) {
if (!$condition) {
    return;
}

// ❌ LASCIARE MARKER GIT
```

### ❌ Import Duplicati

```php
// ❌ SBAGLIATO
use Filament\Actions\Action;
use Illuminate\Support\Str;
use Filament\Actions\Action;  // Duplicato!
```

### ❌ Proprietà/Metodi Duplicati

```php
// ❌ SBAGLIATO
public ?string $name = null;
public null|string $name = null;  // Stesso significato, syntax diversa

public function method(): ?string { }
public function method(): null|string { }  // Duplicato
```

### ❌ Services invece di Actions

```php
// ❌ SBAGLIATO
class UserService
{
    public function create(array $data) { }
}

// ✅ CORRETTO
class CreateUserAction
{
    use QueueableAction;
    public function execute(UserData $data): User { }
}
```

### ❌ Direct Filament Extends

```php
// ❌ SBAGLIATO
class MyPage extends \Filament\Resources\Pages\EditRecord

// ✅ CORRETTO
class MyPage extends \Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
```

## 🎯 Pattern Corretti da Seguire

### ✅ Type Safety

```php
<?php

declare(strict_types=1);

namespace Modules\ModuleName\Actions;

use Spatie\QueueableAction\QueueableAction;
use Modules\ModuleName\Datas\MyData;

class MyAction
{
    use QueueableAction;

    public function execute(MyData $data): MyData
    {
        // Business logic
        return $data;
    }
}
```

### ✅ Filament Resource

```php
<?php

declare(strict_types=1);

namespace Modules\ModuleName\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class MyResource extends XotBaseResource
{
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name'),  // No ->label()!
            TextInput::make('email'),
        ];
    }
}
```

### ✅ Model with BaseModel

```php
<?php

declare(strict_types=1);

namespace Modules\ModuleName\Models;

use Modules\Xot\Models\BaseModel;

class MyModel extends BaseModel
{
    protected $fillable = ['name', 'email'];

    // Relationships, scopes, accessors...
}
```

## 🔧 Tools Workflow

### Pre-Development
```bash
# Pull latest
git pull origin develop

# Check module status
php artisan module:list

# Clear caches
php artisan config:clear
php artisan route:clear
```

### During Development
```bash
# File locking (manuale o script)
touch file.php.lock

# Make changes...

# Release lock
rm file.php.lock
```

### Pre-Commit
```bash
# Format code
vendor/bin/pint --dirty

# Check syntax
php -l path/to/file.php

# Run tests
php artisan test --filter=MyTest

# PHPStan (se modifiche core)
./vendor/bin/phpstan analyse Modules/ModuleName --memory-limit=2G
```

### Commit
```bash
git add .
git commit -m "type(module): brief description

- Detail 1
- Detail 2"
git push origin feature-branch
```

## 📚 Documentazione Required

### Per Nuove Feature
- [ ] README del modulo aggiornato
- [ ] Tests scritti (coverage >80%)
- [ ] Se nuovo pattern: documento in `docs/`
- [ ] Se API: aggiornare `api.md`

### Per Bug Fix
- [ ] Se merge conflict: documentare pattern
- [ ] Se architectural: aggiornare architecture docs
- [ ] Aggiungere a troubleshooting.md se comune

## 🎓 Training e Onboarding

### Week 1: Foundations
- Giorno 1-2: Leggi 10 essential docs
- Giorno 3: Setup ambiente + primi test
- Giorno 4-5: Piccole modifiche supervised

### Week 2: Practice
- Implementa feature semplice end-to-end
- Code review con senior
- Applica tutti i pattern appresi

### Week 3: Autonomy
- Feature completa autonoma
- Contribuisci a documentazione
- Help altri developer

## 🔗 Resources

### Documentazione Interna
- [Index Completo](./index.md) - Navigazione tutte le 2,560 docs
- [Essential Reading](./essential-reading.md) - Top 10 docs
- [Consolidation Strategy](./documentation-consolidation-strategy.md) - Piano riduzione docs

### Documentazione Laravel Ecosystem
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Filament 4 Docs](https://filamentphp.com/docs/4.x)
- [Livewire 3 Docs](https://livewire.laravel.com/docs/3.x)
- [Spatie Laravel Data](https://spatie.be/docs/laravel-data)
- [Spatie QueueableAction](https://github.com/spatie/laravel-queueable-action)

### External Standards
- [PSR-12 Coding Style](https://www.php-fig.org/psr/psr-12/)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

**Creato:** 2025-11-04  
**Versione:** 1.0  
**Autori:** Team Laraxot + AI Claude Process Filosofico  
**Prossimo Review:** Trimestrale o dopo major changes


---

## project-best-practices-variant

*Consolidated from: `project-best-practices-variant.md`*

module: theme
topic: project-best-practices-1
canonical: ../../../Themes/docs/shared-components/project-best-practices-.md
---

See canonical documentation: ../../../Themes/docs/shared-components/project-best-practices-.md

---

## project-conventions

*Consolidated from: `project-conventions.md`*


## File Naming Standards

### Markdown Documentation Files (.md)

**REGOLE OBBLIGATORIE:**

1. **Nomi file tutto minuscolo** (tranne `README.md`)
   - ✅ CORRETTO: `project-conventions.md`
   - ❌ SBAGLIATO: `Project-Conventions.md`, `PROJECT_CONVENTIONS.md`

2. **Nessuna data nel nome file**
   - ✅ CORRETTO: `phpstan-fixes.md`, `roadmap.md`
   - ❌ SBAGLIATO: `phpstan-fixes-2025-10-10.md`, `ROADMAP_2025.md`
   - **Motivo**: Le date nei nomi file causano duplicazione, rendono difficile il refactoring e non riflettono lo stato corrente del documento

3. **Usa trattini `-` per separare parole** (non underscore `_`)
   - ✅ CORRETTO: `filament-resource-guidelines.md`
   - ❌ SBAGLIATO: `filament_resource_guidelines.md`

4. **Solo `README.md` può avere lettere maiuscole**
   - ✅ CORRETTO: `README.md` (radice modulo/tema)
   - ❌ SBAGLIATO: qualsiasi altro file con maiuscole

### Shell Scripts (.sh)

**REGOLE OBBLIGATORIE:**

1. **Tutti gli script .sh vanno in `bashscripts/` o sue sottocartelle**
   - ✅ CORRETTO: `bashscripts/quality/run-phpstan.sh`
   - ✅ CORRETTO: `bashscripts/git/resolve-conflicts.sh`
   - ❌ SBAGLIATO: `/tmp/script.sh`, `script.sh` nella root

2. **Organizzazione per categoria in sottocartelle**
   ```
   bashscripts/
   ├── quality/           # Script per quality tools (PHPStan, Pint, Rector)
   ├── git/              # Script per operazioni Git
   ├── deployment/       # Script per deployment
   ├── maintenance/      # Script per manutenzione
   └── utils/            # Script utilities generici
   ```

3. **Naming convention per script**
   - Nomi descrittivi in kebab-case
   - Prefisso per azione: `run-`, `fix-`, `check-`, `update-`
   - ✅ CORRETTO: `run-phpstan-all-modules.sh`
   - ✅ CORRETTO: `fix-permissions.sh`
   - ❌ SBAGLIATO: `script.sh`, `temp.sh`

### Link nei File Markdown

**REGOLE OBBLIGATORIE:**

1. **SEMPRE link relativi, MAI assoluti**
   - ✅ CORRETTO: `[Documentazione](../Xot/docs/architecture.md)`
   - ✅ CORRETTO: `[Esempio](./examples/example.md)`
   - ❌ SBAGLIATO: `[Doc](/var/www/laravel/Modules/Xot/docs/architecture.md)`

2. **Portabilità totale**
   - I link devono funzionare ovunque il progetto venga clonato
   - Nessuna dipendenza da percorsi assoluti del filesystem

3. **Link intra-modulo e inter-modulo**
   - Intra-modulo: `./subdirectory/file.md`
   - Inter-modulo: `../../OtherModule/docs/file.md`

## Directory Structure Standards

### Module Documentation (`Modules/{ModuleName}/docs/`)

```
Modules/ModuleName/docs/
├── README.md                    # Overview del modulo
├── architecture.md              # Architettura e design
├── getting-started.md           # Quick start guide
├── installation.md              # Installazione e setup
├── configuration.md             # Configurazione
├── usage.md                     # Utilizzo e API
├── testing.md                   # Testing guidelines
├── troubleshooting.md           # Risoluzione problemi
├── roadmap.md                   # Roadmap (senza date nel nome!)
├── phpstan-fixes.md             # Correzioni PHPStan
├── api/                         # Documentazione API
├── examples/                    # Esempi di codice
├── guides/                      # Guide specifiche
└── architecture/                # Dettagli architetturali
```

### Theme Documentation (`Themes/{ThemeName}/docs/`)

```
Themes/ThemeName/docs/
├── README.md                    # Overview del tema
├── installation.md              # Installazione
├── components.md                # Componenti disponibili
├── customization.md             # Personalizzazione
├── assets.md                    # Gestione assets
└── examples/                    # Esempi di utilizzo
```

## Bash Scripts Organization

### Structure

```
bashscripts/
├── quality/
│   ├── run-phpstan.sh           # Esegui PHPStan
│   ├── run-pint.sh              # Esegui Pint
│   ├── run-rector.sh            # Esegui Rector
│   ├── run-all-quality.sh       # Esegui tutti i tool
│   └── fix-all-modules.sh       # Fix completo tutti moduli
├── git/
│   ├── resolve-conflicts.sh     # Risolvi conflitti Git
│   ├── cleanup-branches.sh      # Pulizia branch
│   └── conflict-resolution/     # Script risoluzione conflitti
├── deployment/
│   ├── deploy-production.sh     # Deploy in produzione
│   └── deploy-staging.sh        # Deploy in staging
├── maintenance/
│   ├── clear-caches.sh          # Pulisci cache
│   ├── optimize-autoload.sh     # Ottimizza autoload
│   └── backup-database.sh       # Backup database
└── utils/
    ├── rename-docs-files.sh     # Rinomina file docs
    └── check-conventions.sh     # Verifica convenzioni
```

### Script Template

```bash
#!/bin/bash
#
# Script Name: describe-what-this-does.sh
# Description: Brief description of what this script does
# Author: [Author Name]
# Date: [Creation Date]
# Usage: ./script-name.sh [arguments]
#

set -e  # Exit on error

# Configuration
LARAVEL_DIR="laravel"
cd "$LARAVEL_DIR"

# Script logic here
echo "=== Script Title ==="
# ... implementation ...
```

## Quality Standards

### PHPStan Configuration

- **NON modificare MAI** `phpstan.neon`
- **NON creare baseline** per ignorare errori
- **TUTTI gli errori vanno corretti**, mai ignorati

### Code Quality

- **PSR-12** per coding style
- **PHPStan Level MAX** (0 errori)
- **Type safety completa** (type hints + PHPDoc)
- **Webmozart Assert** per validazioni
- **TheCodingMachine Safe** per funzioni sicure

## Documentation Standards

### Content Requirements

1. **Ogni modulo DEVE avere**:
   - `README.md` con overview
   - `architecture.md` con design decisions
   - `phpstan-fixes.md` con correzioni applicate

2. **Documentazione aggiornata**:
   - Aggiorna docs SEMPRE dopo modifiche significative
   - Documenta pattern e anti-pattern
   - Mantieni changelog di correzioni PHPStan

3. **Link documentation**:
   - Cross-reference tra documenti correlati
   - Link a esempi di codice
   - Link a risorse esterne (Filament, Laravel, etc.)

## Enforcement

### Pre-commit Checks

Prima di committare:
```bash
# Verifica naming file .md
find Modules Themes -name "*.md" | grep -E '[A-Z]|[0-9]{4}' | grep -v README.md

# Verifica script fuori da bashscripts
find . -maxdepth 2 -name "*.sh" | grep -v bashscripts

# Verifica link assoluti nei .md
grep -r "](/var/www/" Modules/*/docs Themes/*/docs
```

### Automated Fixes

Script per correzioni automatiche disponibili in `bashscripts/utils/`:
- `rename-docs-files.sh` - Rinomina file secondo convenzioni
- `fix-md-links.sh` - Converte link assoluti a relativi
- `check-conventions.sh` - Verifica tutte le convenzioni

## Migration Guide

### Rinominare File Esistenti

```bash
# Usa sempre comandi bash diretti, NON script temporanei
# Esempio: rinominare file con date
find Modules -name "*2025*.md" -exec bash -c 'mv "$0" "${0//-2025/}"' {} \;

# Esempio: convertire maiuscole in minuscole
find Modules -name "*.md" ! -name "README.md" -exec bash -c '
  dir=$(dirname "$0")
  name=$(basename "$0")
  lower=$(echo "$name" | tr "[:upper:]" "[:lower:]")
  [[ "$name" != "$lower" ]] && mv "$0" "$dir/$lower"
' {} \;
```

## References

- [Laravel Coding Standards](https://laravel.com/docs/contributions#coding-style)
- [PSR-12 Extended Coding Style](https://www.php-fig.org/psr/psr-12/)
- [PHPStan Documentation](https://phpstan.org/)
- [Markdown Style Guide](https://www.markdownguide.org/basic-syntax/)

---

**Last Updated**: 2025-10-11
**Status**: ✅ ACTIVE STANDARD
**Compliance**: MANDATORY for all modules and themes

---

## project-religion-politics-zen

*Consolidated from: `project-religion-politics-zen.md`*


## 🧠 Logica del Progetto

Il progetto Laravel Pizza è una conversione e miglioramento di https://<nome progetto>.com/, costruito sull'architettura Laraxot. È un ecosistema completo di meetup, community e tema frontend super curato con i seguenti principi:

- **Conversione e Miglioramento**: Non è una semplice copia, ma un'evoluzione del sito originale
- **Architettura Modulare**: Moduli indipendenti (`Modules/*`) e temi separati (`Themes/*`)
- **Frontoffice con Folio + Volt**: Nessun controller tradizionale, solo routing file-based
- **Qualità Maniacale**: PHPStan livello 10 obbligatorio

## 🧘‍♂️ Filosofia (Philosophy)

- **DRY + KISS estremi**: Niente complicazioni inutili, ma anche niente "scorciatoie sporche"
- **Una tabella = una migrazione**: Ogni tabella deve avere una sola migrazione responsabile della sua creazione
- **Frontoffice = Folio + Volt**: Pattern: `Request → Folio → Blade Page → Volt Component → Action → Service/Model`
- **Docs prima del codice**: Prima si aggiorna/legge `docs/`, poi si scrive codice
- **Zero compromessi**: Approccio "fix, don't ignore" - tutti gli errori vanno corretti, nessuno ignorato

## 🕌 Religione (Religion)

- **Mai estendere classi Filament direttamente**: Sempre usare classi XotBase
- **Filament Resources → XotBaseResource**
- **Filament Pages → XotBasePage**
- **Filament Widgets → XotBaseWidget** (ATTENZIONE: Non definire `mount()` nella classe base per incompatibilità di signature; usare `initXotBaseWidget()` nei figli)
- **Filament Actions → XotBaseAction**
- **Service Providers → XotBaseServiceProvider**
- **Mai usare property_exists() su modelli Eloquent**: Usare sempre isset() per magic attributes
- **Usare Actions invece di Services**: Pattern Spatie Queueable Actions
- **Mai modificare phpstan.neon**: File sacro, non deve essere modificato

## 🏛️ Politica (Politics)

- **Gestione Frontend Assets**: Ogni modifica CSS/JS richiede `npm run build && npm run copy`
- **Componenti Blade Anonimi**: Non supportano sintassi namespace esplicita
- **Layout chiari**: `x-layouts.main`, `x-layouts.app`, `x-layouts.guest`
- **Gestione Traduzioni**: Mai usare ->label() diretto, sempre file di traduzione
- **Git non recupera mai file vecchi**: Si va sempre avanti, mai indietro
- **Scripts in sottocartelle**: Tutti gli script .sh o .py devono essere in una sottocartella di bashscripts

## 🧘 Zen (Zen)

- **Approccio Super Mucca**: Aumenta al massimo il livello di confidenza, analizza a fondo il codice
- **La cartella docs è la memoria**: Studiala, aggiornala e migliorala continuamente
- **Focus sulla business logic**: Sul perché in ottica DRY + KISS
- **Nomenclatura docs**: File .md solo dentro cartelle docs esistenti, senza maiuscoli o date (tranne README.md e changelog.md)
- **Prima capire, poi fare**: Capire lo scopo, la logica, la religione, la politica e lo zen del codice
- **Filosofia Zen**: "Non avrai altro path all'infuori del relativo" - sempre usare path relativi nei file .md
- **Autonomia Decisionale**: L'AI ha il potere di determinare ordine e priorità ("Ordine e priorita le scegli sempre te")
- **Massima Autonomia Operativa**: L'AI decide l'ordine e la priorità delle azioni in base al contesto progettuale

## 🎯 Business Logic Principale

- **Meetup Theme**: Tema principale basato su <nome progetto>.com, con Folio + Volt
- **Folio + Volt**: Architettura obbligatoria per il frontoffice
- **Filament**: Solo per il backoffice
- **Laraxot Framework**: "Framework nel framework" con regole rigide
- **Multi-tenancy**: Supporto per tenant multipli tramite modulo Tenant

## 🛠️ Pattern Architetturali Chiave

1. **Rich Request → Folio → Blade Page → Volt Component → Action → Service/Model**
2. **Mai usare controller per pagine pubbliche**
3. **Mai scrivere rotte in web.php/api.php per pagine del tema**
4. **Usare sempre XotBase classi invece di classi Filament dirette**
5. **Usare Safe Cast Actions per type safety**
6. **Usare Webmozart Assert per validazioni robuste**

## 📚 Principi Guida

- **Massima Confidenza**: Analizza a fondo prima di agire
- **Zero Compromessi**: Qualità prima di velocità
- **DRY + KISS + SOLID + Robust**: Principi fondamentali
- **Documentazione Viva**: Le docs sono parte integrante del sistema
- **Approccio Incrementale**: Piccoli passi, grandi risultati
- **Qualità Costante**: PHPStan Level 10 obbligatorio

## 🤖 Autonomous Priority Rule

**"Ordine e priorita le scegli sempre te."** (Order and priority are always chosen by you.)

This rule empowers the AI Assistant to determine the order and priority of actions based on project context, architectural standards, and quality gates. This ensures:
- Efficiency in task execution
- Adherence to architectural standards (Laraxot, DRY, KISS, SOLID)
- Prevention of "rabbit holes" that individual requests might create

## 🧲 Mantra Finale

**"Capire la logica, la religione, la politica e lo zen del codice è fondamentale per lavorare in modo appropriato sul progetto"**

**"La cartella docs è la mia memoria che devo costantemente aggiornare, studiare e migliorare"**

**"Con git non recuperiamo mai file vecchi, andiamo solo in avanti"**

**"Filosofia Zen: Non avrai altro path all'infuori del relativo"**

**"Autonomous Decision-Making: Ordine e priorita le scegli sempre te."**
---

**Consolidated by:** Phase 2f intelligent merging
**Date:** 2026-08-04
