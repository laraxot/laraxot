# Enhanced Development Rules - TechPlanner Project

## 🚀 Overview

Questo documento aggiorna e consolida tutte le regole di sviluppo per il progetto TechPlanner, basandosi sull'analisi approfondita di webmozarts/assert, della documentazione dei moduli e dei temi.

## 📋 Table of Contents

1. [PHP Development Rules](#php-development-rules)
2. [Laravel & Laraxot Philosophy](#laravel--laraxot-philosophy)
3. [Frontend Development Rules](#frontend-development-rules)
4. [Documentation Standards](#documentation-standards)
5. [Testing & Quality Assurance](#testing--quality-assurance)
6. [Security & Performance](#security--performance)
7. [Git & Version Control](#git--version-control)

---

## PHP Development Rules

### 🔒 Type Safety with webmozarts/assert

**REGOLA FONDAMENTALE**: Usa sempre `webmozart/assert` per validare input/output dei metodi invece di controlli manuali.

#### ✅ Pattern Corretto
```php
use Webmozart\Assert\Assert;

class TimeEntry extends BaseModel
{
    public function calculateTotalHours(): float
    {
        Assert::notNull($this->clock_in, 'Clock in time is required');
        Assert::notNull($this->clock_out, 'Clock out time is required');
        Assert::integer($this->break_duration, 'Break duration must be integer');
        Assert::greaterThanEq($this->break_duration, 0, 'Break duration cannot be negative');
        
        $totalMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        $totalMinutes -= $this->break_duration;
        
        return round($totalMinutes / 60, 2);
    }
}
```

#### ❌ Anti-Pattern da Evitare
```php
// SBAGLIATO - Controlli manuali
public function calculateTotalHours(): float
{
    if (!$this->clock_in || !$this->clock_out) {
        return 0.0;
    }
    // Manca validazione tipi e valori
}
```

#### Metodi Assert Principali
```php
// Tipi base
Assert::string($value, 'Expected string, got: %s');
Assert::integer($value, 'Expected integer, got: %s');
Assert::float($value, 'Expected float, got: %s');
Assert::boolean($value, 'Expected boolean, got: %s');
Assert::array($value, 'Expected array, got: %s');
Assert::object($value, 'Expected object, got: %s');

// Confronti
Assert::greaterThan($value, $min, 'Value must be > %s, got: %s');
Assert::lessThan($value, $max, 'Value must be < %s, got: %s');
Assert::range($value, $min, $max, 'Value must be between %s and %s, got: %s');

// Stringhe
Assert::minLength($value, $min, 'String must be at least %s characters, got: %s');
Assert::maxLength($value, $max, 'String must be at most %s characters, got: %s');
Assert::email($value, 'Invalid email: %s');
Assert::uuid($value, 'Invalid UUID: %s');

// Array
Assert::minCount($array, $min, 'Array must have at least %s elements, got: %s');
Assert::maxCount($array, $max, 'Array must have at most %s elements, got: %s');
Assert::keyExists($array, $key, 'Array must contain key %s');

// Null/Empty
Assert::notNull($value, 'Value cannot be null');
Assert::notEmpty($value, 'Value cannot be empty');
Assert::nullOrString($value, 'Value must be string or null, got: %s');
```

### 🏗️ Class Architecture Rules

#### 1. Estensione BaseModel (MAI Eloquent diretto)
```php
// ✅ CORRETTO
final class TimeEntry extends BaseModel
{
    // ...
}

// ❌ SBAGLIATO
class TimeEntry extends Model  // MAI!
{
    // ...
}
```

#### 2. Classi Final Sempre
```php
// ✅ CORRETTO - Tutti i modelli devono essere final
final class Employee extends BaseModel
{
    // ...
}

// ✅ CORRETTO - Le classi astratte per ereditarietà
abstract class BaseEmployee extends BaseModel
{
    // ...
}
```

#### 3. Property Type Hints
```php
// ✅ CORRETTO - Tipi nativi per le proprietà
final class TimeEntry extends BaseModel
{
    protected array $fillable = [
        'employee_id',
        'clock_in',
        // ...
    ];
}

// ❌ SBAGLIATO - Property senza tipo nativo
final class TimeEntry extends BaseModel
{
    protected $fillable = [  // Manca "array"
        'employee_id',
        // ...
    ];
}
```

#### 4. Metodo casts() vs $casts
```php
// ✅ CORRETTO - Laravel 11+ usa metodo casts()
protected function casts(): array
{
    return [
        'clock_in' => 'datetime',
        'total_hours' => 'decimal:2',
        'metadata' => 'array',
    ];
}

// ❌ DEPRECATO - Non usare proprietà $casts
// protected $casts = [  // DEPRECATO!
//     'clock_in' => 'datetime',
// ];
```

### 🔧 Method Implementation Rules

#### 1. Non Duplicare Metodi Eloquent
```php
// ❌ SBAGLIATO - Wrapper inutili
final class TimeEntry extends BaseModel
{
    public static function find($id): ?static
    {
        return parent::find($id);  // DUPLICAZIONE INUTILE!
    }
}

// ✅ CORRETTO - Usa metodi Eloquent direttamente
TimeEntry::find($id);  // Funziona perfettamente senza wrapper
```

#### 2. Metodi Business Logic Pur
```php
// ✅ CORRETTO - Solo logica specifica del modello
final class TimeEntry extends BaseModel
{
    public function calculateTotalHours(): float
    {
        // Logica business specifica
    }
    
    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'auto_approved'], true);
    }
}
```

#### 3. Strict Typing Sempre
```php
// ✅ CORRETTO - Strict types e tipi completi
declare(strict_types=1);

final class TimeEntry extends BaseModel
{
    public function calculateTotalHours(): float
    {
        // ...
    }
    
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
```

---

## Laravel & Laraxot Philosophy

### 🎯 XotBase Extension Rules

#### REGOLA CRITICA: Estendi sempre XotBase, MAI Filament diretto

```php
// ❌ SBAGLIATO - MAI estendere Filament direttamente
use Filament\Resources\Resource;
class UserResource extends Resource  // VIOLAZIONE CRITICA!
{
}

// ✅ CORRETTO - Estendi sempre XotBase
use Modules\Xot\Filament\Resources\XotBaseResource;
class UserResource extends XotBaseResource
{
}
```

#### Mapping Completo Classi
| ❌ SBAGLIATO | ✅ CORRETTO |
|-------------|------------|
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` |
| `Filament\Resources\Pages\ListRecords` | `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord` |
| `Filament\Pages\Page` | `Modules\Xot\Filament\Pages\XotBasePage` |
| `Filament\Schemas\Components\Section` | `Modules\Xot\Filament\Schemas\Components\XotBaseSection` |

### 🔄 Actions vs Services

#### Usa sempre Spatie Queueable Actions
```php
// ❌ SBAGLIATO - Service tradizionale
class UserService
{
    public function createUser(array $data): User
    {
        // ...
    }
}

// ✅ CORRETTO - Queueable Action
use Spatie\QueueableAction\QueueableAction;

final class CreateUserAction
{
    use QueueableAction;

    public function execute(array $data): User
    {
        Assert::notEmpty($data, 'Data cannot be empty');
        Assert::string($data['name'], 'Name must be string');
        Assert::email($data['email'], 'Invalid email');
        
        // Logica creazione utente
    }
}
```

### 🌐 Translation Management

#### Non usare ->label() diretto
```php
// ❌ SBAGLIATO - Testo hardcoded
TextInput::make('name')
    ->label('Nome')
    ->placeholder('Inserisci nome');

// ✅ CORRETTO - Usa sistema traduzioni
TextInput::make('name')
// Le traduzioni sono gestite automaticamente da LangServiceProvider
```

#### Struttura File Traduzione
```
Modules/{ModuleName}/lang/{locale}/{resource}.php

// Esempio: Modules/User/lang/it/user.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci nome',
        ],
    ],
];
```

---

## Frontend Development Rules

### 🎨 Theme Development Standards

#### 1. Vite Configuration Rules
```blade
{{-- ✅ CORRETTO - Sempre secondo parametro tema --}}
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')

{{-- ❌ SBAGLIATO - Senza parametro tema --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

#### 2. Package.json Scripts Standard
```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "copy": "cp -r ./public/* ../../../public_html/themes/Sixteen",
        "analyze": "vite-bundle-analyzer"
    }
}
```

#### 3. Component Organization
```php
// ✅ CORRETTO - Componenti tematici organizzati
namespace Themes\Sixteen\Components\Forms;

class TimeEntryForm extends Component
{
    // ...
}

// ✅ CORRETTO - Layout inheritance
@extends('themes::sixteen.layouts.app')
```

### 📱 Responsive & Accessibility

#### AGID Compliance (Sixteen Theme)
- WCAG 2.1 AA compliance
- Bootstrap Italia components
- Contrast ratio 4.5:1 minimum
- Keyboard navigation support
- Screen reader compatibility

#### Mobile-First Development
```css
/* ✅ CORRETTO - Mobile-first approach */
.component {
    /* Mobile styles */
    font-size: 14px;
}

@media (min-width: 768px) {
    .component {
        /* Tablet styles */
        font-size: 16px;
    }
}

@media (min-width: 1024px) {
    .component {
        /* Desktop styles */
        font-size: 18px;
    }
}
```

---

## Documentation Standards

### 📁 Docs Structure Template

#### Structure Standard per Moduli
```
Modules/{ModuleName}/docs/
├── README.md                    # Overview modulo (OBBLIGATORIO)
├── index.md                     # Indice alternativo
├── architecture/
│   ├── overview.md              # Architettura generale
│   ├── patterns.md              # Design patterns
│   └── models.md                # Data models
├── development/
│   ├── getting-started.md       # Setup sviluppo
│   ├── best-practices.md        # Best practices
│   └── testing.md               # Guide testing
├── business-logic/
│   └── overview.md              # Logica business
├── quality/
│   ├── phpstan.md               # PHPStan compliance
│   ├── standards.md             # Code standards
│   └── performance.md           # Performance
├── integration/
│   ├── modules.md               # Integrazione altri moduli
│   └── api.md                   # API documentation
└── resources/
    ├── troubleshooting.md       # Troubleshooting
    ├── faq.md                   # FAQ
    └── changelog.md             # Change log
```

#### Structure Standard per Temi
```
Themes/{ThemeName}/docs/
├── README.md                    # Overview tema
├── components.md                # Componenti UI
├── layouts.md                   # Layout system
├── vite-configuration.md        # Vite setup
├── accessibility.md            # Accessibility features
└── troubleshooting.md           # Common issues
```

### 📝 Writing Standards

#### Naming Conventions
- ✅ File .md sempre in lowercase (eccetto README.md)
- ✅ Nomi descrittivi con trattini: `vite-configuration.md`
- ✅ Nessuna data nei nomi: `setup-guide.md` (non `2025-12-12-setup.md`)
- ✅ Cartelle in lowercase

#### Content Standards
```markdown
# Title

## Overview
Brief description of purpose and scope.

## Requirements
List of prerequisites.

## Implementation
Step-by-step guide with code examples.

## Examples
Practical usage examples.

## Troubleshooting
Common issues and solutions.

## See Also
Related documentation links.
```

---

## Testing & Quality Assurance

### 🧪 PHPStan Level 10 Compliance

#### Obbligatorio per Tutti i Moduli
```bash
# Esegui analisi completa
./vendor/bin/phpstan analyse Modules --memory-limit=-1 --level=10

# Analisi singolo modulo
./vendor/bin/phpstan analyse Modules/Employee --memory-limit=-1 --level=10
```

#### Pattern PHPStan-Compliant
```php
// ✅ CORRETTO - Tipi espliciti
final class TimeEntry extends BaseModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Employee, TimeEntry>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    /**
     * Calculate total hours worked.
     */
    public function calculateTotalHours(): float
    {
        Assert::notNull($this->clock_in, 'Clock in required');
        Assert::notNull($this->clock_out, 'Clock out required');
        
        // Implementation
    }
}
```

### 📊 Quality Metrics

#### PHPInsights Targets
- **Code Quality**: ≥ 90%
- **Complexity**: ≥ 95%
- **Architecture**: ≥ 90%
- **Style**: ≥ 90%

#### Run Complete Analysis
```bash
# PHPInsights completo
./vendor/bin/phpinsights analyse Modules --min-quality=90

# PHPMD analysis
./vendor/bin/phpmd Modules/Employee/app/Models text cleancode,codesize,controversial,design,naming,unusedcode
```

---

## Security & Performance

### 🔒 Security Rules

#### 1. Input Validation con Assert
```php
final class CreateUserAction
{
    public function execute(array $data): User
    {
        // Validazione input con Assert
        Assert::notEmpty($data, 'Data cannot be empty');
        Assert::string($data['name'] ?? '', 'Name required');
        Assert::minLength($data['name'], 2, 'Name too short');
        Assert::email($data['email'] ?? '', 'Invalid email');
        Assert::string($data['password'] ?? '', 'Password required');
        Assert::minLength($data['password'], 8, 'Password too short');
        
        // Procedi con creazione sicura
    }
}
```

#### 2. Never Expose Secrets
```php
// ❌ SBAGLIATO - Mai loggare dati sensibili
Log::info('User created: ' . $user->email); // Potrebbe contenere PII

// ✅ CORRETTO - Log sicuro
Log::info('User created', ['user_id' => $user->id]);
```

#### 3. Property Exists vs HasAttribute
```php
// ❌ SBAGLIATO - property_exists non funziona con Eloquent
if (property_exists($user, 'email')) { ... }

// ✅ CORRETTO - Usa hasAttribute per Eloquent
if ($user->hasAttribute('email')) { ... }
```

### ⚡ Performance Rules

#### 1. Eager Loading
```php
// ❌ SBAGLIATO - N+1 queries
$users = User::all();
foreach ($users as $user) {
    echo $user->profile->name; // Query per ogni utente!
}

// ✅ CORRETTO - Eager loading
$users = User::with('profile')->get();
foreach ($users as $user) {
    echo $user->profile->name; // Single query!
}
```

#### 2. Database Indexing
```php
// Migration con indici appropriati
Schema::create('time_entries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained();
    $table->timestamp('clock_in');
    $table->timestamp('clock_out')->nullable();
    
    // Indici per performance
    $table->index(['employee_id', 'clock_in']);
    $table->index('status');
});
```

---

## Git & Version Control

### 🌳 Git Workflow

#### 1. Mai Ripristinare File Vecchi
```bash
# ❌ SBAGLIATO - Non ripristinare versioni vecchie
git checkout HEAD~1 -- file.php

# ✅ CORRETTO - Vai sempre avanti
# Fai nuove modifiche e commit
```

#### 2. Commit Message Standards
```bash
# ✅ CORRETTO - Commit message chiari
git commit -m "feat: add time entry validation with Assert"

# ❌ SBAGLIATO - Messaggi vaghi
git commit -m "fix stuff"
```

#### 3. Branch Strategy
```bash
# Feature branch
git checkout -b feature/time-entry-validation

# Commit regolari
git add .
git commit -m "feat: implement Assert validation"

# Push e merge
git push origin feature/time-entry-validation
# Create PR e merge dopo review
```

---

## 🎯 Quick Reference Checklist

### Before Coding
- [ ] Ho letto la documentazione del modulo?
- [ ] Ho verificato le dipendenze esistenti?
- [ ] Ho capito la business logic?

### During Coding
- [ ] Uso `declare(strict_types=1)`?
- [ ] Uso `webmozart/assert` per validazioni?
- [ ] Estendo classi XotBase (MAI Filament diretto)?
- [ ] Uso Actions invece di Services?
- [ ] Tipizzo tutti i metodi e proprietà?

### After Coding
- [ ] PHPStan Level 10 passa?
- [ ] PHPMD non ha issues?
- [ ] PHPInsights ≥ 90%?
- [ ] Ho aggiornato la documentazione?
- [ ] Ho eseguito i test?

### Before Commit
- [ ] Ho verificato il diff?
- [ ] Il commit message è chiaro?
- [ ] Ho aggiornato docs/ se necessario?

---

## 📚 Additional Resources

### Documentation
- [Xot Module Documentation](../laravel/Modules/Xot/docs/)
- [Filament v4 Upgrade Guide](../laravel/Modules/Xot/docs/filament_v4_upgrade.md)
- [PHPStan Best Practices](../laravel/Modules/Xot/docs/phpstan-best-practices.md)

### Tools & Commands
- [Development Commands](./development_commands.md)
- [Testing Guidelines](./testing_guidelines.md)
- [Deployment Checklist](./deployment_checklist.md)

---

**Version**: 2.0.0  
**Last Updated**: 2025-12-12  
**Status**: ✅ Active & Enforced