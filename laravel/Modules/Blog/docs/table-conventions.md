# Convenzioni per le Tabelle nel Modulo Blog

## Perché queste convenzioni?
Le convenzioni per le tabelle nel modulo Blog sono progettate per garantire:
- Coerenza nell'interfaccia utente
- Gestione centralizzata delle traduzioni
- Riduzione del codice duplicato
- Manutenibilità del codice

## Struttura delle Tabelle

### Colonne (`getTableColumns`)
- Ogni colonna deve avere una chiave stringa
- Le etichette devono essere gestite tramite il sistema di traduzione
- Esempio:
  ```php
  return [
      'id' => Tables\Columns\TextColumn::make('id'),
      'title' => Tables\Columns\TextColumn::make('title')
          ->sortable()
          ->searchable(),
  ];
  ```

### Azioni di Tabella
- Non implementare `getTableActions` se si usano solo le azioni standard (View, Edit, Delete)
- Non implementare `getTableBulkActions` se si usa solo DeleteBulkAction
- Le azioni personalizzate devono essere implementate solo quando necessario
- Mai usare `->label('')` - le etichette devono essere gestite via traduzione

### Traduzioni
- Tutte le etichette sono gestite dal LangServiceProvider
- I file di traduzione si trovano in `resources/lang/{locale}/tables.php`
- Struttura consigliata per le traduzioni:
  ```php
  return [
      'columns' => [
          'id' => 'ID',
          'title' => 'Titolo',
          // ...
      ],
      'actions' => [
          'edit' => 'Modifica',
          'delete' => 'Elimina',
          // ...
      ],
  ];
  ```

## Best Practices
1. Usa sempre chiavi stringa per gli array di colonne e azioni
2. Estendi le azioni base solo quando necessario
3. Centralizza le traduzioni nel LangServiceProvider
4. Mantieni la coerenza con gli altri moduli

## Collegamenti
- [Documentazione Filament Tables](../laravel/Modules/Xot/project_docs/FILAMENT-TABLES.md)
- [Sistema di Traduzione](../laravel/Modules/Lang/project_docs/README.md)
- [Convenzioni Generali](../project_docs/NAMING_CONVENTIONS.md) 
- [Documentazione Filament Tables](../laravel/Modules/Xot/docs/FILAMENT-TABLES.md)
- [Sistema di Traduzione](../laravel/Modules/Lang/docs/README.md)
- [Convenzioni Generali](../docs/NAMING_CONVENTIONS.md) 
