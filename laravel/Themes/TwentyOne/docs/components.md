# Componenti del Tema TwentyOne

## Introduzione
Questa documentazione descrive i componenti principali del tema TwentyOne e come utilizzarli nel tuo progetto Laravel.

## Struttura dei Componenti
I componenti sono organizzati nella cartella `app/` seguendo le best practices di Laravel e utilizzando Spatie Data per una gestione robusta dei dati.

### Componenti Principali

#### Layout
- `MainLayout` - Layout principale del tema
- `Header` - Componente header con navigazione
- `Footer` - Componente footer con informazioni di copyright

#### UI Components
- `Button` - Componente pulsante personalizzabile
- `Card` - Componente card per contenuti
- `Form` - Componenti form con validazione
- `Modal` - Componente modale per dialoghi

## Utilizzo dei Componenti

### Esempio di Utilizzo
```php
use TwentyOne\Components\Button;
use TwentyOne\Components\Card;

// Nel tuo blade template
<x-twentyone::button 
    type="primary"
    :data="ButtonData::from([
        'text' => 'Clicca qui',
        'icon' => 'arrow-right'
    ])"
/>

<x-twentyone::card
    :data="CardData::from([
        'title' => 'Titolo Card',
        'content' => 'Contenuto della card'
    ])"
/>
```

## Data Objects
Tutti i componenti utilizzano Spatie Data per una gestione type-safe dei dati:

```php
use Spatie\LaravelData\Data;

class ButtonData extends Data
{
    public function __construct(
        public string $text,
        public ?string $icon = null,
        public string $type = 'primary'
    ) {}
}
```

## Personalizzazione
I componenti possono essere personalizzati in diversi modi:

1. **Estensione dei Data Objects**
```php
class CustomButtonData extends ButtonData
{
    public function __construct(
        public string $text,
        public ?string $icon = null,
        public string $type = 'primary',
        public string $customProperty = 'default'
    ) {
        parent::__construct($text, $icon, $type);
    }
}
```

2. **Sovrascrittura dei Template**
```php
// resources/views/vendor/twentyone/components/button.blade.php
@props(['data'])

<button 
    class="btn btn-{{ $data->type }}"
    {{ $attributes }}
>
    @if($data->icon)
        <x-twentyone::icon :name="$data->icon" />
    @endif
    {{ $data->text }}
</button>
```

## Best Practices

1. **Type Safety**
   - Utilizza sempre i Data Objects per i componenti
   - Definisci proprietà e metodi con tipi specifici

2. **Composizione**
   - Preferisci la composizione all'ereditarietà
   - Utilizza slot per contenuti dinamici

3. **Performance**
   - Lazy loading dei componenti quando possibile
   - Caching dei componenti statici

4. **Accessibilità**
   - Includi sempre attributi ARIA appropriati
   - Supporta la navigazione da tastiera

## Troubleshooting

### Problemi Comuni

1. **Componente non trovato**
   - Verifica il namespace corretto
   - Controlla il registro dei componenti

2. **Errori di tipo**
   - Assicurati di utilizzare i Data Objects corretti
   - Verifica i tipi delle proprietà

3. **Stili mancanti**
   - Controlla l'importazione dei file CSS
   - Verifica la compilazione di Tailwind

## Contribuire
Per aggiungere nuovi componenti:

1. Crea il Data Object corrispondente
2. Implementa il template Blade
3. Aggiungi la documentazione
4. Includi test unitari 