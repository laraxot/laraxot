# Proprietà dei Widget Filament

## Riferimenti
- [Best Practices Widget](filament-widget-best-practices.md)
- [Errori Comuni](filament-widget-common-errors.md)

## Proprietà Statiche vs Non Statiche

### View Property
La proprietà `$view` nei widget Filament deve essere definita come statica e deve seguire specifiche regole:

```php
// ❌ Errore: Definizione incompleta
protected static string $view;

// ❌ Errore: Namespace non corretto
protected static string $view = 'user::widgets.auth.login';

// ✅ Corretto: Definizione completa con namespace corretto
protected static string $view = 'user::filament.widgets.auth.login';
```

#### Regole per la View
1. **Tipo**: Deve essere `string`
2. **Visibilità**: Deve essere `protected static`
3. **Namespace**: Deve seguire il pattern `{module}::filament.widgets.{type}`
4. **Inizializzazione**: Deve essere inizializzata al momento della dichiarazione

#### Struttura delle Cartelle
```
Modules/
└── User/
    └── Resources/
        └── views/
            └── filament/
                └── widgets/
                    └── auth/
                        └── login.blade.php
```

## Best Practices

### 1. Definizione delle Proprietà
```php
class LoginWidget extends XotBaseWidget
{
    // Proprietà statiche
    protected static string $view = 'user::filament.widgets.auth.login';
    
    // Proprietà di istanza
    protected int | string | array $columnSpan = 'full';
    protected bool $isCollapsible = true;
    protected array $data = [];
}
```

### 2. Documentazione
```php
/**
 * @property static string $view La view del widget segue il pattern {module}::filament.widgets.{type}
 * @property int|string|array $columnSpan Definisce la larghezza del widget
 */
class LoginWidget extends XotBaseWidget
{
    // ...
}
```

## Checklist Implementazione

### 1. View Property
- [ ] Definita come `protected static string`
- [ ] Inizializzata con un valore
- [ ] Segue il pattern corretto del namespace
- [ ] La view esiste nel percorso corretto

### 2. Altre Proprietà
- [ ] Definite con il tipo corretto
- [ ] Inizializzate con valori di default
- [ ] Documentate con PHPDoc
- [ ] Rispettano le convenzioni di Filament

## Test

```php
class LoginWidgetTest extends TestCase
{
    /** @test */
    public function it_has_correct_view_property()
    {
        $widget = new LoginWidget();
        $reflection = new ReflectionClass($widget);
        $viewProperty = $reflection->getProperty('view');
        
        $this->assertTrue($viewProperty->isStatic());
        $this->assertTrue($viewProperty->isProtected());
        $this->assertEquals('string', $viewProperty->getType()->getName());
        $this->assertEquals(
            'user::filament.widgets.auth.login',
            $viewProperty->getValue()
        );
    }
}
```

## Errori Comuni e Soluzioni

### 1. Proprietà Non Inizializzata
```php
// ❌ Errore
protected static string $view;

// ✅ Soluzione
protected static string $view = 'user::filament.widgets.auth.login';
```

### 2. Namespace Errato
```php
// ❌ Errore
protected static string $view = 'user::widgets.login';

// ✅ Soluzione
protected static string $view = 'user::filament.widgets.auth.login';
```

### 3. Tipo Errato
```php
// ❌ Errore
protected static $view = 'user::filament.widgets.auth.login';

// ✅ Soluzione
protected static string $view = 'user::filament.widgets.auth.login';
```

## Conclusioni

1. La proprietà `$view` è fondamentale per il corretto funzionamento del widget
2. Deve seguire regole precise di definizione e namespace
3. La struttura delle cartelle deve rispecchiare il namespace
4. I test devono verificare la corretta implementazione
5. La documentazione deve essere chiara e completa 