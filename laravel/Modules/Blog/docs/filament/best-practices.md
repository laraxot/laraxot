# Best Practices per Filament nel Modulo Blog

## Visibilità dei Metodi

### Regola Generale
Quando si estende una classe base di Filament o XotBase, è importante mantenere la stessa visibilità dei metodi della classe padre. Questo è particolarmente importante per i metodi che fanno parte dell'API pubblica di Filament.

### Esempi
- `getTableActions()` deve essere `public` quando si estende `XotBaseListRecords`
- `getFormSchema()` deve essere `public` quando si estende `XotBaseCreateRecord` o `XotBaseEditRecord`

### Errori Comuni da Evitare
```php
// ❌ SBAGLIATO
protected function getTableActions(): array
{
    return parent::getTableActions();
}

// ✅ CORRETTO
public function getTableActions(): array
{
    return parent::getTableActions();
}
```

## Collegamenti
- [Documentazione Filament](../../../Xot/project_docs/filament/FILAMENT-BEST-PRACTICES.md)
- [Convenzioni di Codice](../../../Xot/project_docs/NAMING-CONVENTIONS.md) 
