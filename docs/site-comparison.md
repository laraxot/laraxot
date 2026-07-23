# Analisi Comparativa Siti Web

## Sito Locale: http://127.0.0.1:8000/it
- **Tecnologia**: Laravel + Filament v4 + Tailwind CSS
- **Tema**: TechPlanner con tema Two
- **Lingua**: Italiano
- **Contenuto**: Piattaforma di gestione aziendale (HR, Analytics, Automazione)

## Sito Produzione: https://lightseagreen-dogfish-560272.hostingersite.com/
- **Tecnologia**: React/Vite + Hostinger Horizons
- **Tema**: Hostinger Horizons template
- **Lingua**: Inglese
- **Contenuto**: Sito placeholder di Hostinger

## Differenze Principali

### 1. Stack Tecnologico
| Aspect | Locale | Produzione |
|--------|--------|------------|
| Backend | Laravel PHP | React/Vite |
| Framework | Filament v4 | Hostinger Horizons |
| CSS | Tailwind CSS | CSS custom |
| JS | Livewire + Filament | Vanilla JS |

### 2. Struttura HTML
- **Locale**: HTML server-side renderizzato con Blade
- **Produzione**: SPA React con div#root

### 3. Contenuti
- **Locale**: TechPlanner - piattaforma B2B completa
- **Produzione**: Sito base Hostinger senza contenuti personalizzati

### 4. Asset e Styling
- **Locale**: File CSS compilati da Vite/Tailwind
- **Produzione**: Asset statici in /assets/

## Obiettivi di Allineamento

Per rendere il sito locale uguale a quello di produzione:

1. **Migrazione stack**: Da Laravel/Filament a React/Vite
2. **Conversione contenuti**: Adattare contenuti TechPlanner al nuovo template
3. **Asset management**: Sincronizzare immagini e risorse
4. **Routing**: Implementare routing lato client simile

## Prossimi Passi

1. Scaricare asset dal sito di produzione
2. Analizzare JavaScript per funzionalità dinamiche
3. Creare versione statica HTML/CSS/JS
4. Implementare nel tema Two locale