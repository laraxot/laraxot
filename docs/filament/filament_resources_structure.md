# Struttura delle Filament Resources

## XotBaseResource e XotBaseListRecords

### IMPORTANTE: Gestione delle Tabelle
Quando si utilizzano le classi base `XotBaseResource` e `XotBaseListRecords`, ci sono delle regole FONDAMENTALI da seguire:

❌ **ERRORI GRAVI DA EVITARE**:
1. NON implementare il metodo `table()` nella Resource
2. NON implementare il metodo `table()` nella ListPage che estende `XotBaseListRecords`
3. NON duplicare la definizione delle colonne in più luoghi

✅ **IMPLEMENTAZIONE CORRETTA**:
1. Utilizzare ESCLUSIVAMENTE il metodo `getListTableColumns()` nella ListPage
2. Definire le colonne come array di `TextColumn` o altri tipi di colonne Filament
3. Rispettare la struttura ereditata da `XotBaseListRecords`

### Esempio di Implementazione Corretta

```php
// Nel file della Resource (es: ClientResource.php)
class ClientResource extends XotBaseResource
{
    protected static ?string $model = Client::class;
    
    public static function getFormSchema(): array
    {
        // definizione del form
    }
}

// Nella Page collegata (es: ListClients.php)
class ListClients extends XotBaseListRecords
{
    protected static string $resource = ClientResource::class;

    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->sortable()
                ->searchable(),
            TextColumn::make('email')
                ->sortable()
                ->searchable(),
            // altre colonne...
        ];
    }
    
    // Altri metodi per filtri, azioni, ecc.
    protected function getTableFilters(): array
    {
        return [
            // definizione dei filtri
        ];
    }
}
```

### Motivazione
La struttura con `XotBaseListRecords` è stata progettata per:
1. Standardizzare la gestione delle tabelle
2. Evitare duplicazione di codice
3. Garantire coerenza nell'implementazione
4. Facilitare la manutenzione
5. Permettere personalizzazioni controllate

### Struttura Completa dei File

```
Modules/NomeModulo/
└── app/
    └── Filament/
        └── Resources/
            ├── NomeResource.php                    # Solo form e configurazione
            └── NomeResource/
                └── Pages/
                    ├── ListNomes.php              # Solo getListTableColumns()
                    ├── CreateNome.php             # Logica creazione
                    └── EditNome.php               # Logica modifica
```

### Best Practices per le Tabelle

1. **Definizione Colonne**
   - Usare sempre `getListTableColumns()`
   - Definire le colonne in modo chiaro e organizzato
   - Aggiungere commenti per colonne complesse

2. **Personalizzazione**
   - Estendere i metodi esistenti di `XotBaseListRecords`
   - Non sovrascrivere la logica base della tabella
   - Utilizzare i metodi predefiniti per filtri e azioni

3. **Manutenibilità**
   - Mantenere le definizioni delle colonne in un unico posto
   - Documentare eventuali personalizzazioni complesse
   - Seguire le convenzioni di naming

4. **Performance**
   - Ottimizzare le query delle colonne
   - Utilizzare lazy loading dove appropriato
   - Evitare computazioni pesanti nelle colonne

## Struttura dei Resources in Filament

### Organizzazione dei File

Un Resource in Filament è organizzato nella seguente struttura:
```
Modules/NomeModulo/
└── app/
    └── Filament/
        └── Resources/
            ├── NomeResource.php              # Definizione principale del resource
            └── NomeResource/                 # Directory per i componenti del resource
                └── Pages/                    # Directory per le pagine
                    ├── CreateNome.php        # Pagina di creazione
                    ├── EditNome.php          # Pagina di modifica
                    └── ListNomes.php         # Pagina di lista/tabella
```

### Responsabilità dei File

1. **NomeResource.php**
   - Definizione del modello associato
   - Schema del form (campi e layout)
   - Configurazione generale del resource
   - Definizione delle relazioni
   - NON deve contenere la definizione della tabella

2. **Pages/ListNomes.php**
   - Definizione della struttura della tabella
   - Configurazione delle colonne
   - Gestione dei filtri
   - Azioni bulk e singole
   - Personalizzazione della vista lista

3. **Pages/CreateNome.php**
   - Logica di creazione
   - Validazione specifica
   - Redirect post-creazione

4. **Pages/EditNome.php**
   - Logica di modifica
   - Validazione specifica
   - Gestione dello stato

### Errori Comuni da Evitare

❌ **NON FARE**:
- Definire la tabella nel file principale del resource
- Mischiare la logica delle pagine con quella del resource
- Duplicare codice tra le pagine

✅ **FARE**:
- Mantenere la separazione delle responsabilità
- Utilizzare i file appropriati per ogni funzionalità
- Seguire la struttura standard di Filament

### Best Practices

1. **Organizzazione del Codice**
   - Ogni componente nel suo file dedicato
   - Riutilizzo del codice attraverso trait o classi base
   - Documentazione chiara delle personalizzazioni

2. **Manutenibilità**
   - Separazione chiara delle responsabilità
   - Codice DRY (Don't Repeat Yourself)
   - Utilizzo di costanti per valori ripetuti

3. **Performance**
   - Caricamento lazy delle relazioni
   - Ottimizzazione delle query
   - Cache quando appropriato
