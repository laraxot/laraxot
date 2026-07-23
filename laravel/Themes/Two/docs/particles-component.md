# Componente Particles (Theme Two)

## Scopo

Effetto particelle animate per pannelli con sfondo gradient scuro (login, register). Canvas-based, leggero, non blocca interazioni.

## Utilizzo

```blade
@include('pub_theme::components.ui.particles')
```

Oppure come componente:

```blade
<x-pub_theme::ui.particles />
```

## Dove

- `resources/views/components/ui/particles.blade.php`
- Usato in: `pages/auth/login.blade.php` (pannello sinistro)

## Tecnologia

- Canvas HTML5 + Alpine.js
- 50 particelle bianche semi-trasparenti
- Movimento casuale con rimbalzo sui bordi
- `aria-hidden="true"` per accessibilità
- `pointer-events-none` per non bloccare click

## Requisiti

- Alpine.js (fornito da Livewire/Filament)
- Contenitore con `position: relative` e dimensioni definite

## Collegamenti

- [auth-login-page](auth-login-page.md)
- [component-library](component-library.md)
