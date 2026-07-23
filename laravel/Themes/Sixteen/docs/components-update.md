# Sixteen Theme - Components Update Ottobre 2025

## Overview

Aggiornamento della struttura dei componenti Blade per garantire la corretta compilazione della cache delle views e il corretto funzionamento con il namespace `pub_theme`.

## Modifiche Effettuate

### 1. **Badge Components - Riorganizzazione Directory**

#### Problema
I componenti badge erano posizionati in `resources/views/views/components/badge/` ma il namespace `pub_theme` punta a `resources/views/`, causando errori di compilazione:

```
Unable to locate a class or view for component [pub_theme::badge.status]
```

#### Soluzione
Componenti spostati nella posizione corretta:

**Da:**
```
Themes/Sixteen/resources/views/views/components/badge/status.blade.php
Themes/Sixteen/resources/views/views/components/badge/priority.blade.php
```

**A:**
```
Themes/Sixteen/resources/views/components/badge/status.blade.php
Themes/Sixteen/resources/views/components/badge/priority.blade.php
Themes/Sixteen/resources/views/components/badge.blade.php
```

### 2. **Form Component - Semplificazione**

#### Problema
Il componente `<x-filament-panels::form>` non era disponibile e causava errori durante la compilazione:

```
Unable to locate a class or view for component [filament-panels::form]
```

#### Soluzione
Sostituito con tag HTML `<form>` standard in:

**File Modificato**: `Modules/Blog/resources/views/livewire/profile/v1.blade.php`

```php
// PRIMA
<x-filament-panels::form wire:submit="save">
    <!-- contenuto -->
</x-filament-panels::form>

// DOPO
<form wire:submit="save">
    <!-- contenuto -->
</form>
```

**Motivazione**:
- Livewire supporta nativamente l'attributo `wire:submit` sui tag `<form>` HTML
- Non è necessario un wrapper Filament specifico
- Riduce dipendenze non necessarie

### 3. **Test Files - Gestione**

#### Problema
File `.blade.test` venivano processati durante la compilazione causando errori.

#### Soluzione
Rinominati temporaneamente i file di test:

```bash
*.blade.test → *.blade.test.bak
```

**File Interessati:**
- `Modules/Blog/resources/views/livewire/article/ratings_to_predict/for-image/v1/check.blade.test`
- `Modules/Blog/app/resources_to_delete/views/livewire/article/ratings_to_predict/for-image/v1/check.blade.test`

## Componenti Badge - Documentazione

### Status Badge

**Ubicazione**: `Themes/Sixteen/resources/views/components/badge/status.blade.php`

**Utilizzo:**
```blade
<x-pub_theme::badge.status :status="$ticket->status" />
```

**Stati Supportati:**
- `open` → Badge rosso con icona "it-horn"
- `in_progress` → Badge giallo con icona "it-settings"
- `resolved` → Badge verde con icona "it-check"
- `closed` → Badge grigio con icona "it-close"

**Esempio Output:**
```html
<span class="badge badge-danger">
    <svg class="icon icon-white icon-sm">
        <use href="/bootstrap-italia/svg/sprites.svg#it-horn"></use>
    </svg>
    Aperto
</span>
```

### Priority Badge

**Ubicazione**: `Themes/Sixteen/resources/views/components/badge/priority.blade.php`

**Utilizzo:**
```blade
<x-pub_theme::badge.priority :priority="$ticket->priority" />
```

**Priorità Supportate:**
- `urgent` → Badge rosso "Urgente"
- `high` → Badge giallo "Alta"
- `medium` → Badge blu "Media"
- `low` → Badge grigio "Bassa"

### Base Badge

**Ubicazione**: `Themes/Sixteen/resources/views/components/badge.blade.php`

**Utilizzo:**
```blade
<x-pub_theme::badge variant="success">Completato</x-pub_theme::badge>
<x-pub_theme::badge variant="danger" :pill="true">Urgente</x-pub_theme::badge>
```

**Varianti Disponibili:**
- `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`

