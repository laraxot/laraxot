---
title: "Religione — Http/Livewire → Filament widget"
type: constitution
module: Xot
status: approved
related:
  - ./livewire-inventory.md
  - ./livewire-widget-advantages.md
  - ../../../User/docs/bmad/livewire-inventory.md
---

# Project context: un contenitore UI per il panel

Costituzione **piattaforma**. I moduli foglia applicano, non riscrivono.

## Perché

Filament 5 è il panel. Un widget Filament **è** Livewire (`XotBaseWidget`). `Http\Livewire` è il guscio Jet/pagina FO. Due stack nello stesso chrome = alias stringa, hint path morti, `/admin` spento (già successo in User: `filament-jet`).

## Vincoli

1. Estendere `XotBaseWidget` / `XotBaseSchemaWidget`, mai `Filament\Widgets\Widget` o `Livewire\Component` nudo per UI admin.
2. **Non convertire** `XotBaseComponent`: è la base storica HTTP, non un controllo da montare.
3. Chrome (user menu, login-after): `$isDiscovered = false` + hook FQCN.
4. Pagina FO / token / legal ≠ widget forzato. HTTP gemello di un widget esistente = **ritiro**.
5. `ViewCopyAction` vietata in `render()`.
6. Nessuna Action di dominio nuova solo per cambiare guscio.
7. `phpstan.neon` immutabile. Lang `modulo::`, niente `->label()`.
8. Documentazione nel **modulo proprietario** (`docs/bmad/`), non in User.

## Urgenza

Ogni modulo che monta `@livewire('alias')` nel panel ha la stessa classe di difetto del 500 User. Identità (User) è P0; chrome UI/Lang/Notify è P1; HTTP orfani con `dd()`/`dddx` sono P0 locali.
