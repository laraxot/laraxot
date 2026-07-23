# Architettura Modelli - Modulo Employee

## Panoramica

Il modulo Employee segue l'architettura Laraxot per i modelli, che garantisce isolamento, personalizzazione e coerenza attraverso una gerarchia di ereditarietà ben definita.

## Gerarchia di Ereditarietà

### Struttura Completa
```
Illuminate\Database\Eloquent\Model (Laravel Core)
    ↓
Modules\Xot\Models\XotBaseModel (Funzionalità comuni Xot)
    ↓
Modules\Employee\Models\BaseModel (Personalizzazioni Employee)
    ↓
Modules\Employee\Models\{ModelName} (Modelli concreti)
```

### Modelli Implementati

#### 1. **BaseModel** - Classe Base Astratta
- **Estende**: `Modules\Xot\Models\XotBaseModel`
- **Scopo**: Fornisce funzionalità comuni per tutti i modelli del modulo Employee
- **Personalizzazioni**: Metodi e comportamenti specifici del modulo HR

#### 2. **Employee** - Modello Principale
- **Estende**: `Modules\Employee\Models\BaseModel`
- **Scopo**: Gestisce le informazioni complete dei dipendenti
- **Caratteristiche**:
  - Dati personali, di contatto e lavorativi strutturati in array JSON
  - Gestione gerarchica (manager/subordinati)
  - Relazioni con dipartimenti, posizioni e documenti
  - Sistema di stati (attivo, inattivo, sospeso, licenziato)

#### 3. **WorkHour** - Gestione Ore Lavorate
- **Estende**: `Modules\Employee\Models\BaseModel`
- **Scopo**: Traccia presenze, timbrature e ore lavorate
- **Caratteristiche**:
  - Tipi di timbratura (entrata, uscita, pausa inizio/fine)
  - Geolocalizzazione delle timbrature
  - Sistema di approvazione e validazione
  - Calcolo automatico delle ore lavorate

#### 4. **Department** - Gestione Dipartimenti
- **Estende**: `Modules\Employee\Models\BaseModel`
- **Scopo**: Organizza la struttura aziendale
- **Caratteristiche**:
  - Nome e descrizione del dipartimento
  - Manager assegnato
  - Sistema di stati (attivo/inattivo)
  - Relazione con dipendenti

#### 5. **Position** - Gestione Posizioni Lavorative
- **Estende**: `Modules\Employee\Models\BaseModel`
- **Scopo**: Definisce ruoli e livelli organizzativi
- **Caratteristiche**:
  - Titolo e descrizione della posizione
  - Livello gerarchico (entry, junior, senior, lead, manager, director, executive)
  - Sistema di stati (attivo/inattivo)
  - Relazione con dipendenti

## Motivazioni Architetturali

1. **Isolamento Modulare**: Ogni modulo può personalizzare il proprio BaseModel
2. **Override Sicuro**: Le modifiche al BaseModel del modulo non impattano altri moduli
3. **Compliance PHPStan**: L'architettura garantisce type safety e compliance livello 10
4. **Manutenibilità**: Codice più pulito e facile da mantenere
5. **Estensibilità**: Facile aggiungere nuovi modelli seguendo lo stesso pattern

## Best Practices Implementate

### 1. **Tipizzazione Rigorosa**
- Tutti i metodi hanno tipi di ritorno espliciti
- Tutti i parametri hanno tipi dichiarati
- Utilizzo di generics per le relazioni (`Collection<int, Model>`)
- PHPDoc completo per tutte le proprietà

### 2. **Gestione Casting**
- Utilizzo del metodo `casts()` invece della proprietà `$casts` (deprecata)
- Cast appropriati per array JSON, datetime e decimal
- Gestione corretta dei tipi nullable

### 3. **Relazioni Tipizzate**
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Employee>
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/**
 * @return \Illuminate\Database\Eloquent\Relations\HasMany<WorkHour>
 */
public function workHours(): HasMany
{
    return $this->hasMany(WorkHour::class);
}
```

### 4. **Scope e Metodi di Utilità**
- Scope per filtrare per stato, dipendente, data
- Metodi per calcolare ore lavorate e status corrente
- Validazione delle sequenze di timbratura
- Metodi per formattazione date e orari

## Factory e Testing

### Factory Implementate
- **EmployeeFactory**: Genera dati realistici per dipendenti
- **WorkHourFactory**: Genera timbrature e ore lavorate
- **DepartmentFactory**: Genera dipartimenti aziendali
- **PositionFactory**: Genera posizioni lavorative

### Test Implementati
- **BaseModelTest**: Verifica ereditarietà e funzionalità base
- **EmployeeTest**: Test completi per il modello Employee
- **WorkHourTest**: Test per gestione ore e timbrature

## Anti-Pattern Evitati

### ❌ Estensione Diretta di XotBaseModel
```php
// SBAGLIATO - Non estendere mai direttamente XotBaseModel
class Employee extends XotBaseModel
{
    // ERRORE: perde isolamento modulare
}
```

### ❌ Estensione Diretta di Model
```php
// SBAGLIATO - Non estendere mai direttamente Model
class Employee extends Model
{
    // ERRORE: perde funzionalità Xot e isolamento
}
```

### ❌ Utilizzo di Proprietà $casts Deprecata
```php
// SBAGLIATO - Non usare la proprietà $casts
protected $casts = [
    'created_at' => 'datetime',
];
```

## Compliance PHPStan

Tutti i modelli sono conformi a PHPStan livello 10:
- ✅ Tipizzazione esplicita per tutti i metodi
- ✅ PHPDoc completo per tutte le proprietà
- ✅ Generics corretti per le relazioni
- ✅ Gestione appropriata dei tipi nullable
- ✅ Nessun uso di `mixed` non necessario

## Collegamenti alla Documentazione

- [README Modulo Employee](README.md)
- [Regole Estensione XotBase](xotbase_extension_rules.md)
- [Documentazione Root](../../../docs/model_inheritance_best_practices.md)
- [Regole Laraxot](../../../docs/laraxot.md)

## Ultimo Aggiornamento
2025-01-06
