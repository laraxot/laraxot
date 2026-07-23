# 🎯 Filament Wizard Patterns - Best Practices

## Pattern Ufficiali Filament v5

### 1. **Wizard Base Structure**
```php
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

// Nel widget
public function getFormSchema(): array
{
    return [
        Wizard::make($this->getSteps())
            ->startOnStep(fn () => $this->wizardStartStep)
            ->columnSpanFull(),
    ];
}
```

### 2. **Step con Description** (Pattern Ufficiale)
```php
public function getSteps(): array
{
    return [
        Step::make('privacy')
            ->description('Leggi e accetta l\'informativa privacy')
            ->schema($this->getPrivacySchema()),
        
        Step::make('data')
            ->description('Compila i dati della segnalazione')
            ->schema($this->getDataSchema()),
        
        Step::make('summary')
            ->description('Riepilogo dei dati inseriti')
            ->schema($this->getSummarySchema()),
    ];
}
```

### 3. **Submit stato wizard (preferenza progetto)**

Non introdurre un helper generico sulla base che riscrive l’intero array dopo `$this->form->getState()`. Preferisci **`$this->form->getState()`** più eventuali merge di dominio (es. `owner_id`). Se il modello non accetta chiavi annidate, sistemare **schema** / **cast / mutatori**, non middleware PHP nel widget base.

```php
public function submit(): void
{
    /** @var array<string, mixed> $data */
    $data = $this->form->getState();
    Ticket::create($data);
}
```

### 4. **Action Configuration**
```php
protected function configureWizardNextAction(Action $action): Action
{
    return $action
        ->icon('heroicon-o-arrow-right')
        ->requiresConfirmation(); // opzionale
}

protected function configureWizardPreviousAction(Action $action): Action
{
    return $action
        ->icon('heroicon-o-arrow-left');
}
```

## Anti-Patterns da Evitare

### ❌ **Hardcoded Labels**
```php
// SBAGLIATO
Text::make('name')->label('Nome')

// CORRETTO (Auto-label system)
Text::make('name') // LangServiceProvider gestisce il label
```

### ❌ **Enum Cast Directto**
```php
// SBAGLIATO
$type = TicketTypeEnum::tryFrom((string) ($get('type') ?? ''));

// CORRETTO
$typeValue = $get('type');
$type = is_string($typeValue) ? TicketTypeEnum::tryFrom($typeValue) : null;
```

### ❌ **Log nei Widget**
```php
// SBAGLIATO
Log::error($e->getMessage());

// CORRETTO
Notification::make()->danger()->title('Errore')->send();
```

## Pattern Migliorati XotBaseWizardWidget

### 1. **Step Navigation Safety**
```php
protected function queryStepOverrideAllowed(): bool
{
    // Consentito SOLO in sviluppo
    if (app()->isLocal()) return true;
    if (config('app.debug')) return true;
    
    return $this->wizardAllowStepQueryExtra();
}
```

### 2. **Gestione stato in submit**

Vedi sopra § **Submit stato wizard**: niente snippet di `normalizeWizardFormState` duplicati — la forma deve combaciare col dehydrate Filament.

### 3. **Auto-Label System**
```php
// Pattern: {namespace}::{widget_name}.{type}.{field_name}.{property}
// es: fixcity::create_ticket_wizard.fields.address.placeholder
// es: fixcity::create_ticket_wizard.actions.next.label
```

## Customization Points

### Override nei Widget di Dominio

```php
class CreateTicketWizardWidget extends XotBaseWizardWidget
{
    // Custom validation tra step
    protected function beforeNextStep(): bool
    {
        if ($this->wizardStartStep === 1) {
            return $this->validatePrivacyStep();
        }
        return true;
    }

    // Custom data preparation
    protected function prepareWizardFormData(array $data): array
    {
        $data['status'] = 'pending';
        return $data;
    }

    // Custom action configuration
    protected function configureWizardNextAction(Action $action): Action
    {
        return parent::configureWizardNextAction($action)
            ->after(fn () => $this->afterNextStep());
    }
}
```

## Testing Pattern

### Pest Feature Test
```php
test('wizard navigation works', function () {
    $component = mountComponent(CreateTicketWizardWidget::class);
    
    // Step 1
    $component->assertSee('Privacy');
    
    // Next step
    $component->call('nextStep');
    $component->assertSee('Dati di segnalazione');
});
```

### Visual Testing con Puppeteer e Playwright

Tutte le modifiche wizard richiedono test visuali automatici:

```bash
# Installazione strumenti globali (solo una volta per sistema)
npm install -g playwright@latest
npm install -g puppeteer@latest
playwright install

# Esecuzione test
npm run test:visual:wizard
```

**Pattern di test per wizard step:**
```javascript
// Esempio test Playwright per wizard step visibility
test('wizard step 1 is visible', async ({ page }) => {
    await page.goto('/it/tests/segnalazione-crea?step=privacy');
    
    // Verifica che il primo step sia visibile
    await expect(page.locator('.wizard-dc-form-shell .fi-sc-wizard-step.fi-active')).toBeVisible();
    await expect(page.locator('text=Privacy')).toBeVisible();
});
```

### Quality Gate Obligatorio

Tutte le modifiche a widget wizard devono passare attraverso:

1. **phpstan analyse** - 0 errori richiesti
2. **phpmd.phar** (in ./tools) - nessun errore bloccante  
3. **phpinsights** - nessun errore critico
4. **pest** - test devono passare
5. **puppeteer** e **playwright** - test visuali devono passare
6. **Verifica file .lock** - integrità mantenuta

## Migration da Versioni Precedenti

### Da Filament v4 a v5
```php
// V4
use Filament\Forms\Concerns\ManagesFormState;

// V5
use Filament\Schemas\Components\Wizard;
```

### Key Changes
- `steps()` → `getSteps()`
- `formSchema()` → `getFormSchema()`
- Actions sono ora configurate via callbacks
- La forma del payload in submit è quella esposta da **`$this->form->getState()`** + schema/dehydrate (niente normalize generico sulla base Xot wizard)

## Performance Tips

1. **Lazy Load Steps**: Caricare gli schema solo quando necessari
2. **Minimal State**: Non salvare dati temporanei nello stato
3. **Cache Labels**: Le labels sono gestite da LangServiceProvider
4. **Database Transactions**: Gestire nel metodo submit

---

**Ricorda**: Segui sempre la [documentazione ufficiale Filament](https://filamentphp.com/docs/5.x/resources/creating-records#using-a-wizard) per gli aggiornamenti più recenti.