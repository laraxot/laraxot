# TechPlanner Development Rules

## 🚨 Principi Fondamentali

### 1. **DRY + KISS + Clean Code**
- Non duplicare mai codice o logica
- Mantieni il codice semplice e leggibile
- Scrivi codice auto-documentante

### 2. **Type Safety First**
- PHPStan Level 10 compliance obbligatoria
- Strict types sempre abilitati (`declare(strict_types=1)`)
- Type hints completi per parametri e return

### 3. **Laraxot Philosophy**
- Estendi sempre classi XotBase, mai classi Filament direttamente
- Usa Actions invece di Services per business logic
- Segui pattern consolidati del framework

---

## 📋 Regole di Sviluppo Aggiornate

### 1. **Webmozarts/Assert Integration** 🆕

**Usa Webmozarts/Assert per tutte le validazioni input/output:**

```php
use Webmozart\Assert\Assert;

// ✅ CORRETTO
public function updateEmployee(int $id, array $data): Employee
{
    Assert::positiveInteger($id, 'Employee ID must be positive, got: %s');
    Assert::isArray($data, 'Data must be array, got: %s');
    Assert::notEmpty($data, 'Data cannot be empty');
    
    if (isset($data['email'])) {
        Assert::email($data['email'], 'Invalid email: %s');
    }
    
    // Logica business...
}

// ❌ SBAGLIATO - Validazioni manuali
public function updateEmployee(int $id, array $data): Employee
{
    if (!is_int($id) || $id <= 0) {
        throw new InvalidArgumentException('Invalid ID');
    }
    // ...
}
```

### 2. **Model Refactoring Rules** 🆕

**Rimuovi metodi ridondanti dai modelli:**

```php
// ❌ SBAGLIATO - Metodi wrapper inutili
class TimeEntry extends BaseModel
{
    public static function find($id): ?static
    {
        return parent::find($id); // INUTILE
    }
    
    public static function where($column, $value): Builder
    {
        return parent::where($column, $value); // INUTILE
    }
}

// ✅ CORRETTO - Solo business logic
class TimeEntry extends BaseModel
{
    public function calculateTotalHours(): float
    {
        // Logica specifica del modello
    }
    
    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'auto_approved'], true);
    }
}
```

### 3. **Filament Extension Rules** (Aggiornate)

Fonte canonica:

- [Filament Extension Rules (Architecture)](architecture/filament-extension-rules.md)

### 4. **Vite Configuration Rules** 🆕

**Temi Laravel - Pattern Vite obbligatorio:**

```blade
{{-- ✅ CORRETTO - Sempre con secondo parametro --}}
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')

{{-- ❌ SBAGLIATO - Senza secondo parametro --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Package.json temi - Copy command obbligatorio:**
```json
{
  "scripts": {
    "copy": "cp -r ./public/* ../../../public_html/themes/Sixteen"
  }
}
```

### 5. **PHPStan Return Type Rules** 🆕

**Correggi mismatch tipi con Safe Casting Actions:**

```php
// ❌ PROBLEMA - Return type mismatch
public function getFormattedAddressAttribute(): string
{
    return $this->address; // Potrebbe essere mixed
}

// ✅ CORRETTO - Safe casting
public function getFormattedAddressAttribute(): string
{
    return \Modules\Xot\Actions\Cast\SafeStringCastAction::cast($this->address, '');
}
```

---

## 🔧 Pattern Implementazione

### 1. **Action Pattern** (Spatie QueueableAction)

```php
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class CreateEmployeeAction
{
    use QueueableAction;

    public function execute(array $data): Employee
    {
        // Validazione input con Assert
        Assert::keyExists($data, 'email', 'Missing email');
        Assert::email($data['email'], 'Invalid email: %s');
        Assert::stringNotEmpty($data['name'], 'Name cannot be empty');
        
        // Business logic
        $employee = Employee::create($data);
        
        // Eventi dispatch
        event(new EmployeeCreated($employee));
        
        return $employee;
    }
}
```

### 2. **Model Pattern**

```php
final class Employee extends BaseModel
{
    /** @var list<string> */
    protected array $fillable = [
        'name',
        'email',
        'department_id',
    ];

