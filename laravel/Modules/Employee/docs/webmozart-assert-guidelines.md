# Webmozart Assert Guidelines - Best Practices

## 📚 Panoramica

**webmozarts/assert** è una libreria per asserzioni type-safe che aiuta a prevenire errori di tipo in PHP. Nel progetto Laraxot, è principalmente utilizzata per risolvere errori PHPStan legati a type narrowing.

## 🔍 Stato Attuale nel Progetto

### **Installazione**
```json
// composer.json (indirettamente via rector/rector)
"rector/rector": "^1.0",
// che dipende da webmozart/assert
```

### **Utilizzo Reale**
- **Nessun uso diretto** di `use Webmozart\Assert` nel codice sorgente
- **Utilizzo principale**: `Assert::` (probabilmente da `Illuminate\Support\Facades\Assert` o simili)
- **Documentazione**: Ampi riferimenti in file di documentazione PHPStan

### **Pattern Trovati** (dal modulo Job):
```php
// Esempi di utilizzo trovati
Assert::string($date_format, '['.__LINE__.']['.class_basename($this).']');
Assert::isArray($res = $this->getResource()::getFormSchema());
Assert::isInstanceOf($model, Schedule::class);
```

## 🎯 Quando Usare Webmozart Assert

### **✅ CASI VALIDI**

#### 1. **Type Narrowing per PHPStan**
```php
public function process($data): void
{
    // $data è mixed, bisogno di type narrowing
    Assert::isArray($data);
    // Ora PHPStan sa che $data è array

    foreach ($data as $item) {
        Assert::isString($item);
        // Ora PHPStan sa che $item è string
    }
}
```

#### 2. **Validazione Input da Fonti Esterne**
```php
public function handleRequest(array $input): void
{
    // Input da API/HTTP - bisogno di validazione type-safe
    Assert::keyExists($input, 'user_id');
    Assert::integer($input['user_id']);
    Assert::greaterThan($input['user_id'], 0);

    Assert::keyExists($input, 'email');
    Assert::string($input['email']);
    Assert::notEmpty($input['email']);
}
```

#### 3. **Contract Validation**
```php
public function __construct($repository)
{
    // Validazione contratti/interfacce
    Assert::isInstanceOf($repository, UserRepositoryInterface::class);
    $this->repository = $repository;
}
```

### **❌ CASI DA EVITARE**

#### 1. **Dati già Type-hinted**
```php
// ❌ SBAGLIATO - Ridondante
public function process(string $name): void
{
    Assert::string($name); // Già type-hinted!
    // ...
}

// ✅ CORRETTO
public function process(string $name): void
{
    // $name è già garantito essere string
    // ...
}
```

#### 2. **Dati Interni al Modello**
```php
// ❌ SBAGLIATO - Usa type hints invece
class User extends Model
{
    public function getName(): string
    {
        Assert::string($this->name); // Non necessario
        return $this->name;
    }
}

// ✅ CORRETTO - Usa casts() o type hints
class User extends Model
{
    protected function casts(): array
    {
        return [
            'name' => 'string', // PHPStan lo capisce
        ];
    }

    public function getName(): string
    {
        return $this->name; // Già type-safe
    }
}
```

#### 3. **Eloquent Attribute Access**
```php
// ❌ SBAGLIATO - Usa SafeEloquentCastAction
class TimeEntry extends Model
{
    public function getTotalHours(): float
    {
        Assert::numeric($this->total_hours); // Non necessario
        return (float) $this->total_hours;
    }
}

// ✅ CORRETTO - Usa casts()
class TimeEntry extends Model
{
    protected function casts(): array
    {
        return [
            'total_hours' => 'decimal:2', // Type-safe
        ];
    }

    public function getTotalHours(): float
    {
        return $this->total_hours; // Già castato
    }
}
```

## 🛠️ Pattern di Utilizzo Consigliati

### **Pattern 1: API Input Validation**
```php
use Webmozart\Assert\Assert;

class UserController
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // Type-safe validation per PHPStan
        Assert::isArray($data);
        Assert::keyExists($data, 'name');
        Assert::string($data['name']);
        Assert::keyExists($data, 'email');
        Assert::string($data['email']);

        $user = User::create($data);

        return response()->json($user);
    }
}
```

