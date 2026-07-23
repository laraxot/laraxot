# Ereditarietà delle Pagine Filament

## Regole di Base per l'Ereditarietà

### XotBasePage
Tutte le pagine Filament nel sistema DEVONO estendere `XotBasePage` invece di `Filament\Pages\Page`.

```php
use Modules\Xot\Filament\Pages\XotBasePage;

class MiaPagina extends XotBasePage
{
    // ...
}
```

### Proprietà Gestite da XotBasePage

Le seguenti proprietà NON devono essere definite nelle classi che estendono XotBasePage poiché sono già gestite automaticamente:

```php
// NON definire queste proprietà
protected static ?string $navigationIcon;     // Gestita da XotBasePage
protected static ?string $navigationGroup;    // Gestita da XotBasePage
protected static string $view;                // Gestita da XotBasePage
```

### Come Funziona

1. **Icone di Navigazione**
   - Le icone sono gestite attraverso i file di traduzione
   - Vengono caricate automaticamente da XotBasePage

2. **Gruppi di Navigazione**
   - I gruppi sono definiti nei file di traduzione
   - XotBasePage gestisce l'assegnazione automatica

3. **View**
   - Il template della vista viene determinato automaticamente
   - Segue convenzioni di naming basate sul nome della classe

## Best Practices

1. **File di Traduzione**
   - Definire le configurazioni di navigazione nei file di traduzione
   - Usare la struttura standard per le traduzioni

2. **Convenzioni di Naming**
   - Seguire le convenzioni di naming per le classi
   - Mantenere coerenza tra nomi di file e classi

3. **Personalizzazione**
   - Per casi speciali, usare i metodi di override forniti da XotBasePage
   - Non definire direttamente le proprietà protette

## Esempio Completo

```php
use Modules\Xot\Filament\Pages\XotBasePage;

class ArtisanCommandsManager extends XotBasePage
{
    protected function getHeaderActions(): array
    {
        return [
            // Azioni personalizzate
        ];
    }

    protected function getFooterActions(): array
    {
        return [
            // Azioni del footer
        ];
    }
}
```

## Note Importanti

1. La gestione della navigazione è completamente automatizzata
2. Le configurazioni vanno fatte nei file di traduzione
3. L'override delle proprietà base può causare comportamenti inattesi
4. Usare i metodi forniti da XotBasePage per personalizzazioni
