# Filament Multiple Forms Rule

## REGOLA PERMANENTE: Widget $form property declaration

### Vincolo assoluto

```
OBBLIGATORIO: dichiarare `public $form = null;` nei widget che usano form
VIETATO: non dichiarare il form property nei widget che usano Blade template
```

### Perché

Livewire inietta automaticamente il form instance quando è referenziato in Blade template (`{{ $this->form }}`), ma la proprietà deve esistere nella classe PHP.

Senza la dichiarazione, Livewire lancia `PropertyNotFoundException: Property [$form] not found on component`.

### Pattern corretto

```php
// Nello widget PHP
class CreateTicketWizardWidget extends XotBaseWizardWidget
{
    /** 
     * Property required by Livewire for form injection
     * @see https://filamentphp.com/docs/5.x/components/form#using-multiple-forms
     */
    public $form = null;
    
    // ... resto della classe
}
```

### Pattern corretto nel Blade template

```blade
<!-- Nel template Blade -->
<div>
    {{ $this->form }}
    
    <!-- Oppure per specifici form -->
    <form wire:submit.prevent="submit">
        {{ $this->form }}
        <button type="submit">Submit</button>
    </form>
</div>
```

### Quando applicare

Quando il widget usa:
- `InteractsWithForms` trait
- `getFormSchema()` metodo
- Blade template con `{{ $this->form }}`
- Multiple forms con `forms()` metodo

### File coinvolti

- `laravel/Themes/Sixteen/resources/views/components/sections/` - Template Blade
- `laravel/Modules/*/app/Filament/Widgets/` - Widget PHP

### Documentazione

- Root wiki: `docs/wiki/concepts/filament-multiple-forms-rule.md`
- Fixcity wiki: `laravel/Modules/Fixcity/docs/wiki/concepts/filament-multiple-forms-rule.md`
- Xot reference: `laravel/Modules/Xot/app/Filament/Widgets/XotBaseWidget.php`

### Esempio reale

```php
// In laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php
class CreateTicketWizardWidget extends XotBaseWizardWidget
{
    public $form = null; // OBBLIGATORIO
    
    public function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required(),
            // ... altri campi
        ];
    }
}
```

```blade
// In laravel/Modules/Fixcity/resources/views/filament/widgets/...
<div class="wizard-form">
    {{ $this->form }}
</div>
```