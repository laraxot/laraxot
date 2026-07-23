# Errori Comuni: Widget Filament

## Riferimenti
- [Best Practices Widget](filament-widget-best-practices.md)
- [Processo Gestione Errori](error-handling-process.md)

## Errori di Implementazione

### 1. Metodi Statici
```php
// ❌ Errore: Tentativo di rendere statico un metodo non statico della classe base
public static function getFormSchema(): array
{
    // ...
}

// ✅ Corretto: Mantenere la stessa visibilità del metodo nella classe base
public function getFormSchema(): array
{
    // ...
}
```

#### Cause Comuni
- Non verificare la definizione del metodo nella classe base
- Assumere che i metodi possano essere resi statici
- Non seguire il principio di Liskov Substitution

#### Soluzione
1. Verificare sempre la definizione del metodo nella classe base
2. Mantenere la stessa visibilità del metodo
3. Documentare le dipendenze
4. Implementare test di integrazione

### 2. Inizializzazione Proprietà
```php
// ❌ Errore: Accesso a proprietà statica non inizializzata
protected static string $view;

// ✅ Corretto: Inizializzare la proprietà
protected static string $view = 'user::widgets.auth.login-widget';
```

#### Cause Comuni
- Non inizializzare proprietà statiche
- Accedere a proprietà prima dell'inizializzazione
- Non seguire le best practices di Filament

#### Soluzione
1. Inizializzare sempre le proprietà statiche
2. Verificare l'ordine di inizializzazione
3. Documentare le dipendenze
4. Implementare test di unità

### 3. Namespace delle View
```php
// ❌ Errore: Namespace non corretto per le view dei widget
protected static string $view = 'user::widgets.auth.login-widget';

// ✅ Corretto: Seguire il pattern {module}::filament.widgets.{type}
protected static string $view = 'user::filament.widgets.auth.login';
```

#### Cause Comuni
- Non seguire la convenzione dei namespace di Filament
- Usare il pattern sbagliato per i percorsi delle view
- Non rispettare la struttura delle cartelle del modulo

#### Soluzione
1. Seguire sempre il pattern {module}::filament.widgets.{type}
2. Verificare che la view esista nel percorso corretto
3. Mantenere la coerenza con la struttura del modulo
4. Implementare test per verificare l'esistenza delle view

## Best Practices

### 1. Verifica Classe Base
```php
// Prima di estendere una classe
class LoginWidget extends XotBaseWidget
{
    // Verificare:
    // 1. Visibilità dei metodi
    // 2. Inizializzazione proprietà
    // 3. Dipendenze
    // 4. Contratti
}
```

### 2. Documentazione
```php
/**
 * @property string $view
 * @method array getFormSchema()
 */
class LoginWidget extends XotBaseWidget
{
    // Documentare:
    // 1. Proprietà
    // 2. Metodi
    // 3. Dipendenze
    // 4. Contratti
}
```

### 3. Testing
```php
class LoginWidgetTest extends TestCase
{
    /** @test */
    public function it_follows_base_class_contract()
    {
        // Testare:
        // 1. Visibilità metodi
        // 2. Inizializzazione proprietà
        // 3. Dipendenze
        // 4. Contratti
    }
}
```

## Checklist Prevenzione

### 1. Analisi
- [ ] Verificare classe base
- [ ] Controllare visibilità metodi
- [ ] Verificare inizializzazione proprietà
- [ ] Documentare dipendenze
- [ ] Implementare test

### 2. Implementazione
- [ ] Seguire best practices
- [ ] Mantenere contratti
- [ ] Documentare codice
- [ ] Implementare test
- [ ] Verificare integrazione

### 3. Verifica
- [ ] Test unitari
- [ ] Test integrazione
- [ ] Test performance
- [ ] Test sicurezza
- [ ] Documentazione

## Conclusioni

### 1. Lezioni Apprese
- Verificare sempre la classe base
- Documentare dipendenze
- Implementare test
- Seguire best practices
- Mantenere contratti

### 2. Miglioramenti
- Migliorare documentazione
- Aggiungere test
- Ottimizzare performance
- Migliorare sicurezza
- Aggiungere monitoring

### 3. Prossimi Passi
1. Aggiornare documentazione
2. Migliorare test
3. Ottimizzare performance
4. Migliorare sicurezza
5. Aggiungere monitoring 