---
title: "Modulo UI"
type: concept
tags: [docs]
created: 2026-07-14
updated: 2026-07-14
qmd: "ui modulo ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./activity.md"
  - "./ai.md"
  - "./cms.md"
  - "./index.md"
  - "./themes-sixteen.md"
---

# Modulo UI

## Descrizione
Il modulo UI fornisce componenti di interfaccia utente riutilizzabili per l'intero sistema. Questo modulo si concentra sulla creazione di elementi UI coerenti, accessibili e responsive.

## Componenti
- [Lista completa dei componenti](/Modules/UI/docs/components.md)
- [DarkModeSwitcher](/Modules/UI/docs/components.md#darkmodeswitcher) - Gestione tema chiaro/scuro con persistenza

## Livewire Components
Il modulo include componenti Livewire che possono essere facilmente integrati nelle view:

### DarkModeSwitcher
Componente per il toggle del tema chiaro/scuro:
```php
<livewire:ui::dark-mode-switcher />
```

**Caratteristiche:**
- Toggle tema chiaro/scuro
- Persistenza con cookie
- Icone dinamiche per modalità chiaro/scuro
- Compatibilità con Tailwind Dark Mode
- Integrazione con Livewire 3

**File importanti:**
- [DarkModeSwitcher.php](/Modules/UI/app/Http/Livewire/DarkModeSwitcher.php) - Controller Livewire
- [switcher.blade.php](/Modules/UI/resources/views/livewire/dark-mode/switcher.blade.php) - View Blade

## Tools e Utilities
- Form components
- Table components
- Chart components
- Layout components

## Collegamenti
- [Indice generale](/docs/index.md)
- [Documentazione moduli](/docs/modules/index.md)
- [Documentazione completa componenti](/Modules/UI/docs/components.md)

## Best Practices
1. Utilizzare i componenti UI esistenti anziché crearne di nuovi
2. Seguire le linee guida di accessibilità WCAG
3. Mantenere la coerenza visiva in tutta l'applicazione
4. Testare i componenti su diversi dispositivi e browser 
