# Analisi Componente ui.service-card - Report Tecnico

## Data: 2026-02-07
## Problema: Unable to locate a class or view for component [ui.service-card]

---

## 1. SITUAZIONE ATTUALE

### Dove esiste il componente service-card:

1. **Modulo UI** (Componente agnostico)
   - Path: `Modules/UI/resources/views/components/ui/service-card.blade.php`
   - Namespace: `ui::ui.service-card`
   - Props: title, description, icon, url, category, status, requiresAuth, badge
   - Colori: AGID compliant (anagrafe=blue, tributi=green, urbanistica=orange, sociale=purple, ambiente=emerald)

2. **Theme Two** (Componente tema-specifico)
   - Path: `Themes/Two/resources/views/components/ui/service-card.blade.php`
   - Namespace: `ui.service-card` (tema attivo)
   - Props: title, description, icon, url, category, status, id, class, featured, image, color
   - Stile: Bootstrap Italia / AGID design system

3. **Theme Sixteen** (Altra versione tema-specifica)
   - Path: `Themes/Sixteen/resources/views/components/ui/service-card.blade.php`
   - Namespace: `ui.service-card` (se Sixteen è attivo)

---

## 2. ARCHITETTURA REGISTRAZIONE COMPONENTI

### Flusso Registrazione (XotBaseServiceProvider):

```php
public function registerBladeComponents(): void
{
    // 1. Registra path componenti anonimi
    $componentViewPath = app(GetModulePathByGeneratorAction::class)
        ->execute($this->name, 'component-view');
    // Risultato: Modules/UI/resources/views/components
    
    Blade::anonymousComponentPath($componentViewPath);  // Registra path
    
    // 2. Registra namespace componenti class-based
    $namespace = $this->module_ns.'\View\Components';
    Blade::componentNamespace($namespace, $this->nameLower);  // es: 'ui'
}
```

### Ordine Risoluzione Componenti in Laravel:

1. **Componenti del Tema Attivo** (priorità massima)
   - `Themes/{ActiveTheme}/resources/views/components/ui/service-card.blade.php`
   - Accesso: `<x-ui.service-card />`

2. **Componenti Modulo via anonymousComponentPath**
   - `Modules/UI/resources/views/components/ui/service-card.blade.php`
   - Accesso: `<x-ui.service-card />` (se non trovato nel tema)

3. **Componenti Class-Based via componentNamespace**
   - `Modules/UI/app/View/Components/ServiceCard.php`
   - Accesso: `<x-ui::service-card />` (con doppio ::)

---

## 3. CAUSE POSSIBILI DELL'ERRORE

### A. Cache Blade non aggiornata
```bash
php artisan view:clear
php artisan cache:clear
```

### B. Tema attivo non ha il componente
Se il tema attivo è Sixteen ma il componente è solo in Theme Two:
- Theme Sixteen non trova `resources/views/components/ui/service-card.blade.php`
- Fallback al modulo UI potrebbe non funzionare se la registrazione è fallita

### C. Registrazione modulo UI fallita
Possibili cause:
- Modulo UI non caricato (disabilitato in config/modules.php)
- Errore in UIServiceProvider
- GetModulePathByGeneratorAction ritorna path errato

### D. Conflitto di nomi
Se esistono entrambi:
- `Themes/Two/resources/views/components/ui/service-card.blade.php`
- `Modules/UI/resources/views/components/ui/service-card.blade.php`

Laravel usa sempre quello del tema (shadowing del modulo).

---

## 4. SOLUZIONI

### Soluzione 1: Verificare Cache
```bash
cd /var/www/html/ptvx/laravel
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Soluzione 2: Verificare Tema Attivo
Controllare quale tema è attivo:
```php
// In un controller o route
$activeTheme = config('theme.active'); // o simile
$viewPaths = config('view.paths');
dd($viewPaths);
```

### Soluzione 3: Copiare componente nel tema attivo
Se il tema attivo non ha il componente, copiarlo:
```bash
# Da Theme Two a Theme attivo
cp Themes/Two/resources/views/components/ui/service-card.blade.php \
   Themes/{ActiveTheme}/resources/views/components/ui/service-card.blade.php