### **Pattern 2: Service Method Contracts**
```php
use Webmozart\Assert\Assert;

class PaymentService
{
    public function processPayment($amount, $currency): PaymentResult
    {
        // Contract validation
        Assert::numeric($amount);
        Assert::greaterThan($amount, 0);
        Assert::string($currency);
        Assert::length($currency, 3);

        // Ora PHPStan sa i tipi
        $floatAmount = (float) $amount;
        $upperCurrency = strtoupper($currency);

        return $this->gateway->charge($floatAmount, $upperCurrency);
    }
}
```

### **Pattern 3: Factory Methods**
```php
use Webmozart\Assert\Assert;

class OrderFactory
{
    public static function createFromArray(array $data): Order
    {
        // Validazione completa
        Assert::keyExists($data, 'items');
        Assert::isArray($data['items']);
        Assert::notEmpty($data['items']);

        Assert::keyExists($data, 'customer_id');
        Assert::integer($data['customer_id']);

        foreach ($data['items'] as $item) {
            Assert::isArray($item);
            Assert::keyExists($item, 'product_id');
            Assert::keyExists($item, 'quantity');
            Assert::integer($item['product_id']);
            Assert::integer($item['quantity']);
            Assert::greaterThan($item['quantity'], 0);
        }

        return Order::create($data);
    }
}
```

## ⚠️ Avvertenze e Errori Comuni

### **Errore PHPStan: `staticMethod.alreadyNarrowedType`**
```php
// ❌ CAUSA ERRORE PHPStan
public function process(string $input): void
{
    Assert::string($input); // ERRORE: already narrowed type
    // ...
}
```

**Soluzione**: Rimuovi l'assert ridondante.

### **Performance Considerations**
```php
// ❌ Troppi assert in loop
foreach ($items as $item) {
    Assert::isArray($item); // Chiamato N volte
    Assert::keyExists($item, 'id');
    Assert::integer($item['id']);
}

// ✅ Validazione batch
Assert::allIsArray($items);
Assert::allKeyExists($items, 'id');
Assert::allInteger(array_column($items, 'id'));
```

### **Legacy Code Integration**
```php
// Quando lavori con codice legacy
public function processLegacyData($legacyData): void
{
    // Step 1: Validazione type-safe
    Assert::isArray($legacyData);

    // Step 2: Migrazione graduale
    $validatedData = $this->validateAndConvert($legacyData);

    // Step 3: Nuovo codice type-hinted
    $this->processNewData($validatedData);
}

private function validateAndConvert(array $data): ValidatedData
{
    Assert::keyExists($data, 'old_field');
    Assert::string($data['old_field']);

    return new ValidatedData([
        'new_field' => (string) $data['old_field'],
    ]);
}
```

## 🔄 Alternative a Webmozart Assert

### **1. PHPStan Type Hints (Preferito)**
```php
// ✅ MIGLIORE - Type hints nativi
public function process(string $name, int $age): void
{
    // PHPStan già conosce i tipi
    echo "Name: $name, Age: $age";
}
```

### **2. Eloquent Casts (Per Modelli)**
```php
// ✅ OTTIMALE - Casts nativi
class User extends Model
{
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
```

### **3. Custom Assertion Classes**
```php
// ✅ PER DOMINI SPECIFICI
class OrderAssertions
{
    public static function assertValidOrder(array $order): void
    {
        Assert::keyExists($order, 'items');
        Assert::notEmpty($order['items']);

        foreach ($order['items'] as $item) {
            self::assertValidOrderItem($item);
        }
    }

    private static function assertValidOrderItem(array $item): void
    {
        Assert::keyExists($item, 'product_id');
        Assert::keyExists($item, 'quantity');
        Assert::greaterThan($item['quantity'], 0);
    }
}
```

## 📊 Decision Tree: Quando Usare Assert

```
┌─────────────────────────────────────┐
│        Dovrei usare Assert?         │
└─────────────────┬───────────────────┘
                  │
    ┌─────────────▼─────────────┐
    │ Il dato viene da fonte    │
    │ esterna (API, DB, User)?  │
    └─────────────┬─────────────┘
                  │
        ┌─────────▼─────────┐   NO
        │        SI         ├─────────┐
        └─────────┬─────────┘         │
                  │                   │
    ┌─────────────▼─────────────┐     │
    │ PHPStan segnala errori    │     │
    │ di tipo (mixed, etc.)?    │     │
    └─────────────┬─────────────┘     │
                  │                   │
        ┌─────────▼─────────┐   NO    │
        │        SI         ├─────────┤
        └─────────┬─────────┘         │
                  │                   │
    ┌─────────────▼─────────────┐     │
    │  ✅ USA WEBMOZART ASSERT  │     │
    └───────────────────────────┘     │
                                      │
                          ┌───────────▼───────────┐
                          │  ✅ USA TYPE HINTS    │
                          │  O ELOQUENT CASTS     │
                          └───────────────────────┘
```