    // Relazioni
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Scope
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // Business logic methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Casting method (Laravel 11+)
    protected function casts(): array
    {
        return [
            'hired_at' => 'datetime',
            'salary' => 'decimal:2',
            'metadata' => 'array',
        ];
    }
}
```

### 3. **Resource Pattern**

```php
class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;

    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required(),
                
            EmailInput::make('email')
                ->required()
                ->unique(), // Traduzioni gestite automaticamente
                
            Select::make('department_id')
                ->relationship('department', 'name')
                ->searchable(),
        ];
    }

    // getTableColumns() gestito da XotBaseResource
    // getPages() non necessario se standard
    // getTableActions() non necessario se standard
}
```

---

## 📚 Documentation Standards

### 1. **Struttura Docs Moduli**

```
docs/
├── README.md                    # Overview modulo
├── architecture/               # Design patterns
├── development/                # Guide sviluppo
├── phpstan/                    # Compliance PHPStan
├── filament/                   # Guide Filament v4
├── testing/                    # Strategie test
└── business-logic-overview.md  # Logica business
```

### 2. **Convenzioni Naming**

- ✅ File .md in lowercase (eccetto README.md)
- ✅ Nomi descrittivi con trattini
- ✅ Categorizzazione con prefissi
- ✅ Link relativi sempre

### 3. **Template README Modulo**

```markdown
# [Module Name] Module

## Quick Start
- [Installation](./installation.md)
- [Configuration](./configuration.md)
- [Basic Usage](./basic-usage.md)

## Architecture
- [Overview](./architecture/overview.md)
- [Design Patterns](./architecture/patterns.md)

## Development
- [Getting Started](./development/getting-started.md)
- [Best Practices](./development/best-practices.md)
- [Testing](./development/testing.md)

## Quality
- [PHPStan Compliance](./quality/phpstan.md)
- [Code Standards](./quality/standards.md)
```

---

## 🚨 Critical Rules (NON NEGOTIABLE)

### 1. **Property Exists SU MODELLI ELOQUENT** ❌

```php
// ❌ MAI USARE property_exists() su Eloquent models
if (property_exists($model, 'attribute')) { // SBAGLIATO }

// ✅ USA hasAttribute(), isFillable() o Schema::hasColumn()
if ($model->hasAttribute('attribute')) { // CORRETTO }
if ($model->isFillable('attribute')) { // CORRETTO }
if (Schema::hasColumn($model->getTable(), 'attribute')) { // CORRETTO }
```

### 2. **Estensione Diretta Filament** ❌

```php
// ❌ MAI estendere direttamente classi Filament
class MyPage extends Page { } // SBAGLIATO

// ✅ Estendi sempre XotBase
class MyPage extends XotBasePage { } // CORRETTO
```

### 3. **Protected $casts Deprecato** ❌

```php
// ❌ DEPRECATO - Laravel 10 e precedenti
protected $casts = [
    'field' => 'datetime',
];

// ✅ CORRETTO - Laravel 11+
protected function casts(): array
{
    return [
        'field' => 'datetime',
    ];
}
```

### 4. **BadgeColumn Deprecato** ❌

```php
// ❌ DEPRECATO
BadgeColumn::make('status')

// ✅ CORRETTO
TextColumn::make('status')->badge()
```

---

## 🔄 Workflow Sviluppo

### 1. **Prima di Scrivere Codice**
1. Studia documentazione esistente nel modulo
2. Verifica pattern già implementati
3. Controlla regole specifiche del modulo

### 2. **Durante Sviluppo**
1. Usa Webmozarts/Assert per validazioni
2. Estendi classi XotBase appropriate
3. Segui pattern DRY e KISS
4. Scrivi test per business logic

### 3. **Dopo Sviluppo**
1. Esegui PHPStan Level 10
2. Esegui PHPMD e PHPInsights
3. Aggiorna documentazione modulo
4. Verifica compliance con tutte le regole

---

## 📋 Checklist Pre-Commit

- [ ] PHPStan Level 10 senza errori
- [ ] PHPMD senza problemi critical
- [ ] PHPInsights qualità > 90%
- [ ] Nessun uso di `property_exists()` su Eloquent
- [ ] Estensione XotBase corretta
- [ ] Webmozarts/Assert per validazioni
- [ ] Documentazione aggiornata
- [ ] Test superati
- [ ] Strict types abilitati
- [ ] Type hints completi

---

## 🔗 Riferimenti Utili

- [Webmozarts/Assert Rules](../bashscripts/prompts/webmozarts_assert_rules.txt)
- [Filament Extension Rules](../bashscripts/prompts/filament_class.txt)
- [PHPStan Return Type Errors](../laravel/Modules/Geo/docs/phpstan_return_type_errors.md)
- [XotBase Documentation](../laravel/Modules/Xot/docs/)

---

**Filosofia Aggiornata**: Code quality through type safety, clean architecture, and comprehensive validation with Webmozarts/Assert.