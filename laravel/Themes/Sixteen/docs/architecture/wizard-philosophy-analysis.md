# 🧘‍♂️ Analisi Filosofica Wizard Architecture

## Visione d'Insieme

### Architettura Corrente (3 Livelli)

```
1. Filament CreateRecord (Vendor)
   ├── Pattern standard per form singolo
   ├── State management automatico
   └── Hook system rigido

2. XotBaseWizardWidget (Custom)
   ├── Specializzazione per wizard multi-step
   ├── Gestione stato annidato complesso
   └── Politica sicurezza step query

3. CreateTicketWizardWidget (Dominio)
   ├── Business logic specifica
   ├── 3 step con logica custom
   └── Integration con servizi esterni
```

## 🎯 Perché Questa Architettura? (Filosofia Profonda)

### 1. **Separazione delle Responsabilità** (SOLID Principle)
- **Filament**: Si occupa del framework base
- **XotBase**: Si occupa del protocollo wizard
- **Fixcity**: Si occupa del dominio

> *"Ogni classe dovrebbe avere una sola ragione per cambiare"*

### 2. **DRY + KISS nel Cuore**
- **UNA** implementazione del protocollo wizard
- **ZERO** ripetizione della logica step navigation
- **AUTO** label system via LangServiceProvider

### 3. **Allineamento con Filament**
- Non si sostituisce Filament, si estende
- Usa i componenti ufficiali (Wizard, Step, Action)
- Mantiene la compatibility con il ecosistema

## 📜 Regole d'Oro (Religione del Codice)

### REGOLA 1: **NO Hardcoded Labels**
```php
// SBAGLIATO - Rompe l'auto-label system
->label('Nome campo')

// CORRETTO - Usa il pattern automatico
TextInput::make('name')
```

### REGOLA 2: **Enum Safety First**
```php
// SBAGLIATO - Cast diretto su enum
$type = TicketTypeEnum::tryFrom((string) ($get('type') ?? ''));

// CORRETTO - Validazione tipo prima del cast
$typeValue = $get('type_id');
$type = is_string($typeValue) ? TicketTypeEnum::tryFrom($typeValue) : null;
```

### REGOLA 3: **Error Handling Pattern**
```php
// SBAGLIATO - Log nel dominio
Log::error($e->getMessage());

// CORRETTO - Notification user-friendly
->catch(\Throwable::class, function ($e) {
    Notification::make()->danger()->title('Errore')->send();
})
```

## 🎨 Principio Zen del Design

### **Semplicità > Complessità**
- XotBaseWizardWidget ha 360 righe ma fa UNA cosa
- Ogni metodo ha una sola responsabilità
- La complessità è nascosta dietro interfacce semplici

### **Convenzione > Configurazione**
- `getStepByName('data')` → `getDataSchema()`
- View risolta automaticamente da `GetViewByClassAction`
- Labels da LangServiceProvider senza configurazione

## 🚫 Politica di Anti-Pattern

### Cosa NON Fare
1. ** NON estendere classi Filament** (usa composition)
2. ** NON mettere logica nel Blade** (tutto nel PHP)
3. ** NON usare Log nei widget** (usare Notification)
4. ** NON rompere il type safety** (mai `(string)` su enum)

### Cosa SEMPRE Fare
1. ** Usare i Filament Components** ufficiali
2. ** Seguire il naming convention** 
3. ** Documentare le regole** (questo file!)
4. ** Testare ogni hook** (Pest feature tests)

## 🔮 Visione Futura

### Potenziali Miglioramenti

1. **Type Safety Migliorato**
   ```php
   // Usare enum per i type safety
   enum Step: string { case PRIVACY = 'privacy'; case DATA = 'data'; }
   ```

2. **Form Request Validation**
   ```php
   // Separare validation logic
   public function getRules(): array { return [/* ... */]; }
   ```

3. **Componenti Riutilizzabili**
   ```php
   // Wizard step base con pattern comuni
   abstract class BaseStepWizardWidget extends XotBaseWizardWidget
   ```

## 📚 Risorse di Approfondimento

- [Filament Docs: Wizard Component](https://filamentphp.com/docs/2.0/wizards)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [KISS Principle](https://en.wikipedia.org/wiki/KISS_principle)

---

**Ricorda**: La bellezza sta nella semplicità del design, non nella complessità dell'implementazione.