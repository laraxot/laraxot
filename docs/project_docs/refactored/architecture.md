# Architettura del Progetto

## Panoramica

Il progetto è strutturato come un'applicazione Laravel 10+ con Filament 3.x per l'admin panel. Utilizza un'architettura modulare basata su Laraxot per la gestione dei temi e dei componenti.

## Struttura delle Directory

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── config/
├── database/
├── docs/
├── laravel/
│   └── Themes/
│       └── One/
│           ├── src/
│           ├── resources/
│           └── docs/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
└── tests/
```

## Componenti Principali

### 1. Sistema dei Temi

Il progetto utilizza un sistema di temi multipli basato su Laraxot. I temi disponibili sono:

- **Sixteen** (tema attivo): Tema principale del progetto
- **Two**: Tema alternativo
- **Zero**: Tema base/fallback

Il tema attivo è configurato in `laravel/config/local/techplanner/xra.php` con la chiave `pub_theme`.

**Configurazione corrente**: `'pub_theme' => 'Sixteen'`

Per dettagli sui componenti dei temi, vedere [Theme Components](./theme_components.md).

#### Struttura dei Temi

```
laravel/Themes/
├── Sixteen/                    # Tema attivo
│   ├── resources/
│   │   └── views/
│   │       └── components/
│   │           ├── blocks/
│   │           │   └── navigation/
│   │           │       └── simple.blade.php
│   │           └── layouts/
│   ├── theme.json
│   └── app/
├── Two/                        # Tema alternativo
│   ├── resources/views/
│   ├── lang/
│   └── theme.json
└── Zero/                       # Tema base
    ├── resources/views/
    ├── theme.json
    └── components/
```

#### Scelte Architetturali

Durante la risoluzione dei conflitti sono state prese le seguenti decisioni architetturali:

1. **Standardizzazione dei Nomi**:
   - Utilizzo del namespace `laraxot` per i pacchetti generici
   - Nomi dei pacchetti in formato snake_case
   - Documentazione standardizzata in Markdown

2. **Configurazione**:
   - Mantenimento della configurazione base di Vite
   - Aggiunta di alias per i componenti del tema
   - Supporto per TypeScript e PostCSS

3. **Dipendenze**:
   - Allineamento con i requisiti di Filament 3.x
   - Utilizzo di versioni specifiche
   - Documentazione chiara delle dipendenze

4. **Documentazione**:
   - Mantenimento della documentazione generica
   - Aggiunta di riferimenti incrociati
   - Standardizzazione del formato

### 2. Admin Panel (Filament)

L'admin panel è basato su Filament 3.x e include:
- Gestione utenti
- Gestione contenuti
- Configurazione del tema
- Personalizzazione dell'interfaccia

### 3. Frontend

Il frontend è basato su:
- Tailwind CSS
- Alpine.js
- TypeScript
- Vite

## Flusso dei Dati

1. **Richiesta HTTP**:
   - Routing (Laravel)
   - Middleware
   - Controller

2. **Business Logic**:
   - Services
   - Models
   - Actions (Spatie)

3. **Presentazione**:
   - Views (Blade)
   - Components
   - Assets

## Sicurezza

1. **Autenticazione**:
   - Laravel Sanctum
   - Filament Auth

2. **Autorizzazione**:
   - Policies
   - Gates
   - Roles & Permissions

3. **Validazione**:
   - Form Requests
   - Data Objects (Spatie)

## Performance

1. **Caching**:
   - Route Cache
   - Config Cache
   - View Cache

2. **Assets**:
   - Vite
   - Tailwind
   - TypeScript

3. **Database**:
   - Indexes
   - Eager Loading
   - Query Optimization

## Manutenzione

1. **Logging**:
   - Laravel Log
   - Error Tracking
   - Performance Monitoring

2. **Testing**:
   - Unit Tests
   - Feature Tests
   - Browser Tests

3. **Documentazione**:
   - API Docs
   - Code Docs
   - User Guides

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 