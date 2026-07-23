# TwentyOne Icon Rendering Policy

Il tema `TwentyOne` segue la policy globale di rendering icone:

- preferire `<x-filament::icon>`
- usare `<x-filament::icon-button>` per bottoni icona
- usare `@svg(...)` per icone custom registrate del progetto

## Applicazione pratica

Nel blocco `features/simple.blade.php` la renderizzazione e' stata riallineata a:

```blade
<x-filament::icon :icon="$item['icon']" class="w-8 h-8 text-indigo-400" />
```

Questo evita pattern alternativi non necessari come `x-dynamic-component` per icone standard.
