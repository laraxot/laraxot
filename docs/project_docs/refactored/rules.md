# Naming Conventions for Actions

- **Action Naming**: Actions should have descriptive names that clearly indicate their functionality. For example, an action that retrieves coordinates from a full address should be named `GetCoordinatesDataByFullAddressAction` instead of `PopulateCoordinatesAction`.

- **Strong Typing**: Ensure all methods in actions are strongly typed, specifying parameter types and return types explicitly.

- **Error Handling**: Implement persistent notifications for errors to ensure users are informed of any issues.

These guidelines help maintain clarity and robustness in code design and implementation.

# Filament Resources Rules

- **Verifica Completa**: Quando si lavora su un tipo specifico di classe (es. List, Create, Edit):
  - Eseguire una ricerca completa nel modulo per trovare TUTTE le classi di quel tipo
  - Non fermarsi alla prima classe trovata
  - Verificare ogni classe trovata per assicurarsi che rispetti le regole
  - Esempio: cercando classi List, usare una regex come `extends\s+(List|XotBaseList)`

- **Base Classes**: Tutte le classi Filament devono estendere le corrispondenti classi base del modulo Xot con il prefisso XotBase. Per esempio:
  - `CreateRecord` → `XotBaseCreateRecord`
  - `ListRecords` → `XotBaseListRecords`
  - `EditRecord` → `XotBaseEditRecord`
  - Le classi base si trovano nel namespace `Modules\Xot\Filament\Resources\Pages`
  - Questo garantisce consistenza e funzionalità estese in tutta l'applicazione
  - Le classi XotBase forniscono funzionalità aggiuntive e personalizzazioni specifiche per il progetto

- **List Classes**: Le classi che estendono `XotBaseListRecords` devono implementare il metodo `getListTableColumns()` che definisce le colonne da visualizzare nella tabella:
  - Le chiavi dell'array devono essere stringhe che corrispondono ai nomi dei campi
  - I campi devono esistere nel modello/database (verificare migration e model)
  - Non è possibile mostrare campi non definiti nel database

Esempio di implementazione corretta per Create:
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateDevice extends XotBaseCreateRecord
{
    protected static string $resource = DeviceResource::class;
}
```

Esempio di implementazione corretta per List:
```php
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListDevices extends XotBaseListRecords
{
    protected static string $resource = DeviceResource::class;

    public function getListTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable(),
            'name' => TextColumn::make('name')
                ->searchable()
                ->sortable(),
            'type' => TextColumn::make('type')
                ->searchable(),
            // Assicurarsi che ogni campo esista nel database
        ];
    }
}
```

# Translation and Icons Rules

- **Struttura Traduzioni**:
  - Ogni risorsa deve avere il suo file di traduzione dedicato
  - I file devono essere posizionati in `lang/it/` del modulo
  - Struttura base delle traduzioni:
    ```php
    return [
        'navigation' => [
            'name' => 'Nome Risorsa',
            'group' => 'Nome Gruppo',
            'label' => 'Etichetta Menu',
            'icon' => '[modulo]-[icona]',
            'sort' => 10, // Ordine nel menu
            'badge' => [
                'color' => 'success',
                'label' => 'Stato',
            ],
        ],
        'fields' => [...],
        'actions' => [...],
        'messages' => [...],
    ];
    ```

- **Verifica Icone**:
  - Prima di creare nuove icone, verificare quelle esistenti
  - Controllare tutti i file di traduzione per identificare le icone necessarie
  - Mantenere coerenza tra il nome dell'icona e il suo utilizzo

# SVG Icons Rules

- **Posizione e Struttura**:
  - Gli SVG devono essere posizionati nella cartella `resources/svg` del modulo
  - Utilizzare viewBox="0 0 24 24" per mantenere consistenza
  - Includere gli attributi base:
    ```svg
    <svg xmlns="http://www.w3.org/2000/svg" 
         viewBox="0 0 24 24" 
         fill="none" 
         stroke="currentColor" 
         stroke-width="2" 
         stroke-linecap="round" 
         stroke-linejoin="round">
    ```

- **Naming Convention**:
  - Nome file: `nome-icona.svg` (tutto minuscolo, parole separate da trattini)
  - Il nome deve essere significativo e descrivere chiaramente l'icona
  - NON includere il nome del modulo nel nome del file
  - Esempio: `call.svg`, `document.svg`, `user-profile.svg`

- **Richiamo delle Icone**:
  - Le icone vengono richiamate automaticamente con il prefisso del modulo
  - Formula: `[modulo-in-minuscolo]-[nome-file]`
  - Esempio: per `call.svg` nel modulo TechPlanner → `techplanner-call`

- **Design Guidelines**:
  - Le icone devono essere animate quando possibile
  - Il design deve essere significativo e auto-esplicativo
  - Utilizzare animazioni che migliorano la comprensione dell'azione
  - Mantenere uno stile coerente all'interno del modulo
  - Tecniche di animazione comuni:
    ```svg
    <!-- Animazione progressiva di un tracciato -->
    <animate
      attributeName="stroke-dasharray"
      from="0,100"
      to="100,100"
      dur="1s"
      begin="0s"
      fill="freeze"
    />

    <!-- Pulsazione di un elemento -->
    <animate
      attributeName="r"
      values="0;2;1.5;2"
      dur="1.5s"
      repeatCount="indefinite"
    />

    <!-- Fade in/out -->
    <animate
      attributeName="opacity"
      values="0;1;0"
      dur="2s"
      repeatCount="indefinite"
    />
    ```

- **Esempi di Icone Comuni**:
  - Utenti/Profili: Utilizzare una figura stilizzata con dettagli del ruolo
  - Documenti: Base documento con linee di testo animate
  - Azioni: Simboli intuitivi con feedback animato
  - Luoghi: Edifici o pin con elementi distintivi
  - Dispositivi: Forme riconoscibili con elementi interattivi

Esempio di implementazione:
```php
// Nel file di traduzione del modulo
'navigation' => [
    'icon' => 'techplanner-call', // Richiama call.svg dalla cartella resources/svg
],
```
