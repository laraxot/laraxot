# Sistema Icone SVG - Standard Laraxot

## Panoramica

Questo documento definisce gli standard e le best practices per il sistema di icone SVG personalizzate utilizzato in Laraxot, seguendo le convenzioni del tema dark e lo stile Heroicon outline.

## Principi Fondamentali

### 1. Architettura Modulare
- **Isolamento**: Ogni modulo gestisce le proprie icone SVG
- **Coerenza**: Stile uniforme tra tutti i moduli
- **Manutenibilità**: Organizzazione logica e documentata

### 2. Convenzioni Naming
- **File**: `icon.svg` per l'icona principale del modulo
- **Varianti**: `icon1.svg`, `icon2.svg`, `icon3.svg` per varianti aggiuntive
- **Specifiche**: `{module-name}-{functionality}.svg` per funzionalità specifiche

### 3. Struttura Standardizzata
```
laravel/Modules/{ModuleName}/resources/svg/
├── icon.svg              # Icona principale del modulo
├── icon1.svg             # Prima variante
├── icon2.svg             # Seconda variante
├── icon3.svg             # Terza variante
├── {functionality}.svg   # Icone per funzionalità specifiche
└── .gitkeep              # Mantiene la cartella nel repository
```

## Standard di Design

### 1. Stile Heroicon Outline
- **Stroke**: `stroke="currentColor"` per adattarsi al tema
- **Fill**: `fill="none"` per mantenere lo stile outline
- **Stroke Width**: `stroke-width="1.5"` per spessore consistente
- **ViewBox**: `viewBox="0 0 24 24"` per dimensioni standard

### 2. Tema Dark Ready
- **Colori**: Utilizzare `currentColor` per adattarsi automaticamente
- **Contrasto**: Garantire visibilità su sfondi scuri
- **Accessibilità**: Mantenere contrasto sufficiente

### 3. Animazioni
- **Hover**: Transizioni fluide al passaggio del mouse
- **Stato Attivo**: Animazioni per stati interattivi
- **Performance**: Animazioni CSS per performance ottimali

## Struttura Icona Standard

### 1. Template Base
```svg
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="w-6 h-6 transition-all duration-200 hover:scale-110 hover:stroke-2">
    <!-- Path dell'icona -->
</svg>
```

### 2. Classi CSS Standard
- **Dimensioni**: `w-6 h-6` per dimensioni standard
- **Transizioni**: `transition-all duration-200` per animazioni fluide
- **Hover**: `hover:scale-110 hover:stroke-2` per effetti interattivi

### 3. Responsive Design
- **Scalabilità**: Icone vettoriali per ogni dimensione
- **Mobile**: Ottimizzate per dispositivi touch
- **Desktop**: Supporto per alta risoluzione

## Moduli e Icone

### 1. Employee Module ✅
- **Stato**: Icone complete e implementate
- **File**: `icon.svg`, `icon1.svg`, `icon2.svg`, `icon2.svg`
- **Tema**: Dark ready con animazioni
- **Stile**: Heroicon outline elegante

### 2. Xot Module ✅
- **Stato**: Icone complete e implementate
- **File**: `icon.svg` e molte altre icone specifiche
- **Tema**: Dark ready con animazioni
- **Stile**: Heroicon outline elegante

### 3. User Module ✅
- **Stato**: Icone complete e implementate
- **File**: `icon.svg` e icone per funzionalità specifiche
- **Tema**: Dark ready con animazioni
- **Stile**: Heroicon outline elegante

### 4. Altri Moduli
- **Tenant**: Verificare e implementare se necessario
- **UI**: Verificare e implementare se necessario
- **Notify**: Verificare e implementare se necessario
- **Media**: Verificare e implementare se necessario
- **Lang**: Verificare e implementare se necessario
- **Job**: Verificare e implementare se necessario
- **Geo**: Verificare e implementare se necessario
- **Gdpr**: Verificare e implementare se necessario
- **Activity**: Verificare e implementare se necessario
- **Cms**: Verificare e implementare se necessario
- **Chart**: Verificare e implementare se necessario
- **TechPlanner**: Verificare e implementare se necessario

## Best Practices Implementazione

### 1. Creazione Icone
- **Design**: Seguire lo stile Heroicon outline
- **Tema**: Preparare per tema dark
- **Animazioni**: Includere transizioni CSS
- **Responsive**: Garantire scalabilità

### 2. Organizzazione File
- **Struttura**: Mantenere organizzazione logica
- **Naming**: Usare convenzioni consistenti
- **Versioning**: Creare varianti numerate
- **Documentazione**: Aggiornare docs