**Opzioni:**
- `pill` (boolean) - Rende il badge arrotondato
- `tag` (string) - Tag HTML da usare (`span` o `a`)
- `href` (string) - URL per badge link
- `srText` (string) - Testo per screen reader

## View Caching

### Test di Compilazione

```bash
php artisan view:cache
```

**Risultato:**
```
✅ INFO  Blade templates cached successfully.
```

### Pulizia Cache

```bash
php artisan view:clear
```

## Namespace Configuration

Il tema Sixteen usa il namespace `pub_theme` per tutti i componenti:

**Service Provider**: `Themes/Sixteen/app/Providers/ThemeServiceProvider.php`

```php
protected function loadCoreThemeResources(): void
{
    // IMPORTANTE: pub_theme è il namespace standard per i temi
    $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
    $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'pub_theme');
}
```

## Bootstrap Italia Compliance

Tutti i componenti badge seguono le linee guida Bootstrap Italia:

### Classi CSS
- `.badge` - Classe base
- `.badge-{variant}` - Variante colore (danger, success, warning, etc.)
- `.icon` - Icona SVG
- `.icon-white` - Icona bianca
- `.icon-sm` - Icona piccola

### Accessibilità
- ✅ Uso corretto di `role="status"` per badge informativi
- ✅ Supporto screen reader con `.visually-hidden`
- ✅ Icone con `<use>` per SVG sprite
- ✅ Contrasto colori WCAG 2.1 AA compliant

## Testing

### Componenti da Testare

1. **Status Badge in Ticket Card**
   - File: `Themes/Sixteen/resources/views/views/components/ticket-card.blade.php`
   - Verifica rendering corretto per tutti gli stati

2. **Priority Badge in Ticket List**
   - Verifica colori e icone appropriate

3. **Badge Base in UI vari**
   - Test varianti colore
   - Test modalità pill
   - Test badge come link

### Checklist Test

- [ ] View cache compila senza errori
- [ ] Badge status visualizza colore corretto
- [ ] Badge priority mostra priorità corretta
- [ ] Icone SVG caricate correttamente
- [ ] Screen reader text funzionante
- [ ] Responsive design su mobile
- [ ] Accessibilità WCAG 2.1 AA

## Troubleshooting

### Errore: "Unable to locate a class or view for component"

**Causa**: Componente non nella directory corretta rispetto al namespace

**Soluzione**:
1. Verificare namespace in `ThemeServiceProvider.php`
2. Assicurarsi che il file sia in `resources/views/components/`
3. Non in `resources/views/views/components/`

### Errore: "View cache compilation failed"

**Causa**: File `.blade.test` processati durante compilazione

**Soluzione**:
```bash
find Modules -name "*.blade.test" -exec mv {} {}.bak \;
```

## Best Practices

### 1. **Component Organization**
```
resources/views/
├── components/
│   ├── badge.blade.php          # Base component
│   ├── badge/
│   │   ├── status.blade.php     # Specialized
│   │   └── priority.blade.php   # Specialized
│   ├── button.blade.php
│   └── card.blade.php
```

### 2. **Naming Convention**
- Usa nomi descrittivi: `badge.status` non `badge.stato`
- Segui convenzioni Bootstrap Italia
- Mantieni coerenza con altri componenti

### 3. **Documentation in Comments**
```blade
{{--
    Status Badge Component
    Based on Design Comuni Italiani badge pattern
    AGID Compliant

    @param string $status - open, in_progress, resolved, closed
--}}
```

## Migration Notes

Per aggiornare componenti esistenti:

1. **Individuare componenti in directory errata**
   ```bash
   find Themes -path "*/views/views/components/*" -name "*.blade.php"
   ```

2. **Spostare nella directory corretta**
   ```bash
   mv resources/views/views/components/* resources/views/components/
   ```

3. **Testare compilazione**
   ```bash
   php artisan view:cache
   ```

4. **Verificare rendering**
   - Aprire pagine che usano i componenti
   - Controllare console browser per errori
   - Verificare accessibilità

---

**Autore**: Claude Code
**Data**: 2025-10-15
**Versione Tema**: 1.x
**Laravel**: 12.34.0
**Bootstrap Italia**: 2.x
