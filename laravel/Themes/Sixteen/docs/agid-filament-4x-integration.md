# Integrazione AGID con Filament 4.x - Tema Sixteen

## Panoramica

Il tema Sixteen è stato aggiornato per essere completamente compatibile con Filament 4.x mantenendo la conformità al design system AGID (Agenzia per l'Italia Digitale) come mostrato nel sito di riferimento [https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html).

## Caratteristiche Principali

### ✅ Design System AGID
- **Colori ufficiali**: Utilizzo dei colori primari AGID (#0066CC, #00B373, #D9364F, #F5A623)
- **Bootstrap Italia**: Integrazione completa del framework CSS ufficiale
- **Accessibilità**: Conformità alle linee guida AGID per l'accessibilità

### ✅ Compatibilità Filament 4.x
- **Tailwind CSS 4.x**: Configurazione aggiornata per la versione più recente
- **Componenti Filament**: Stili personalizzati per tutti i componenti Filament
- **Build System**: Processo di build ottimizzato con Vite 6.x

## Struttura File

```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   ├── app.css                 # CSS principale con stili Filament
│   │   ├── bootstrap-italia.css    # Design system AGID
│   │   └── agid-colors.css         # Colori personalizzati AGID
│   └── js/
│       ├── app.js                  # JavaScript principale
│       └── filament-4x.js          # Logica specifica Filament 4.x
├── tailwind.config.js              # Configurazione Tailwind CSS 4.x
├── vite.config.js                  # Configurazione Vite per build
└── composer.json                   # Dipendenze PHP Filament 4.x
```

## Colori AGID Implementati

### Colori Primari
- **Blu Italia**: `#0066CC` (colore primario)
- **Verde Successo**: `#00B373` (azioni positive)
- **Rosso Errore**: `#D9364F` (azioni negative)
- **Giallo Avviso**: `#F5A623` (avvisi e warning)

### Palette Completa
Ogni colore include una scala completa da 50 a 900 per garantire flessibilità nel design.

## Componenti Filament Personalizzati

### Bottoni
```css
.filament-button-primary {
    background-color: #0066CC; /* Blu Italia */
    color: white;
}

.filament-button-success {
    background-color: #00B373; /* Verde Successo */
    color: white;
}
```

### Form
- Input con focus su colore primario AGID
- Validazione con colori di stato appropriati
- Placeholder e help text conformi alle linee guida

### Tabelle
- Header con sfondo blu Italia
- Hover states con colori AGID
- Paginazione personalizzata

### Modali e Notifiche
- Bordi e ombre conformi al design system
- Colori di stato per successo, errore, avviso

## Build Process

### Comandi Disponibili
```bash
# Build standard
npm run build

# Build per Filament 4.x
npm run build:filament

# Pubblicazione asset
npm run copy

# Build completo con pubblicazione
npm run filament:build
```

### Configurazione Vite
Il file `vite.config.js` è configurato per:
- Chunking ottimizzato per Filament
- Output personalizzato per componenti specifici
- Integrazione con Laravel Vite Plugin

## Conformità AGID

### Accessibilità
- Contrasto colori conforme WCAG 2.1 AA
- Focus indicators visibili
- Supporto screen reader

### Responsive Design
- Mobile-first approach
- Breakpoints conformi alle linee guida AGID
- Componenti adattivi

### Performance
- CSS ottimizzato e minificato
- JavaScript modulare
- Lazy loading per componenti pesanti

## Utilizzo

### 1. Installazione Dipendenze
```bash
cd laravel/Themes/Sixteen
composer install
npm install
```

### 2. Build Assets
```bash
npm run build
npm run copy
```

### 3. Configurazione Laravel
Il tema è automaticamente registrato tramite il Service Provider.

## Personalizzazione

### Colori Personalizzati
I colori AGID sono definiti in `resources/css/agid-colors.css` come variabili CSS:

```css
:root {
    --italia-blue-500: #0066CC;
    --italia-green-500: #00B373;
    --italia-red-500: #D9364F;
    --italia-yellow-500: #F5A623;
}
```

### Componenti Filament
Gli stili personalizzati sono in `resources/css/app.css` nella sezione `@layer components`.

## Troubleshooting

### Problemi Comuni

1. **Build Fallito**: Verificare che tutte le dipendenze siano installate
2. **Colori Non Applicati**: Controllare che `agid-colors.css` sia importato
3. **Stili Filament Mancanti**: Verificare che il build sia stato eseguito correttamente

### Debug
```bash
# Verifica configurazione Tailwind
npm run build -- --debug

# Controllo file generati
ls -la public/assets/
```

## Riferimenti

- [Design System AGID](https://italia.github.io/design-comuni-pagine-statiche/)
- [Filament 4.x Documentation](https://filamentphp.com/docs/4.x)
- [Tailwind CSS 4.x](https://tailwindcss.com/docs)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)

## Changelog

### v1.0.0 - Integrazione Filament 4.x
- ✅ Compatibilità completa con Filament 4.x
- ✅ Integrazione design system AGID
- ✅ Tailwind CSS 4.x
- ✅ Build system ottimizzato
- ✅ Documentazione completa

---

**Ultimo aggiornamento**: Settembre 2024  
**Versione**: 1.0.0  
**Compatibilità**: Filament 4.x, Tailwind CSS 4.x, Laravel 10+