```

### Soluzione 4: Forzare uso modulo UI
Se il componente del tema è rotto, usare quello del modulo UI:
```blade
{{-- Invece di --}}
<x-ui.service-card ... />

{{-- Usare namespace modulo esplicito --}}
<x-ui::ui.service-card ... />
```

### Soluzione 5: Registrare componente manualmente
Nel ThemeServiceProvider:
```php
use Illuminate\Support\Facades\Blade;

public function boot(): void
{
    // Registra componenti UI del tema
    Blade::anonymousComponentPath(
        __DIR__ . '/../../resources/views/components',
        'ui'
    );
}
```

---

## 5. DOVE METTERE IL COMPONENTE - BEST PRACTICES

### Mettere nel Modulo UI se:
- ✅ Componente è agnostico (non dipende dal tema)
- ✅ Usato da più temi
- ✅ Logica di business complessa
- ✅ Colori/props standardizzati (AGID)
- ✅ Deve essere sovrascrivibile dai temi

### Mettere nel Tema se:
- ✅ Componente è specifico del design del tema
- ✅ Usa classi CSS specifiche del tema (es: Bootstrap Italia)
- ✅ Ha layout/UX unico del tema
- ✅ Non serve condividerlo con altri temi
- ✅ Deve "shadoware" (sovrascrivere) quello del modulo UI

---

## 6. VERIFICA IMPLEMENTAZIONE CORRETTA

### Checklist per Componente Modulo UI:
- [ ] File in `Modules/UI/resources/views/components/ui/`
- [ ] Props documentati in PHPDoc
- [ ] Colori AGID compliant
- [ ] Accessibilità (ARIA labels)
- [ ] Registrato automaticamente via UIServiceProvider

### Checklist per Componente Tema:
- [ ] File in `Themes/{Name}/resources/views/components/ui/`
- [ ] Namespace corrisponde a `ui.{componente}`
- [ ] Non duplica logica del modulo se non necessario
- [ ] Documentazione in `docs/components.md`

---

## 7. DOCUMENTAZIONE AGGIORNAMENTO

### File da aggiornare/che altri agenti AI dovrebbero leggere:

1. **Modules/UI/docs/components.md**
   - Documentare service-card component
   - Specificare props e colori AGID
   - Esempi di utilizzo

2. **Themes/Two/docs/components.md** (creare se non esiste)
   - Documentare override del componente
   - Specificare differenze con versione modulo UI
   - Quando usare versione tema vs modulo

3. **docs/component-architecture.md** (root docs)
   - Spiegare gerarchia risoluzione componenti
   - Ordine: Tema → Modulo UI → Modulo Xot
   - Best practices per creare nuovi componenti

---

## 8. NOTE PER AGENTI AI FUTURI

### Quando trovate "Unable to locate component":

1. **Prima verificare esistenza**:
   ```bash
   find . -name "service-card.blade.php" -type f 2>/dev/null
   ```

2. **Poi verificare registrazione**:
   - Controllare se il tema attivo ha il componente
   - Controllare se il modulo UI è caricato
   - Verificare `UIServiceProvider::registerBladeComponents()`

3. **Infine verificare cache**:
   ```bash
   php artisan view:clear
   ```

### Pattern di Shadowing (Sovrascrittura):

```
Modulo UI:    Modules/UI/resources/views/components/ui/card.blade.php
Theme Two:    Themes/Two/resources/views/components/ui/card.blade.php ← USA QUESTO
```

Il tema sovrascrive sempre il modulo. Questo è voluto per permettere temi personalizzati.

---

## CONCLUSIONE

Il componente `ui.service-card` **esiste sia nel modulo UI che nei temi**.

L'errore "Unable to locate" è probabilmente dovuto a:
1. **Cache** - Risolvibile con `php artisan view:clear`
2. **Tema sbagliato attivo** - Verificare quale tema è caricato
3. **Path errato** - Verificare che il componente sia nella posizione corretta

**Raccomandazione**: Mantenere il componente nel **tema attivo (Theme Two)** perché è specifico del design del tema (Bootstrap Italia/AGID), mentre la versione modulo UI è più generica.

---

*Report generato da analisi agent AI - 2026-02-07*
