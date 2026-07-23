# Filament Blade Components Usage (CMS)

## Collegamenti
- [Documentazione Root](../../../../../docs/project/collegamenti-documentazione.md) - Indice centrale dei collegamenti
- [Documentazione in Themes/One](../../../../themes/one/project_docs/filament-blade-components.md) - Contesto del tema principale

Regola canonica: [filament-first-rule.md](../../../../docs/wiki/rules/filament-first-rule.md). Documentazione **Filament 5**: https://filamentphp.com/docs/5.x/components/overview — usare sempre i componenti Blade Filament quando esistono (tab, button, modal, dropdown, badge).

## Perché usare `<x-filament::button>`
- **Stile e coerenza**: rispetta il tema e le varianti predefinite.
- **Accessibilità e funzionalità**: supporto integrato per attributi come `size`, `color`, `tag`, e altri.
- **Manutenzione semplificata**: meno markup custom, un’unica API per pulsanti e link.

### Esempio consigliato
```blade
<x-filament::button 
    size="sm" 
    href="{{ route('register.type', ['type' => $type]) }}" 
    tag="a"
>
    {{ ucfirst($type) }}
</x-filament::button>
```

### Cosa evitare
```blade
<a href="{{ route('register.type', ['type' => $type]) }}">
    <x-ui.button class="w-full">{{ ucfirst($type) }}</x-ui.button>
</a>
```

## Collegamenti tra versioni di filament-blade-components.md
* [filament-blade-components.md](laravel/modules/cms/project_docs/filament-blade-components.md)
* [filament-blade-components.md](laravel/themes/one/project_docs/filament-blade-components.md)