## 🧪 Test e Verifica

### **Test 1: Validazione Assert Funziona**
```php
// Test che gli assert catturano errori
public function testAssertCatchesInvalidInput(): void
{
    $this->expectException(InvalidArgumentException::class);

    $service = new PaymentService();
    $service->processPayment('invalid', 'USD'); // Assert fallirà
}
```

### **Test 2: PHPStan Compliance**
```bash
# Verifica che non ci siano errori staticMethod.alreadyNarrowedType
./vendor/bin/phpstan analyse --level=10
```

### **Test 3: Performance Impact**
```php
// Benchmark assert vs no assert
$start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    Assert::string('test');
}
$timeWithAssert = microtime(true) - $start;

$start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    // senza assert
}
$timeWithoutAssert = microtime(true) - $start;

$overhead = ($timeWithAssert / $timeWithoutAssert - 1) * 100;
// Tipicamente < 5% overhead per validazioni critiche
```

## 📈 Metriche di Successo

### **Quantitative**
- **PHPStan errors**: Riduzione errori `mixed` type
- **Code coverage**: Assert aiutano a identificare edge cases
- **Bug rate**: Riduzione bug di tipo

### **Qualitative**
- **Code clarity**: I contratti sono espliciti
- **Maintainability**: Facile capire i requisiti di input
- **Developer confidence**: Meno sorprese a runtime

## 🔗 Collegamenti Utili

- [Webmozart Assert Documentation](https://github.com/webmozarts/assert)
- [PHPStan Type Narrowing](https://phpstan.org/writing-php-code/phpdoc-types#type-narrowing)
- [Eloquent Casts Documentation](https://laravel.com/docs/eloquent-mutators#attribute-casting)
- [SafeEloquentCastAction](../../Xot/docs/actions/safe-eloquent-cast-action.md)

## 🚀 Implementazione Graduale

### **Fase 1: Identificazione**
```bash
# Cerca errori PHPStan mixed type
grep -r "mixed" --include="*.php" Modules/ | head -20
```

### **Fase 2: Prioritizzazione**
1. API endpoints con input esterni
2. Service methods con contratti complessi
3. Factory methods
4. Legacy code integration points

### **Fase 3: Implementazione**
```php
// PRIMA
public function process($data): void
{
    // $data è mixed
    $name = $data['name']; // PHPStan: mixed offset access
}

// DOPO
public function process($data): void
{
    Assert::isArray($data);
    Assert::keyExists($data, 'name');
    Assert::string($data['name']);

    $name = $data['name']; // PHPStan: string access ✅
}
```

### **Fase 4: Verifica**
```bash
# Verifica riduzione errori PHPStan
./vendor/bin/phpstan analyse --generate-baseline
# Confronta con baseline precedente
```

## 📝 Checklist per Nuovo Codice

### **Quando Aggiungere Assert**
- [ ] Input da fonte esterna (API, user, file)
- [ ] PHPStan segnala errori `mixed` type
- [ ] Contratti complessi tra servizi
- [ ] Validazione business rules specifiche

### **Quando NON Aggiungere Assert**
- [ ] Dati già type-hinted nei parametri
- [ ] Attributi di modelli Eloquent (usa `casts()`)
- [ ] Variabili locali con scope limitato
- [ ] Performance-critical loops (usa validazione batch)

### **Best Practices**
- [ ] Usa `Assert::` all'inizio del metodo
- [ ] Fornisci messaggi di errore descrittivi
- [ ] Considera performance in loop
- [ ] Rimuovi assert ridondanti dopo type hints
- [ ] Documenta i contratti nei PHPDoc

---

**Regola Fondamentale**: Usa `webmozarts/assert` per type narrowing quando necessario, ma preferisci sempre type hints nativi e Eloquent casts quando possibile. L'obiettivo è codice type-safe con il minimo overhead necessario.