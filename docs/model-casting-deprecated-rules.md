# ERRORE ARCHITETTURALE CRITICO: Proprietà $casts Deprecata

## Problema Identificato

Durante l'audit del codice è stato identificato un **errore architetturale gravissimo**: l'uso della proprietà deprecata `protected $casts = [...]` in **20 file** del progetto.

## Gravità del Problema

### Perché è Critico

1. **Deprecazione Laravel 11**: La proprietà `$casts` è ufficialmente deprecata
2. **Limitazioni Funzionali**: Impedisce l'uso di metodi statici sui caster moderni
3. **Debito Tecnico**: Codice legacy non conforme agli standard attuali
4. **Performance**: Il metodo `casts()` è più efficiente
5. **Manutenibilità**: Difficoltà nell'evoluzione del codice

### Impatto sul Progetto

- **20 file** identificati con questo errore
- **Modelli base** compromessi (BaseModel, BasePivot)
- **Cascata di errori** su tutti i modelli che estendono le classi base
- **Incompatibilità** con le nuove funzionalità di Laravel 11+

## Regola Assoluta

### ❌ VIETATO ASSOLUTO

```php
class User extends BaseModel
{
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

### ✅ OBBLIGATORIO

```php
class User extends BaseModel
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

## File da Refactoring (Priorità)

### CRITICO (Fase 1)
- `Modules/Geo/app/Models/BaseModel.php` - **INFLUENZA TUTTI I MODELLI**
- `Modules/Geo/app/Models/BasePivot.php` - **INFLUENZA TUTTI I PIVOT**
- `Modules/Geo/app/Models/BaseMorphPivot.php`

### ALTO (Fase 2)
- `Themes/Two/Main_files/filament-peek-demo/app/Models/User.php`
- `Modules/Notify/app/Models/NotificationTemplate.php`
- `Modules/Xot/app/Models/InformationSchemaTable.php`

### MEDIO (Fase 3)
- Modelli specifici dei moduli (FormBuilder, Lang, Chart, ecc.)

### BASSO (Fase 4)
- File di documentazione e conflitti

## Vantaggi del Refactoring

### Funzionalità Moderne

```php
// Possibile SOLO con casts()
protected function casts(): array
{
    return [
        'options' => AsEnumCollection::of(UserOption::class),
        'settings' => AsCollection::using(SettingsCollection::class),
        'metadata' => AsEncryptedCollection::using(MetadataCollection::class),
    ];
}
```

### Logica Dinamica

```php
protected function casts(): array
{
    $casts = parent::casts();
    
    if ($this->hasAttribute('custom_data')) {
        $casts['custom_data'] = 'array';
    }
    
    return $casts;
}
```

## Implementazione

### Pattern Standard

```php
/**
 * Get the attributes that should be cast.
 *
 * @return array<string, string>
 */
protected function casts(): array
{
    return array_merge(parent::casts(), [
        'field_name' => 'cast_type',
    ]);
}
```

### Documentazione PHPDoc

```php
/**
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $is_active
 */
class MyModel extends BaseModel
{
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
```

## Validazione

### PHPStan
- Eseguire PHPStan livello 9+ dopo ogni refactoring
- Verificare tipizzazione corretta

### Testing
- Test funzionali per verificare che i cast funzionino
- Regressione testing per compatibilità

## Status Refactoring

- [ ] **FASE 1**: BaseModel e BasePivot (CRITICO)
- [ ] **FASE 2**: Modelli core (ALTO)
- [ ] **FASE 3**: Modelli modulo (MEDIO)
- [ ] **FASE 4**: Cleanup documentazione (BASSO)

## Collegamenti

- [Modules/Xot/docs/model-casting-rules.md](../Modules/Xot/docs/model-casting-rules.md) - Documentazione completa
- [Laravel 11 Casting Documentation](https://laravel.com/docs/11.x/eloquent-mutators#attribute-casting)
- [Laravel News: Model Casts Moving to Methods](https://laravel-news.com/model-casts)

---

**QUESTA È UNA REGOLA ASSOLUTA E INVIOLABILE**

*Ultimo aggiornamento: agosto 2025*