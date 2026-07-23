# Struttura Views per Widget Filament

## Riferimenti
- [Widget Properties](filament-widget-properties.md)
- [Common Errors](filament-widget-common-errors.md)
- [Provider Inheritance Rules](provider-inheritance-rules.md)

## Struttura Corretta delle Views

### 1. Posizione delle Views
Le views dei widget Filament devono essere posizionate nel modulo corretto seguendo questa struttura:

```
Modules/
└── User/
    └── Resources/
        └── views/
            └── filament/
                └── widgets/
                    └── login.blade.php
```

### 2. Namespace delle Views
Il namespace delle views viene gestito automaticamente da `XotBaseServiceProvider`:
```php
// ❌ Errore: Tentare di registrare manualmente le views
protected function registerViews(): void
{
    $this->loadViewsFrom(__DIR__.'/../Resources/views', 'user');
}

// ✅ Corretto: Lasciare che XotBaseServiceProvider gestisca le views
class UserServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'User';  // Questo è sufficiente
}
```

### 3. Riferimento alle Views nei Widget
```php
// ❌ Errore: Namespace errato
protected static string $view = 'modules.user::filament.widgets.login';

// ✅ Corretto: Namespace semplice basato sul nome del modulo
protected static string $view = 'user::filament.widgets.login';
```

## Struttura del Template Blade

### 1. Base Template
```blade
<x-filament::widget>
    <x-filament::card>
        <form wire:submit="authenticate">
            {{ $this->form }}
            
            <x-filament::button
                type="submit"
                class="w-full"
            >
                {{ __('Login') }}
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament::widget>
```

### 2. Integrazione Livewire
Nel template della pagina:
```blade
<div>
    @livewire(\Modules\User\Filament\Widgets\LoginWidget::class)
</div>
```

## Checklist Implementazione

### 1. Struttura Files
- [ ] Views nel percorso corretto del modulo (`Resources/views/filament/widgets/`)
- [ ] Nome del modulo definito nel provider (`public string $name = 'User';`)
- [ ] Template blade con componenti Filament
- [ ] Integrazione Livewire corretta

### 2. Configurazione
- [ ] Provider estende correttamente XotBaseServiceProvider
- [ ] Namespace delle views gestito automaticamente
- [ ] Componenti Filament importati
- [ ] Livewire configurato

## Test Views

```php
class LoginWidgetViewTest extends TestCase
{
    /** @test */
    public function view_exists_in_correct_location()
    {
        $viewPath = module_path('User', 'Resources/views/filament/widgets/login.blade.php');
        $this->assertFileExists($viewPath);
    }

    /** @test */
    public function view_can_be_rendered()
    {
        $view = view('user::filament.widgets.login');
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }
}
```

## Errori Comuni

### 1. Tentare di Registrare Manualmente le Views
```php
// ❌ Errore: Non necessario e potenzialmente dannoso
protected function registerViews(): void
{
    $this->loadViewsFrom(...);
}
```

### 2. Percorso Errato
```php
// ❌ Errore: Percorso troppo nidificato
Resources/views/filament/widgets/auth/login.blade.php

// ✅ Soluzione: Percorso semplificato
Resources/views/filament/widgets/login.blade.php
```

## Conclusioni

1. Lasciare che XotBaseServiceProvider gestisca le views
2. Mantenere una struttura semplice e diretta
3. Usare il namespace corretto basato sul nome del modulo
4. Seguire le convenzioni di Filament
5. Verificare sempre il corretto caricamento delle views 