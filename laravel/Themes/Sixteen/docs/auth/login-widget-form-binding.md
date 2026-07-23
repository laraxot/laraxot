# LoginWidget Form Data Binding - Fix e Pattern

## Problema Risolto

Il `LoginWidget` mostrava un errore indicando che i campi email e password "non sono popolati" anche quando l'utente inseriva i valori nei campi del form.

## Causa Root

`XotBaseWidget` ha già implementato `mount()`, ma il problema era che il form non veniva correttamente inizializzato quando si usa `statePath('data')` in Filament.

### Analisi Tecnica

1. **XotBaseWidget** configura il form con `statePath('data')` nel metodo `schema()`
2. **XotBaseWidget** ha `mount()` che chiama `$this->data = $this->getFormFill()` e `$this->form->fill($this->data)`
3. Per widget senza modello (`getFormModel()` restituisce `null`), `getFormFill()` restituisce `[]`
4. Il form viene inizializzato correttamente con array vuoto
5. I campi vengono correttamente legati allo stato quando l'utente inserisce valori

## Pattern Corretto

### 1. Sovrascrivere mount() e chiamare initXotBaseWidget()

```php
// ✅ CORRETTO: Pattern obbligatorio per tutti i widget
class LoginWidget extends XotBaseWidget
{
    public function mount(): void
    {
        $this->initXotBaseWidget();
    }
}

// ❌ ERRATO: Non chiamare initXotBaseWidget()
class LoginWidget extends XotBaseWidget
{
    // mount() mancante - il form non viene inizializzato!
}
```

### 2. NON ridichiarare proprietà $data

```php
// ❌ ERRATO
class LoginWidget extends XotBaseWidget
{
    public ?array $data = []; // ERRORE: già definito in XotBaseWidget
}

// ✅ CORRETTO
class LoginWidget extends XotBaseWidget
{
    // $data è già definito in XotBaseWidget
}
```

### 3. Schema con chiavi stringa

```php
#[\Override]
public function getFormSchema(): array
{
    return [
        'email' => TextInput::make('email')->email()->required()->autofocus(),
        'password' => TextInput::make('password')->password()->required(),
        'remember' => Checkbox::make('remember'),
    ];
}
```

## Quando Aggiungere Logica Aggiuntiva in mount()

Se serve logica aggiuntiva, aggiungerla dopo `initXotBaseWidget()`:

```php
// ✅ CORRETTO: Logica aggiuntiva in RegisterWidget
class RegisterWidget extends XotBaseWidget
{
    public function mount(): void
    {
        $this->initXotBaseWidget(); // SEMPRE chiamare prima
        Log::debug('Registration form initialized', [
            'ip' => request()->ip(),
        ]);
    }
}
```

## Riferimenti

- [XotBaseWidget Documentation](../../../Modules/Xot/docs/filament-class-extension-rules.md)
- [LoginWidget Fix Documentation](../../../Modules/User/docs/login-widget-fix.md)
- [Filament Form State Management](https://filamentphp.com/docs/5.x/forms/fields#state-management)

---

