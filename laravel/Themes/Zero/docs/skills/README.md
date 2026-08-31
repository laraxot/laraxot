# Skills per il Tema Zero

## Panoramica

Questo documento fornisce informazioni sulle skills disponibili per il tema Zero. Le skills permettono agli agenti AI di specializzarsi in aree specifiche del tema e migliorare la produttivita' nello sviluppo frontend.

## Governance d'uso

Le skills del tema non sostituiscono i `docs/` del tema:

- prima si leggono e si aggiornano i `Themes/*/docs/` pertinenti;
- poi si usa la skill minima necessaria;
- le decisioni rilevanti tornano nei `docs/` come handoff per altri agenti;
- se il task impatta PHP, il quality gate include `phpstan`, `PHPMD` standalone `.phar` e `phpinsights`.

## Skills Disponibili

### 1. Skills per UI e Design

#### Theme Skills
- **Theme Factory**: Applicazione temi professionali a documenti, presentazioni e landing pages
- **Canvas Design**: Creazione arte visiva in PNG e PDF con design philosophy
- **Imagen**: Generazione immagini usando Google Gemini per mockup UI, icone e illustrazioni

#### Design Skills
- **Brand Guidelines**: Applicazione colori e tipografia ufficiale per coerenza visiva
- **Image Enhancer**: Miglioramento qualità immagini e screenshot per presentazioni professionali
- **Slack GIF Creator**: Creazione GIF animate ottimizzate per Slack con validazione dimensioni

### 2. Skills per Frontend Development

#### Frontend Skills
- **Tailwind CSS Skills**: Best practices Tailwind CSS v4, utility classes, configurazione
- **Flux UI Skills**: Componenti Livewire Flux UI, varianti e customizzazioni
- **Component Library Skills**: Creazione e gestione librerie componenti riutilizzabili

#### UI/UX Skills
- **UI Component Creation**: Creazione componenti frontend con design system
- **Responsive Design**: Implementazione design responsive per mobile e desktop
- **Accessibility Skills**: Best practices accessibilità WCAG per componenti frontend

### 3. Skills per Workflow e Productivity

#### Development Skills
- **Asset Management**: Gestione risorse frontend, compilazione Vite, ottimizzazione
- **Build System Skills**: Configurazione e ottimizzazione pipeline di build
- **Development Workflow**: Best practices per workflow sviluppo frontend

#### Filament / Livewire Operational Rules
- **Download actions**: le closure `->action()` che generano PDF/Excel devono `return` la response (es. `StreamedResponse`). Se la closure è `void` o non fa `return`, il browser non scarica nulla.
- **tableFilters payload**: quando passi `$this->tableFilters` a una Action, passalo direttamente (niente wrapper tipo `['anno/valutatore' => $tableFilters]`). La normalizzazione va fatta dentro l’Action.
- **Riferimento**: `Modules/IndennitaCondizioniLavoro/docs/action-return-type-rule.md`

#### Testing Skills
- **Frontend Testing**: Testing componenti frontend, browser automation
- **Visual Regression**: Testing regressione visiva per componenti UI
- **Performance Testing**: Testing performance e ottimizzazione frontend

## Implementazione Skills per il Tema Zero

### 1. Struttura delle Skills

```
Themes/Zero/docs/skills/
├── ui-design-skills/
│   ├── SKILL.md
│   └── templates/
├── frontend-dev-skills/
│   ├── SKILL.md
│   └── resources/
└── workflow-skills/
    ├── SKILL.md
    └── scripts/
```

### 2. Esempio di Skill per UI Design

```yaml
name: "zero-ui-design-skills"
description: "Best practices e guidelines per lo sviluppo UI nel tema Zero"
argument-hint: "[nome componente]"
disable-model-invocation: false
user-invocable: true
allowed-tools: ["read", "write", "list_directory", "search_file_content"]
```

