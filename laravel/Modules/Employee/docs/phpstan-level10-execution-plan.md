# PHPStan Level 10 - Employee Module Execution Plan

## 🎯 **Obiettivo**
Raggiungere PHPStan Level 10 compliance sul modulo Employee senza modificare `phpstan.neon`, risolvendo tutti gli errori attraverso miglioramenti del codice.

## 📊 **Analisi Errori Esistenti**
**Baseline**: 257 errori identificati e categorizzati

### **Categoria 1: Widget Errors (85 errori) - PRIORITÀ ALTA**
- `WorkHourStatsWidget.php`: 22 errori (costanti obsolete → enum)
- `AttendanceOverviewWidget.php`: 35 errori (mixed types, query builder)
- Altri widget: 28 errori totali

### **Categoria 2: Resource Pages (45 errori) - PRIORITÀ ALTA**
- `EditWorkHour.php`: 15 errori (return types, mixed values)
- `CreateWorkHour.php`: 5 errori (enum usage)
- Altre resource pages: 25 errori

### **Categoria 3: Models (62 errori) - PRIORITÀ MEDIA**
- Riferimenti costanti obsolete
- Problemi di covarianza relazioni Eloquent
- Accesso proprietà su mixed types

### **Categoria 4: Database (35 errori) - PRIORITÀ MEDIA**
- Migration method issues (`comment()` non esiste)
- Seeder type problems
- Foreign key definition problems

### **Categoria 5: Altri Componenti (30 errori) - PRIORITÀ BASSA**
- Controller return types
- Policy method problems
- Factory references

## 🚀 **Piano di Esecuzione**

### **FASE 1: Preparazione e Setup**
1. **Backup documentazione** esistente
2. **Consolidare** documentazione frammentata
3. **Eseguire PHPStan baseline** per confermare errori attuali

### **FASE 2: Widget Fixes (2-4 ore)**
1. **WorkHourStatsWidget**: Sostituire costanti con enum, aggiungere type hints
2. **AttendanceOverviewWidget**: Risolvere mixed types, aggiungere query() calls
3. **Altri widget**: Aggiornare usage enum invece di costanti

### **FASE 3: Resource Pages (1-2 ore)**
1. **EditWorkHour.php**: Return type declarations, gestione mixed values
2. **CreateWorkHour.php**: Verificare enum usage corretto
3. **Altre resource pages**: Type hints appropriati

### **FASE 4: Models Consistency (2-3 ore)**
1. **Backward-compatible constants** in WorkHour model
2. **Fix relazioni**: Rimuovere PHPDoc problematici
3. **Type hints**: Aggiungere a tutti i metodi model

### **FASE 5: Database & Seeders (1-2 ore)**
1. **Migration**: Rinominare `time_entries` → `work_hours` se necessario
2. **Seeder enum usage**: Sostituire costanti con enum values
3. **Rimuovere method calls** invalid dalle migration

### **FASE 6: Validation Finale (1-2 ore)**
1. **PHPStan incrementale** dopo ogni categoria
2. **Test funzionalità** dopo ogni major change
3. **Documentare fixes** appropriati

## 🛠️ **Approccio Tecnico**

### **Constants → Enums**
```php
// PRIMA (errori PHPStan)
$query->where('type', WorkHour::TYPE_CLOCK_IN);

// DOPO (PHPStan compliant)
$query->where('type', WorkHourTypeEnum::CLOCK_IN->value);

// CON BACKWARD COMPATIBILITY
class WorkHour extends BaseModel
{
    // Deprecated constants per backward compatibility
    public const TYPE_CLOCK_IN = 'clock_in';
    public const TYPE_CLOCK_OUT = 'clock_out';
    
    protected function casts(): array
    {
        return [
            'type' => WorkHourTypeEnum::class,
            'status' => WorkHourStatusEnum::class,
        ];
    }
}
```

### **Mixed Types Resolution**
```php
// PRIMA (errore PHPStan)
$value = someFunction(); // returns mixed
$object->property = $value;

// DOPO (compliant)
$value = someFunction();
if (is_string($value)) {
    $object->property = $value;
} else {
    $object->property = ''; // default value
}
```

### **Return Types**
```php
// PRIMA
public function getRedirectUrl()
{
    return $this->getResource()::getUrl('index');
}

// DOPO
public function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
```

## 📋 **Files Priority Order**

### **Immediate Fix (Fase 2)**
1. `app/Filament/Resources/WorkHourResource/Widgets/WorkHourStatsWidget.php`
2. `app/Filament/Widgets/AttendanceOverviewWidget.php`
3. `app/Filament/Resources/WorkHourResource/Pages/EditWorkHour.php`

### **High Priority (Fase 3-4)**
4. `app/Models/WorkHour.php` (backward compatibility constants)
5. `database/seeders/WorkHourSeeder.php` (enum update)
6. Altri widget files con constant references

### **Medium Priority (Fase 5)**
7. Migration files con `comment()` method calls
8. Files referencing `time_entries` table

## ✅ **Criteri di Successo**
- Zero errori PHPStan Level 10
- Funzionalità esistente mantenuta
- Backward compatibility garantita
- Documentazione completa aggiornata
- Schema database consistency

## ⏱️ **Timeline Stimato**
- **Fase 1**: 1-2 ore (setup e documentazione)
- **Fase 2**: 2-4 ore (widget fixes)
- **Fase 3**: 1-2 ore (resource pages)
- **Fase 4**: 2-3 ore (models)
- **Fase 5**: 1-2 ore (database)
- **Fase 6**: 1-2 ore (validation)

**TOTALE: 8-15 ore** per PHPStan Level 10 compliance completa

## 🚨 **Rischi e Mitigazioni**
- **Alto Rischio**: Database migration table rename
- **Mitigazione**: Test su copy del database, rollback plan
- **Medio Rischio**: Breaking changes backward compatibility
- **Mitigazione**: Mantenere constants deprecate, comprehensive testing

---

*Documento creato: 2025-01-06*  
*Stato: Pronto per esecuzione*  
*Target: PHPStan Level 10 compliance senza phpstan.neon modifications*
