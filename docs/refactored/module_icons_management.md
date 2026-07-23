# Gestione delle Icone nei Moduli

## Struttura e Convenzioni

### Directory delle Icone
Le icone SVG devono essere posizionate nella seguente struttura:
```
laravel/Modules/NomeModulo/
├── resources/
    ├── svg/           # Directory per le icone SVG
    │   ├── navigation/  # Icone per la navigazione
    │   ├── custom/    # Icone personalizzate
    │   └── heroicons/ # Icone di Heroicons
    └── icon.svg       # Icona principale del modulo
```

### Registrazione Automatica
Le icone vengono registrate automaticamente tramite il `XotBaseServiceProvider` che:
1. Rileva la directory `svg` del modulo
2. Configura il prefisso basato sul nome del modulo in lowercase
3. Registra il set di icone in `blade-icons.sets`

### Convenzioni di Nomenclatura
#### File SVG
- L'icona principale del modulo DEVE essere nominata `icon.svg`
- Le icone di navigazione devono seguire il pattern `{modulo}-{funzione}-icon.svg`
- Tutte le icone devono essere ottimizzate e includere attributi di accessibilità
- Utilizzare kebab-case per i nomi dei file

#### Struttura SVG
Ogni file SVG dovrebbe seguire questa struttura base:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke="currentColor"
     stroke-width="1.5"
     aria-hidden="true" 
     role="img">
    <!-- Stili e animazioni -->
    <style>
        /* Definizioni delle animazioni */
    </style>
    
    <!-- Contenuto SVG -->
</svg>
```

### Animazioni e Interattività
È consigliato includere animazioni subtle per migliorare l'UX:
```css
<style>
    .element { transition: all 0.3s ease-in-out; }
    @keyframes custom-animation {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
</style>
```

### File di Traduzione
La struttura dei file di traduzione deve seguire questo pattern:

```php
// lang/it/{module}.php
return [
    'navigation' => [
        'name' => 'Nome Modulo',
        'group' => 'Gruppo',
        'sort' => 10,
        'icon' => 'modulo-icon',
        'badge' => [
            'color' => 'success',
            'label' => 'Stato',
        ],
    ],
    'sections' => [
        'feature' => [
            'navigation' => [
                'name' => 'Nome Funzionalità',
                'group' => 'Modulo',
                'sort' => 20,
                'icon' => 'modulo-feature-icon',
                'badge' => [
                    'color' => 'info',
                    'label' => 'Stato',
                ],
            ],
        ],
    ],
];
```

## Best Practices

### 1. Organizzazione delle Icone
- Separare le icone per contesto (navigation, actions, status)
- Mantenere una struttura coerente tra i moduli
- Utilizzare sottodirectory logiche per organizzare le icone

### 2. Accessibilità
- Includere sempre `aria-hidden="true"` e `role="img"`
- Utilizzare colori accessibili e contrasto adeguato
- Fornire alternative testuali quando necessario

### 3. Animazioni
- Utilizzare animazioni subtle e non invasive
- Implementare hover states per feedback visivo
- Mantenere le animazioni performanti

### 4. File di Traduzione
- Organizzare le traduzioni in modo gerarchico
- Includere descrizioni e tooltip
- Mantenere coerenza nei nomi delle chiavi

### 5. Manutenzione
- Ottimizzare regolarmente i file SVG
- Rimuovere attributi non necessari
- Mantenere un registro delle icone utilizzate

## Esempi Pratici

### Icona di Navigazione
```xml
<!-- navigation/module-feature-icon.svg -->
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke="currentColor">
    <style>
        .icon-element { transition: transform 0.3s; }
        svg:hover .icon-element { transform: scale(1.1); }
    </style>
    <path class="icon-element" 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M12 ..." />
</svg>
```

### File di Traduzione
```php
// lang/it/module.php
return [
    'navigation' => [
        'name' => 'Nome Modulo',
        'icon' => 'module-icon',
        'sections' => [
            'feature' => [
                'icon' => 'module-feature-icon',
                'label' => 'Funzionalità',
            ],
        ],
    ],
];
```

## Checklist di Implementazione

1. [ ] Struttura directory corretta
2. [ ] Naming conventions rispettate
3. [ ] Animazioni ottimizzate
4. [ ] File di traduzione organizzati
5. [ ] Accessibilità implementata
6. [ ] SVG ottimizzati
7. [ ] Documentazione aggiornata 