### 3. Integrazione
- **Blade**: Utilizzare componenti Blade per le icone
- **CSS**: Implementare animazioni e transizioni
- **JavaScript**: Aggiungere interattività se necessario
- **Testing**: Verificare su diversi temi

## Esempi di Implementazione

### 1. Icona Employee
```svg
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="w-6 h-6 transition-all duration-200 hover:scale-110 hover:stroke-2">
    <path stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
</svg>
```

### 2. Icona User
```svg
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="w-6 h-6 transition-all duration-200 hover:scale-110 hover:stroke-2">
    <path stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
</svg>
```

### 3. Icona Xot
```svg
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="w-6 h-6 transition-all duration-200 hover:scale-110 hover:stroke-2">
    <path stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423L16.5 15.75l.394 1.183a2.25 2.25 0 001.423 1.423L19.5 18.75l-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
</svg>
```

## CSS per Animazioni

### 1. Transizioni Base
```css
.icon-svg {
    transition: all 0.2s ease-in-out;
}

.icon-svg:hover {
    transform: scale(1.1);
    stroke-width: 2;
}
```

### 2. Animazioni Avanzate
```css
.icon-svg {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.icon-svg:hover {
    transform: scale(1.15) rotate(5deg);
    stroke-width: 2;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
}
```

### 3. Stati Interattivi
```css
.icon-svg:active {
    transform: scale(0.95);
}

.icon-svg:focus {
    outline: 2px solid currentColor;
    outline-offset: 2px;
}
```

## Integrazione con Filament

### 1. Utilizzo nelle Risorse
```php
'icon' => 'heroicon-o-user', // Icona Heroicon standard
'icon' => 'employee-icon',    // Icona personalizzata modulo
'icon' => 'user-main',        // Icona specifica funzionalità
```

### 2. Registrazione Automatica
- **XotBaseServiceProvider**: Registra automaticamente le icone
- **BladeIconsFactory**: Gestisce il rendering delle icone
- **Prefisso Modulo**: Aggiunge automaticamente il prefisso del modulo

### 3. Componenti Blade
```blade
<x-icon name="employee-icon" class="w-6 h-6" />
<x-icon name="user-main" class="w-8 h-8 text-blue-500" />
<x-icon name="xot-module" class="w-5 h-5" />
```

## Manutenzione e Aggiornamenti

### 1. Controlli Regolari
- **Verifica Esistenza**: Controllare presenza `icon.svg` in ogni modulo
- **Qualità Design**: Verificare coerenza con standard
- **Performance**: Ottimizzare animazioni e transizioni
- **Accessibilità**: Mantenere contrasto e leggibilità

### 2. Aggiornamenti
- **Nuove Icone**: Creare per nuove funzionalità
- **Varianti**: Aggiungere varianti numerate
- **Documentazione**: Aggiornare docs per nuove icone
- **Testing**: Verificare su diversi temi e dispositivi

### 3. Versioning
- **Icone Principali**: Mantenere `icon.svg` come standard
- **Varianti**: Usare numerazione progressiva
- **Backward Compatibility**: Mantenere compatibilità con versioni precedenti

## Troubleshooting

### 1. Problemi Comuni
- **Icona Non Visualizzata**: Verificare registrazione in XotBaseServiceProvider
- **Stile Non Applicato**: Controllare classi CSS e transizioni
- **Animazioni Non Funzionanti**: Verificare supporto CSS e JavaScript
- **Tema Non Compatibile**: Testare su diversi temi

### 2. Soluzioni
- **Cache**: Pulire cache Laravel e Filament
- **Registrazione**: Verificare registrazione automatica icone
- **CSS**: Controllare conflitti di stile
- **JavaScript**: Verificare errori console

## Collegamenti e Riferimenti

### Documentazione Interna
- [Employee Module](../laravel/Modules/Employee/docs/)
- [Xot Module](../laravel/Modules/Xot/docs/)
- [User Module](../laravel/Modules/User/docs/)
- [Icon System Implementation](../laravel/Modules/Employee/docs/custom_icon_implementation.md)

### Risorse Esterne
- [Heroicons](https://heroicons.com/) - Icone di riferimento
- [Blade Icons](https://github.com/blade-ui-kit/blade-icons) - Sistema Blade
- [SVG Best Practices](https://developer.mozilla.org/en-US/docs/Web/SVG) - Standard SVG

---

**IMPORTANTE**: Ogni modulo deve avere almeno `icon.svg` nella cartella `resources/svg/`. Le icone devono seguire lo stile Heroicon outline, essere pronte per il tema dark e includere animazioni CSS per hover e interazioni.
