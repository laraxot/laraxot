# ERRORE CRITICO: protected $casts DEPRECATO in Laravel 11+

## 🚨 GRAVITÀ DELL'ERRORE

La proprietà `protected $casts` è **DEPRECATA** in Laravel 11+ e rappresenta un **ERRORE CRITICO** che deve essere risolto immediatamente in tutto il progetto.

## 📋 MOTIVAZIONI CRITICHE

### 1. **Deprecazione Ufficiale Laravel 11+**
- Laravel ha ufficialmente deprecato la proprietà `$casts` a favore del metodo `casts()`
- Il supporto per `$casts` sarà rimosso nelle versioni future
- Codice non future-proof e obsoleto

### 2. **Limitazioni Funzionali Gravi**
- **NON permette** l'uso di metodi statici sui casters built-in
- **Sintassi obsoleta** per casters complessi (es. enum collections)
- **Mancanza di flessibilità** per logica dinamica di casting

### 3. **Problemi di Manutenibilità**
- Sintassi string-based difficile da mantenere
- Nessun supporto per type hints avanzati
- Difficoltà nell'uso di casters personalizzati complessi

### 4. **Impatti su Qualità del Codice**
- **PHPStan**: Possibili errori di analisi statica
- **IDE Support**: Supporto limitato per autocompletamento
- **Refactoring**: Difficoltà nelle modifiche automatizzate

## ✅ SINTASSI CORRETTA (Laravel 11+)

```php
<?php

declare(strict_types=1);

namespace Modules\TechPlanner\Models;

class Client extends BaseModel
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_active' => 'boolean',
            'metadata' => 'array',
            'options' => AsEnumCollection::of(UserOption::class),
        ];
    }
}
```

## ❌ SINTASSI DEPRECATA (DA NON USARE MAI)

```php
<?php

declare(strict_types=1);

namespace Modules\TechPlanner\Models;

class Client extends BaseModel
{
    // ❌ DEPRECATO - NON USARE
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'options' => AsEnumCollection::class.':'.UserOption::class, // Sintassi obsoleta
    ];
}
```

## 🎯 VANTAGGI DEL METODO casts()

### 1. **Metodi Statici Avanzati**
```php
protected function casts(): array
{
    return [
        'options' => AsEnumCollection::of(UserOption::class),
        'settings' => AsCollection::using(SettingsCollection::class),
        'encrypted_data' => AsEncryptedCollection::using(DataCollection::class),
    ];
}
```

### 2. **Type Safety Migliorata**
- Supporto completo per PHPStan livello 9+
- Type hints espliciti per array di ritorno
- Migliore integrazione con IDE

### 3. **Flessibilità e Logica Dinamica**
```php
protected function casts(): array
{
    $baseCasts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    if ($this->hasAdvancedFeatures()) {
        $baseCasts['advanced_options'] = AsEnumCollection::of(AdvancedOption::class);
    }

    return $baseCasts;
}
```

### 4. **Sintassi Pulita per Casters Complessi**
- Niente più concatenazioni string-based
- Chiamate dirette ai metodi statici
- Codice più leggibile e manutenibile

## 📊 AUDIT DEL PROGETTO

### File con protected $casts Trovati:
```
- Modules/Notify/app/Models/NotificationTemplate.php
- Modules/Notify/app/Models/MailTemplateVersion.php
- Modules/Notify/app/Models/MailTemplateLog.php
- Modules/Xot/app/Models/InformationSchemaTable.php
- Modules/Lang/app/Models/TranslationFile.php
- Modules/Geo/app/Models/BaseMorphPivot.php
- Modules/Geo/app/Models/Address.php
- Modules/Geo/app/Models/BaseModel.php
- Modules/Geo/app/Models/Location.php
- Modules/Geo/app/Models/BasePivot.php
- Modules/FormBuilder/app/Models/FormSubmission.php
- Modules/FormBuilder/app/Models/FormTemplate.php
- Modules/FormBuilder/app/Models/FormField.php
- Modules/Geo/app/Models/Place.php
- Modules/Chart/app/Models/Chart.php
- Themes/Two/Main_files/filament-peek-demo/app/Models/Menu.php
- Themes/Two/Main_files/filament-peek-demo/app/Models/Post.php
- Themes/Two/Main_files/filament-peek-demo/app/Models/User.php
```

**TOTALE: 20+ file da correggere**

## 🚀 PIANO DI CORREZIONE

### Fase 1: Audit Completo
1. Identificare tutti i file con `protected $casts`
2. Analizzare i cast utilizzati in ogni file
3. Verificare dipendenze e complessità

### Fase 2: Conversione Sistematica
1. Convertire ogni `protected $casts` in metodo `casts()`
2. Aggiornare sintassi per casters complessi
3. Aggiungere PHPDoc appropriati

### Fase 3: Testing e Validazione
1. Eseguire test completi per ogni modello modificato
2. Verificare funzionamento dei cast
3. Validazione PHPStan livello 9+

### Fase 4: Documentazione
1. Aggiornare regole di coding
2. Aggiornare template per nuovi modelli
3. Documentare best practices

## 📚 REGOLE AGGIORNATE

### Regola Assoluta per Nuovi Modelli
```php
/**
 * SEMPRE usare il metodo casts() invece della proprietà $casts
 * 
 * @return array<string, string>
 */
protected function casts(): array
{
    return [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // Altri cast...
    ];
}
```

### Checklist per Code Review
- [ ] Nessun uso di `protected $casts`
- [ ] Metodo `casts()` implementato correttamente
- [ ] PHPDoc completo con tipo di ritorno
- [ ] Sintassi moderna per casters complessi
- [ ] Test di funzionamento dei cast

## 🔗 Riferimenti

- [Laravel News: Model Casts moving to methods](https://laravel-news.com/model-casts)
- [Laravel 12.x Docs: Eloquent Mutators & Casting](https://laravel.com/docs/12.x/eloquent-mutators)
- [GitHub PR #47237: Implement casts() method](https://github.com/laravel/framework/pull/47237)

## 📝 Note Implementative

### Compatibilità
- Il metodo `casts()` è compatibile con Laravel 10 e 11+
- I due approcci possono coesistere temporaneamente
- Le chiavi del metodo hanno precedenza sulla proprietà

### Migrazione Graduale
1. Convertire prima i modelli più critici
2. Testare ogni conversione individualmente
3. Mantenere backward compatibility durante la transizione

---

**PRIORITÀ: CRITICA**  
**IMPATTO: ALTO**  
**EFFORT: MEDIO**  

*Ultimo aggiornamento: Agosto 2025*