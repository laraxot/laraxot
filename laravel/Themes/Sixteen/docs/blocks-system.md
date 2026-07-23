# Sixteen Theme Blocks System

## Panoramica

Il sistema di blocchi del tema Sixteen fornisce componenti Blade riutilizzabili convertiti da Bootstrap Italia a Tailwind CSS, progettati specificamente per l'integrazione con Filament Form Field Builder. Ogni categoria di blocchi condivide la stessa struttura `@props` per garantire intercambiabilità e compatibilità.

## Filosofia del Sistema

### Coerenza dei Props
Tutti i blocchi nella stessa categoria condividono **identici `@props`** per permettere:
- **Intercambiabilità**: Sostituire un blocco con un altro della stessa categoria senza modificare il codice
- **Integrazione Filament**: Compatibilità diretta con Filament Form Field Builder
- **Manutenibilità**: Struttura prevedibile e consistente
- **Scalabilità**: Facile aggiunta di nuove varianti mantenendo la compatibilità

### Struttura Directory
```
resources/views/components/blocks/
├── cards/          # Componenti card con props uniformi
├── buttons/        # Componenti button con props uniformi  
├── alerts/         # Componenti alert con props uniformi
├── badges/         # Componenti badge con props uniformi
├── forms/          # Componenti form con props uniformi
├── navigation/     # Componenti navigazione con props uniformi
├── layout/         # Componenti layout con props uniformi
└── content/        # Componenti contenuto con props uniformi
```

## Categorie e Props Standard

### 1. Cards Category

**Percorso**: `resources/views/components/blocks/cards/`

**Props Standard** (identici per tutti i blocchi cards):
```php
@props([
    'title' => '',           // string - Titolo della card
    'subtitle' => '',        // string - Sottotitolo (opzionale)
    'content' => '',         // string - Contenuto principale
    'image' => '',           // string - URL immagine (opzionale)
    'footer' => '',          // string - Contenuto footer (opzionale)
    'variant' => 'default',  // string - Variante stile (default, primary, secondary, success, danger, warning, info)
    'size' => 'md',          // string - Dimensione (sm, md, lg)
    'shadow' => true,        // bool - Abilita ombra
    'border' => true,        // bool - Abilita bordo
    'link' => ''             // string - URL collegamento (opzionale)
])
```

**Varianti Disponibili**:
- `basic.blade.php` - Card base standard
- `featured.blade.php` - Card in evidenza con styling prominente
- `service.blade.php` - Card ottimizzata per servizi PA

**Esempio di Utilizzo**:
```blade
<x-cards.basic
    title="Servizio Anagrafe"
    subtitle="Servizi demografici"
    content="Richiesta certificati anagrafici online"
    variant="primary"
    size="md"
    link="/servizi/anagrafe"
/>

<x-cards.featured
    title="Servizio in Evidenza"
    content="Contenuto del servizio principale"
    variant="primary"
    size="lg"
    :shadow="true"
/>
```

### 2. Buttons Category

**Percorso**: `resources/views/components/blocks/buttons/`

**Props Standard** (identici per tutti i blocchi buttons):
```php
@props([
    'text' => '',            // string - Testo del pulsante
    'icon' => '',            // string - Nome icona o SVG (opzionale)
    'iconPosition' => 'left', // string - Posizione icona (left, right)
    'size' => 'md',          // string - Dimensione (xs, sm, md, lg, xl)
    'variant' => 'solid',    // string - Variante (solid, outline, ghost, link)
    'color' => 'primary',    // string - Colore (primary, secondary, success, danger, warning, info)
    'disabled' => false,     // bool - Stato disabilitato
    'loading' => false,      // bool - Stato caricamento
    'href' => '',            // string - URL collegamento (opzionale)
    'type' => 'button',      // string - Tipo button (button, submit, reset)
    'fullWidth' => false     // bool - Larghezza completa
])
```

**Varianti Disponibili**:
- `primary.blade.php` - Pulsante primario standard
- `cta.blade.php` - Call-to-Action con styling prominente

**Esempio di Utilizzo**:
```blade
<x-buttons.primary
    text="Accedi al Servizio"
    icon="<svg>...</svg>"
    iconPosition="right"
    size="lg"
    variant="solid"
    color="primary"
    href="/login"
/>

<x-buttons.cta
    text="Inizia Ora"
    size="xl"
    color="success"
    :fullWidth="true"
/>
```

## Integrazione con Filament

### Form Field Builder Compatibility

I blocchi sono progettati per integrarsi perfettamente con Filament Form Field Builder:

```php
// In un Filament Resource o Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

public function form(Form $form): Form
{
    return $form->schema([
        Select::make('card_variant')
            ->label('Tipo Card')
            ->options([
                'basic' => 'Card Base',
                'featured' => 'Card in Evidenza', 
                'service' => 'Card Servizio'
            ])
            ->default('basic'),
            
        TextInput::make('title')
            ->label('Titolo')
            ->required(),
            
        TextInput::make('content')
            ->label('Contenuto'),
            
        Select::make('variant')
            ->label('Variante Colore')
            ->options([
                'default' => 'Default',
                'primary' => 'Primario',
                'success' => 'Successo',
                'danger' => 'Pericolo'
            ])
    ]);
}

// Nel template Blade
<x-cards.{{ $record->card_variant }}
    :title="$record->title"
    :content="$record->content"
    :variant="$record->variant"
    size="md"
    :shadow="true"
/>
```

### Dynamic Block Selection

```php
// Selezione dinamica del blocco mantenendo props consistenti
$blockType = $record->block_type; // 'basic', 'featured', 'service'
$blockData = [
    'title' => $record->title,
    'content' => $record->content,
    'variant' => $record->variant,
    'size' => $record->size ?? 'md'
];
```

```blade
{{-- Nel template --}}
<x-cards.{{ $blockType }} v-bind="$blockData" />
```

## Best Practices

### 1. Mantenimento Coerenza Props
- **MAI** aggiungere props specifici a un singolo blocco
- **SEMPRE** mantenere la stessa signature `@props` per tutta la categoria
- **SEMPRE** fornire valori di default sensati

### 2. Naming Convention
- Nomi file in **lowercase** con trattini: `featured-card.blade.php`
- Nomi categorie in **inglese** e **plurale**: `cards`, `buttons`, `alerts`
- Nomi varianti **descrittivi** e **concisi**: `basic`, `featured`, `cta`

### 3. Documentazione Props
- **SEMPRE** documentare ogni prop nel commento del blocco
- **SEMPRE** specificare il tipo di dato
- **SEMPRE** indicare se opzionale e il valore default

### 4. Accessibilità
- **SEMPRE** includere attributi ARIA appropriati
- **SEMPRE** gestire stati focus/hover/active
- **SEMPRE** supportare navigazione da tastiera

### 5. Performance
- **SEMPRE** utilizzare classi Tailwind ottimizzate
- **SEMPRE** minimizzare il DOM generato
- **SEMPRE** utilizzare lazy loading per immagini quando appropriato

## Estensione del Sistema

### Aggiunta Nuova Categoria

1. **Creare directory**: `resources/views/components/blocks/nuova-categoria/`
2. **Definire props standard** per tutti i blocchi della categoria
3. **Creare primo blocco** con props completi
4. **Documentare** la categoria in questo file
5. **Creare esempi** di utilizzo

### Aggiunta Nuova Variante

1. **Utilizzare props identici** alla categoria esistente
2. **Implementare logica differente** mantenendo interfaccia uguale
3. **Testare intercambiabilità** con altre varianti
4. **Aggiornare documentazione** con nuova variante

## Testing e Validazione

### Checklist Qualità Blocco
- [ ] Props identici agli altri blocchi della categoria
- [ ] Documentazione PHPDoc completa
- [ ] Accessibilità WCAG 2.1 AA
- [ ] Responsive design testato
- [ ] Integrazione Filament funzionante
- [ ] Performance ottimizzata

### Test di Intercambiabilità
```php
// Test che tutti i blocchi cards accettino gli stessi props
$testProps = [
    'title' => 'Test Title',
    'content' => 'Test Content', 
    'variant' => 'primary',
    'size' => 'md'
];

// Deve funzionare con qualsiasi variante
<x-cards.basic v-bind="$testProps" />
<x-cards.featured v-bind="$testProps" />  
<x-cards.service v-bind="$testProps" />
```

## Roadmap

### Categorie Pianificate
- [ ] **Alerts** - Messaggi di notifica e avvisi
- [ ] **Badges** - Etichette e indicatori di stato
- [ ] **Forms** - Componenti form avanzati
- [ ] **Navigation** - Elementi di navigazione
- [ ] **Layout** - Componenti strutturali
- [ ] **Content** - Elementi di contenuto
- [ ] **Media** - Componenti multimediali
- [ ] **Interactive** - Elementi interattivi

### Funzionalità Future
- [ ] **Block Builder UI** - Interfaccia grafica per costruire blocchi
- [ ] **Theme Variants** - Varianti tematiche per ogni blocco
- [ ] **Animation Presets** - Animazioni predefinite
- [ ] **A/B Testing** - Supporto per test A/B sui blocchi
- [ ] **Analytics Integration** - Tracking automatico interazioni

## Supporto e Contributi

### Segnalazione Bug
- Utilizzare il sistema di issue tracking del progetto
- Includere sempre esempio di riproduzione
- Specificare browser e versione Filament

### Richiesta Nuove Funzionalità
- Proporre mantenendo coerenza con sistema esistente
- Fornire casi d'uso concreti
- Considerare impatto su intercambiabilità blocchi

### Contributi Codice
- Seguire le convenzioni stabilite
- Mantenere props consistenti per categoria
- Includere test e documentazione
- Rispettare standard accessibilità

---

*Versione Sistema Blocchi: 1.0*
