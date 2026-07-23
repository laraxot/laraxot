# Standard Icone SVG - Modulo Employee

## Panoramica

Questo documento definisce gli standard e le best practices per le icone SVG del modulo Employee, seguendo le convenzioni Laraxot per tema dark e stile Heroicon outline.

## Stato Attuale

### Icone Implementate ✅
- **icon.svg** - Icona principale del modulo
- **icon1.svg** - Prima variante
- **icon2.svg** - Seconda variante
- **icon2.svg** - Terza variante
- **employee-icon.svg** - Icona specifica Employee
- **employee-icon1.svg** - Variante specifica Employee
- **employee-icon2.svg** - Variante specifica Employee

### Struttura File
```
laravel/Modules/Employee/resources/svg/
├── icon.svg              # Icona principale
├── icon1.svg             # Prima variante
├── icon2.svg             # Seconda variante
├── icon2.svg             # Terza variante
├── employee-icon.svg      # Icona specifica Employee
├── employee-icon1.svg     # Variante specifica Employee
├── employee-icon2.svg     # Variante specifica Employee
└── .gitkeep              # Mantiene la cartella
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

## Template Icona Standard

### 1. Struttura Base
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

## Icone Specifiche Employee

### 1. Icona Principale (icon.svg)
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

### 2. Variante 1 (icon1.svg)
```svg
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="w-6 h-6 transition-all duration-200 hover:scale-110 hover:stroke-2">
    <path stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
</svg>
```

### 3. Variante 2 (icon2.svg)
```svg
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="w-6 h-6 transition-all duration-200 hover:scale-110 hover:stroke-2">
    <path stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
</svg>
```

## Utilizzo nelle Risorse Filament

### 1. Navigazione
```php
'navigation' => [
    'label' => 'Dipendenti',
    'group' => 'Gestione Dipendenti',
    'icon' => 'employee-icon', // Icona personalizzata
    'sort' => 50,
],
```

### 2. Risorse
```php
class EmployeeResource extends XotBaseResource
{
    protected static ?string $navigationIcon = 'employee-icon';
    
    // ... resto della classe
}
```

### 3. Pagine
```php
class ListEmployees extends XotBaseListRecords
{
    protected static ?string $navigationIcon = 'employee-icon1';
    
    // ... resto della classe
}
```

## CSS per Animazioni

### 1. Transizioni Base
```css
.employee-icon {
    transition: all 0.2s ease-in-out;
}

.employee-icon:hover {
    transform: scale(1.1);
    stroke-width: 2;
}
```

### 2. Animazioni Avanzate
```css
.employee-icon {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.employee-icon:hover {
    transform: scale(1.15) rotate(5deg);
    stroke-width: 2;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
}
```

## Manutenzione

### 1. Controlli Regolari
- **Verifica Esistenza**: Controllare presenza di tutte le icone
- **Qualità Design**: Verificare coerenza con standard
- **Performance**: Ottimizzare animazioni e transizioni
- **Accessibilità**: Mantenere contrasto e leggibilità

### 2. Aggiornamenti
- **Nuove Icone**: Creare per nuove funzionalità
- **Varianti**: Aggiungere varianti numerate
- **Documentazione**: Aggiornare docs per nuove icone
- **Testing**: Verificare su diversi temi

## Collegamenti

- [Sistema Icone SVG Root](../../docs/svg_icon_system_standards.md)
- [Best Practices Lingue](language_best_practices.md)
- [README Modulo](README.md)

---

**IMPORTANTE**: Tutte le icone devono seguire lo stile Heroicon outline, essere pronte per il tema dark e includere animazioni CSS per hover e interazioni.
