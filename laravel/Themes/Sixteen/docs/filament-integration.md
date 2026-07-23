# Integrazione con Filament Form Fields Builder

## Panoramica

Tutti i componenti del tema Sixteen sono stati standardizzati per essere compatibili con **Filament Form Fields Builder**. Ogni componente nella stessa cartella ha le stesse variabili `@props` per garantire coerenza e facilità di utilizzo.

## Standardizzazione delle Props

### Principio Fondamentale

**Tutti i componenti nella stessa cartella devono avere le stesse variabili `@props`** per essere compatibili con Filament Form Fields Builder.

### Struttura Standardizzata

```php
@props([
    // Props comuni per tutti i componenti
    'variant' => 'default',
    'size' => 'md',
    'disabled' => false,
    'required' => false,
    'name' => null,
    'id' => null,
    'label' => null,
    'help-text' => null,
    'error' => null,
    'success' => null,
    
    // Props specifiche per ogni tipo di componente
    // (vengono ignorate se non applicabili)
])
```

## Categorie di Componenti

### 1. Alert Components (`/alerts/`)

**Props Standard:**
```php
@props([
    'variant' => 'info',
    'dismissible' => false,
    'icon' => null,
    'title' => null,
    'role' => 'alert',
    'href' => null,
    'link-text' => null,
    'position' => null,
    'duration' => null
])
```

**Componenti:**
- `alert.blade.php` - Alert base
- `alert-link.blade.php` - Alert con link
- `toast.blade.php` - Toast notifications

### 2. Button Components (`/buttons/`)

**Props Standard:**
```php
@props([
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
    'type' => 'button',
    'href' => null,
    'target' => null,
    'icon' => null,
    'icon-position' => 'left',
    'active' => false,
    'vertical' => false
])
```

**Componenti:**
- `button.blade.php` - Pulsante base
- `button-group.blade.php` - Gruppo pulsanti
- `button-group-item.blade.php` - Elemento gruppo pulsanti

### 3. Form Components (`/forms/`)

**Props Standard:**
```php
@props([
    'type' => 'text',
    'variant' => 'default',
    'size' => 'md',
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'placeholder' => null,
    'value' => null,
    'name' => null,
    'id' => null,
    'label' => null,
    'help-text' => null,
    'error' => null,
    'success' => null,
    'addon-left' => null,
    'addon-right' => null,
    'icon-left' => null,
    'icon-right' => null,
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'searchable' => false,
    'rows' => 3,
    'cols' => null,
    'maxlength' => null,
    'minlength' => null,
    'accept' => null,
    'max-size' => null,
    'allowed-types' => null,
    'preview' => false,
    'checked' => false,
    'href' => null,
    'link-text' => null,
    'position' => null,
    'duration' => null
])
```

**Componenti:**
- `input.blade.php` - Input di testo
- `select.blade.php` - Menu a tendina
- `checkbox.blade.php` - Checkbox
- `radio.blade.php` - Radio button
- `switch.blade.php` - Toggle switch
- `textarea.blade.php` - Area di testo
- `file-upload.blade.php` - Upload file

### 4. Card Components (`/cards/`)

**Props Standard:**
```php
@props([
    'variant' => 'default',
    'with-header' => false,
    'with-footer' => false,
    'with-image' => false,
    'image-src' => null,
    'image-alt' => null,
    'elevated' => false,
    'bordered' => true,
    'overlay-title' => null,
    'overlay-subtitle' => null,
    'overlay-position' => 'bottom',
    'overlay-variant' => 'dark'
])
```

**Componenti:**
- `card.blade.php` - Card base
- `card-overlay.blade.php` - Card con overlay

### 5. Navigation Components (`/navigation/`)

**Props Standard:**
```php
@props([
    'brand' => null,
    'brand-href' => '/',
    'variant' => 'light',
    'sticky' => false,
    'expand' => 'lg',
    'container' => true,
    'items' => [],
    'separator' => '/',
    'aria-label' => 'Breadcrumb',
    'current-page' => 1,
    'total-pages' => 1,
    'base-url' => null,
    'size' => 'md',
    'show-first-last' => true,
    'show-prev-next' => true,
    'max-visible' => 5
])
```

**Componenti:**
- `navbar.blade.php` - Barra di navigazione
- `breadcrumb.blade.php` - Breadcrumb
- `pagination.blade.php` - Paginazione

### 6. Layout Components (`/layout/`)

**Props Standard:**
```php
@props([
    'fluid' => false,
    'max-width' => null,
    'padding' => true,
    'cols' => 1,
    'gap' => 'md',
    'responsive' => true
])
```

**Componenti:**
- `container.blade.php` - Container
- `grid.blade.php` - Sistema di griglia

### 7. Feedback Components (`/feedback/`)