```markdown
# Skills UI Design per il Tema Zero

## Panoramica
Questa skill fornisce le best practices e guidelines per lo sviluppo UI nel tema Zero.

## Design System Zero

### Color Palette
- **Primary**: #2563eb (Blue 600)
- **Secondary**: #64748b (Stone 500)
- **Success**: #10b981 (Emerald 500)
- **Warning**: #f59e0b (Amber 500)
- **Danger**: #ef4444 (Red 500)

### Typography
- **Font**: Inter, system-ui, sans-serif
- **Scale**: 12px, 14px, 16px, 18px, 20px, 24px, 30px, 36px

### Spacing
- **Base Unit**: 4px
- **Scale**: 0, 1, 2, 4, 6, 8, 12, 16, 24, 32

## Componenti Consigliati

### 1. Card Components
```

```blade
<x-zero.card class="bg-white rounded-lg shadow-md p-6">
    <x-zero.card.header>
        <h3 class="text-lg font-semibold text-gray-900">Titolo Card</h3>
    </x-zero.card.header>
    <x-zero.card.body>
        <p class="text-gray-600">Contenuto del card</p>
    </x-zero.card.body>
    <x-zero.card.footer>
        <x-zero.button>CTA</x-zero.button>
    </x-zero.card.footer>
</x-zero.card>
```

### 2. Form Components
```blade
<x-zero.form>
    <x-zero.form.field>
        <x-zero.form.label>Etichetta Campo</x-zero.form.label>
        <x-zero.input type="text" />
    </x-zero.form.field>
</x-zero.form>
```

### 3. Table Components
```blade
<x-zero.table>
    <x-slot name="header">
        <x-zero.table.header>Colonna 1</x-zero.table.header>
        <x-zero.table.header>Colonna 2</x-zero.table.header>
    </x-slot>
    <x-slot name="body">
        <x-zero.table.row>
            <x-zero.table.cell>Dato 1</x-zero.table.cell>
            <x-zero.table.cell>Dato 2</x-zero.table.cell>
        </x-zero.table.row>
    </x-slot>
</x-zero.table>
```

## Best Practices

### 1. Responsive Design
- Usare classi Tailwind responsive: `sm:`, `md:`, `lg:`, `xl:`
- Implementare mobile-first design
- Testare su diversi viewport

### 2. Accessibility
- Aggiungere `aria-label` per componenti non testuali
- Usare colori con sufficiente contrasto
- Implementare keyboard navigation

### 3. Performance
- Ottimizzare immagini con `loading="lazy"`
- Usare componenti pesanti solo quando necessario
- Minimizzare CSS inutile

## Template di Riferimento

### Template Componente Base
```blade
@props(['variant' => 'default', 'size' => 'md'])

<div {{ $attributes->merge(['class' => $this->getClasses()]) }}>
    {{ $slot }}
</div>

@php
public function getClasses(): string
{
    $classes = ['base-component'];
    
    // Variant classes
    $classes[] = match($this->variant) {
        'primary' => 'bg-blue-600 text-white',
        'secondary' => 'bg-gray-200 text-gray-800',
        default => 'bg-white text-gray-900',
    };
    
    // Size classes
    $classes[] = match($this->size) {
        'sm' => 'text-sm px-3 py-1.5',
        'lg' => 'text-lg px-6 py-3',
        default => 'text-base px-4 py-2',
    };
    
    return implode(' ', $classes);
}
@endphp
```

## Testing e Validazione

### 1. Visual Testing
- Confrontare screenshot con design system
- Validare consistenza across componenti
- Testare su browser diversi

### 2. Accessibility Testing
- Usare tool come axe-core
- Validare WAI-ARIA attributes
- Testare screen reader compatibility

## Conclusione

Le skills per il tema Zero sono fondamentali per mantenere coerenza visiva e qualità del codice nel frontend. Con l'implementazione corretta di queste skills, il team può creare UI moderne, accessibili e performanti che rispettino il design system del tema.
