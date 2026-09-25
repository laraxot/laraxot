---
title: "Product brief — ritiro Livewire UI"
type: product-brief
module: UI
related:
  - ./livewire-inventory.md
---

# Brief

Un solo switcher tema: `DarkModeSwitcherWidget`. HTTP `DarkModeSwitcher` esce. `Toast` **non** è orfano (montato in `components/layouts/main.blade.php:28` + `User layouts/main.blade.php:49`, chrome del front-office) e resta fuori da questa campagna. `InteractiveMap` (`app/Livewire/Components/Map`) è orfano e fuori scope: nessun widget mappa. Vedi [livewire-inventory.md](./livewire-inventory.md). Metrica corretta: `app/Http/Livewire` contiene solo `Toast.php` dopo il ritiro, non è vuoto.