**Props Standard:**
```php
@props([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'size' => 'md',
    'animated' => false,
    'striped' => false,
    'label' => null,
    'show-percentage' => false
])
```

**Componenti:**
- `progress.blade.php` - Barra di progresso
- `spinner.blade.php` - Indicatore di caricamento

### 8. Utilities Components (`/utilities/`)

**Props Standard:**
```php
@props([
    'variant' => 'primary',
    'size' => 'md',
    'pill' => false,
    'dismissible' => false,
    'content' => null,
    'position' => 'top',
    'trigger' => 'hover',
    'delay' => 0
])
```

**Componenti:**
- `badge.blade.php` - Badge
- `tooltip.blade.php` - Tooltip

## Utilizzo con Filament

### Esempio di Configurazione Filament

```php
use Filament\Forms\Components\Section;

Section::make('Informazioni')
    ->schema([
        // Input standard
        Forms\Components\TextInput::make('name')
            ->label('Nome')
            ->required()
            ->placeholder('Inserisci il nome'),
            
        // Select standard
        Forms\Components\Select::make('category')
            ->label('Categoria')
            ->options([
                'web' => 'Sviluppo Web',
                'mobile' => 'Sviluppo Mobile',
                'design' => 'Design'
            ])
            ->searchable(),
            
        // Checkbox standard
        Forms\Components\Checkbox::make('terms')
            ->label('Accetto i termini')
            ->required(),
            
        // Switch standard
        Forms\Components\Toggle::make('notifications')
            ->label('Ricevi notifiche')
            ->helpText('Riceverai notifiche via email'),
    ])
```

### Mapping Props Filament → Sixteen

| Filament Field | Sixteen Component | Props Mapping |
|----------------|-------------------|---------------|
| `TextInput` | `input.blade.php` | `name`, `label`, `required`, `placeholder` |
| `Select` | `select.blade.php` | `name`, `label`, `options`, `searchable` |
| `Checkbox` | `checkbox.blade.php` | `name`, `label`, `required` |
| `Toggle` | `switch.blade.php` | `name`, `label`, `help-text` |
| `Textarea` | `textarea.blade.php` | `name`, `label`, `rows`, `placeholder` |
| `FileUpload` | `file-upload.blade.php` | `name`, `label`, `accept`, `multiple` |

## Best Practices

### 1. Props Consistenti
- Tutti i componenti nella stessa cartella devono avere le stesse props
- Le props non utilizzate vengono semplicemente ignorate
- Mantenere l'ordine delle props consistente

### 2. Varianti Standard
- Utilizzare sempre le varianti predefinite
- Documentare nuove varianti quando aggiunte
- Mantenere coerenza tra componenti simili

### 3. Accessibilità
- Tutti i componenti includono attributi ARIA appropriati
- Supporto per screen reader e navigazione da tastiera
- Label e help text sempre disponibili

### 4. Responsive Design
- Tutti i componenti sono responsive per default
- Utilizzo di classi Tailwind responsive
- Breakpoint ottimizzati per mobile

## Esempi di Utilizzo

### Alert con Filament
```php
// Nel form Filament
Forms\Components\Section::make('Notifiche')
    ->schema([
        Forms\Components\TextInput::make('alert_message')
            ->label('Messaggio Alert')
            ->required(),
        Forms\Components\Select::make('alert_type')
            ->label('Tipo Alert')
            ->options([
                'info' => 'Informazione',
                'success' => 'Successo',
                'warning' => 'Attenzione',
                'error' => 'Errore'
            ])
    ])
```

### Button con Filament
```php
// Nel form Filament
Forms\Components\Actions::make([
    Forms\Components\Actions\Action::make('save')
        ->label('Salva')
        ->submit('save'),
    Forms\Components\Actions\Action::make('cancel')
        ->label('Annulla')
        ->color('secondary')
])
```

## Configurazione Avanzata

### Custom Props per Componenti Specifici

Se un componente ha bisogno di props specifiche, queste vengono aggiunte mantenendo la compatibilità:

```php
@props([
    // Props standard per la categoria
    'variant' => 'default',
    'size' => 'md',
    // ... altre props standard
    
    // Props specifiche per questo componente
    'custom-prop' => null,
    'specific-option' => false
])
```

### Validazione Props

Ogni componente valida le prop ricevute:

```php
@php
    // Validazione varianti
    $validVariants = ['primary', 'secondary', 'success', 'danger'];
    $variant = in_array($variant, $validVariants) ? $variant : 'primary';
    
    // Validazione dimensioni
    $validSizes = ['xs', 'sm', 'md', 'lg', 'xl'];
    $size = in_array($size, $validSizes) ? $size : 'md';
@endphp
```

---

**Ultimo aggiornamento**: Dicembre 2024
**Versione**: 1.0
**Compatibilità**: Filament 3.x, Laravel 10.x, Tailwind CSS 3.x 