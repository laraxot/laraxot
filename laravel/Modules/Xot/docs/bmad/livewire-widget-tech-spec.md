---
title: "Tech spec — campagna widget piattaforma"
type: tech-spec
module: Xot
status: approved
related:
  - ./livewire-widget-prd.md
  - ./livewire-widget-architecture.md
  - ./livewire-widget-advantages.md
---

# Tech spec Xot

**Non implementare in questa sessione.**

1. Grep `extends XotBaseComponent` nei moduli: nuovi figli admin = violazione FR-X001.
2. Grep `Blade::render("@livewire('` nei `AdminPanelProvider`: User 3 hook, Notify 1 vendor.
3. Viste `manage_lang_module`: se `class_exists` fallisce, ritirare le Blade o puntare a resource Lang.

Test: PHPStan max sui widget nuovi; smoke `/admin` 200.
