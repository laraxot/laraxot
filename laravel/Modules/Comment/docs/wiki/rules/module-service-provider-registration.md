---
title: "Module Service Provider Registration Rule"
type: rule
tags: [module, service-provider, registration, laravel, architecture]
created: 2026-06-06
updated: 2026-06-06
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/3"
related:
  - ../../../composer.json
  - ../../../module.json
  - ../concepts/module-architecture-overview.md
---

# Module Service Provider Registration Rule

## Perché NON chiamare `$this->app->register()` dentro un provider

### Contesto
Nei moduli Laravel (Modules/*), i Service Providers vengono caricati in base a quanto definito in:
- `module.json` → `providers`
- `composer.json` → `extra.laravel.providers`

### Anti-pattern
```php
// ❌ SBAGLIATO - CommentServiceProvider.php
class CommentServiceProvider extends XotBaseServiceProvider
{
    public function register(): void
    {
        parent::register();
        
        // Questo è un anti-pattern!
        $this->app->register(CommentEngineServiceProvider::class);
    }
}
```

### Perché è sbagliato
1. **Doppia registrazione**: `CommentEngineServiceProvider` è già in `module.json`, verrà caricato automaticamente.
2. **Ordine non deterministico**: il provider figlio potrebbe non essere pronto quando chiamato.
3. **Overhead**: aggiunge complessità e potenziali conflitti.

### Soluzione corretta
```php
// ✅ CORRETTO - module.json
{
    "providers": [
        "Modules\\Comment\\Providers\\CommentServiceProvider",
        "Modules\\Comment\\Providers\\CommentEngineServiceProvider"
    ]
}
```

```php
// ✅ CommentServiceProvider.php - semplicemente estendi, niente registrazioni manuali
class CommentServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Comment';
    protected string $module_dir = __DIR__;
    protected string $module_ns = __NAMESPACE__;
}
```

## Regola
> **I provider secondari vengono dichiarati in `module.json`. I provider principali non devono registrare altri provider manualmente.**

## Verifica
- [ ] `module.json` elenca tutti i provider necessari
- [ ] Nessun `$this->app->register()` nel codice
- [ ] Provider principali estendono solo la classe base del modulo

## Riferimenti
- [Laravel Module Documentation](https://laravel-modules.com/docs)
- [module.json Schema](../../../../docs/wiki/concepts/module-json-schema.md)