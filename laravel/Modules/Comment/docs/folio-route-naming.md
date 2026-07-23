# Folio Route Naming Convention Guide

## Regola DRUCOL
Questa è una regola *DRUCOL* (Dry + Kiss) per mantenere una struttura coerente e minimale nelle pagine della panoramica admin:

### Naming delle Route
- **Pattern obbligatorio**: `{parent}.index` (es: `container0.index`, `container1.index`)
- **Nessuna variante**: non usare `.list`, `.create`, `.edit` nei nomi delle route
- **Principio di minimalità**: numerare solo le componenti essenziali per l'identificazione

### Wrapper Volt
- **Struttura obbligatoria**:
  ```blade
  <x-layouts.app>
      @volt('route.name')
      <div ...>
        <x-page ... />
      </div>
      @endvolt
  </x-layouts.app>
  ```
- **Match-name**: il nome dentro `@volt('NAME')` deve corrispondere esattamente al nome della route dichiarato con `name()`
- **Component minimale**: la classe Volt deve contenere **solo** proprietà dichiarate nel mount() come stringhe/tipi primitivi

### Middleware Comune
- **PageSlugMiddleware** è spesso usato per gestire automaticamente il slug della pagina
- **Uso**: applicare a livello globale nel `$routeMiddleware` o contestualizzato per gruppi di route

### Esempio Completo
```php
// routes/web.php
name('container0.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $pageSlug = '';

    public function mount(string $container0): void {
        $this->container0 = $container0;
        $this->pageSlug = $container0.'.index';
        $this->data = ['container0' => $container0];
    }
};
?>
```

```blade
<!-- resources/views/pages/container0/index.blade.php -->
<x-layouts.app>
    @volt('container0.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

### Perché questa struttura?
- **Coerenza**: pattern uniforme tra naming, routing e invocation
- **Scalabilità**: facile aggiungere nuove sezioni mantenendo lo stesso standard
- **Manutenibilità**: diagnosi rapida di errori basata su naming non conforme

> **Nota**: Qualsiasi deviazione da `.index` o mancato matching con `@volt()` genera errori 404 o componenti non montate.