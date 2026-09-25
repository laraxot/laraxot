# Correzioni PHPStan - Modulo Blog

Questo documento traccia gli errori PHPStan identificati nel modulo Blog e le relative soluzioni implementate.

## Errori Risolti - Gennaio 2025

### 1. Return Type Compatibility - ListArticles

**Problema**: Il metodo `getHeaderActions()` in `ListArticles.php` restituiva `array<int, Action>` invece di `array<string, Action>`.

**Errore PHPStan**:

```text
Return type (array<string, Filament\Actions\Action>) of method should be compatible with return type (array<int, Filament\Actions\Action>)
```

**Soluzione Implementata**:

1. Modificato il tipo di ritorno da `array<int>` a `array<string>` nella documentazione PHPDoc
2. Convertito l'array numerico in array associativo con chiavi stringa
3. Rispettato il pattern Laraxot per i metodi Filament

```php
/**
 * @return array<string, \Filament\Actions\Action>
 */
protected function getHeaderActions(): array
{
    return [
        'locale_switcher' => Actions\LocaleSwitcher::make(),
        'create' => Actions\CreateAction::make(),
        'import' => Actions\Action::make('import')
            // ... resto della configurazione
    ];
}
```

**Regola Fondamentale**: Tutti i metodi Filament devono restituire array associativi con chiavi stringa, mai array numerici.

### 2. Gestione dei Valori Null (Precedenti)

**Problema**: Gestione non corretta dei valori nullable e mancanza di controlli espliciti.

**Soluzione**:
1. Aggiungere controlli espliciti per i valori null
2. Utilizzare operatori null-safe dove appropriato
3. Documentare correttamente i tipi nullable
4. Implementare validazioni con Webmozart Assert

Esempio:
```php
/**
 * @param string|null $locale
 * @return string
 */
public function getLocale(?string $locale = null): string
{
    Assert::nullOrString($locale);
    return $locale ?? config('app.locale');
}
```

### 2. Type Union e Intersection

**Problema**: Uso non corretto dei tipi union e mancanza di documentazione.

**Soluzione**:
1. Documentare correttamente i tipi union
2. Implementare controlli appropriati
3. Utilizzare type assertions
4. Gestire tutti i possibili tipi

Esempio:
```php
/**
 * @param array|string|int|null $value
 * @return string
 */
public function formatValue(array|string|int|null $value): string
{
    if (is_array($value)) {
        return implode(', ', $value);
    }
    
    if (is_null($value)) {
        return '';
    }
    
    return (string) $value;
}
```

### 3. Chiamate a Metodi su Tipi Union

**Problema**: Chiamate non sicure a metodi su tipi union.

**Soluzione**:
1. Implementare controlli di tipo
2. Utilizzare type assertions
3. Documentare i possibili tipi
4. Gestire tutti i casi possibili

Esempio:
```php
/**
 * @param \Illuminate\Database\Eloquent\Model|\ArrayAccess|null $model
 * @return bool
 */
public function validateModel(\Illuminate\Database\Eloquent\Model|\ArrayAccess|null $model): bool
{
    if ($model === null) {
        return false;
    }
    
    Assert::isInstanceOfAny($model, [
        \Illuminate\Database\Eloquent\Model::class,
        \ArrayAccess::class,
    ]);
    
    return true;
}
```

## Principi Applicati

1. **Gestione Null**
   - Controlli espliciti per null
   - Uso di operatori null-safe
   - Documentazione dei tipi nullable
   - Validazioni con Assert

2. **Type Safety**
   - Controlli di tipo espliciti
   - Type assertions
   - Gestione dei tipi union
   - Validazioni runtime

3. **Documentazione**
   - DocBlocks completi
   - Specifiche dei tipi
   - Esempi di utilizzo
   - Casi edge documentati

## Prossimi Passi

1. Implementare le correzioni proposte
2. Aggiungere test per i casi edge
3. Verificare la compatibilità con i contratti
4. Mantenere la documentazione aggiornata
5. Eseguire PHPStan a livello 3 regolarmente 