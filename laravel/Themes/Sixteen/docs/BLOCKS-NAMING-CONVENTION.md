# Naming Convention Blocks — Theme Sixteen

> **Canon aggiornato (2026-06-10)**: [blocks/folder-vocabulary.md](./blocks/folder-vocabulary.md) · regola [wiki/rules/cms-block-naming-tailwind-flowbite.md](./wiki/rules/cms-block-naming-tailwind-flowbite.md) · verifica `bash bashscripts/quality-gates/check-blocks-folder-names.sh`

Questo file è storico; per allowlist completa e cosa mettere in ogni cartella usare **folder-vocabulary.md**.

---

# Naming Convention Blocks - Theme Sixteen

> **Regola**: I nomi delle cartelle in `resources/views/components/blocks/` devono seguire Flowbite e Tailwind UI.

## Fonti Ufficiali

1. **Flowbite Blocks**: https://flowbite.com/blocks/
2. **Tailwind UI Blocks**: https://tailwindcss.com/plus/ui-blocks

## Tech Stack (NO Bootstrap)

| Tecnologia | Uso |
|------------|-----|
| **TailwindCSS** | Styling |
| **Alpine.js** | Interattività |
| **Lit** | Web components |
| **DaisyUI** | Componenti UI |
| **Flowbite** | Pattern |
| **Filament** | Forms/Admin |

## Mappa Blocks Correnti → Nuovi Nomi

| Block Attuale | Nuova Cartella (Flowbite/Tailwind) | Note |
|---------------|-----------------------------------|------|
| `ticket-layout` | `sidebar-layouts/` o `grid-layouts/` | Layout elenco segnalazioni |
| `ticket/heading` | `page-headings/` | Intestazione pagina |
| `ticket/tabs` | `tabs/` | Tabs mappa/elenco |
| `ticket/ticket-card` | `card-headings/` o custom | Card segnalazione |
| `ticket/map-filters` | `sidebar-layouts/` | Sidebar filtri |
| `ticket-list` | `grid-layouts/` o `sidebar-layouts/` | Layout completo |
| `hero` | `hero-sections/` | Header pagina |
| `grid` | `grid-layouts/` | Layout griglia |
| `cta` | `cta-sections/` | Call-to-action |
| `rating` | `feedback-sections/` | Rating stelle |
| `vertical-navigation` | `vertical-navigation/` | Nav contatti |

## Struttura Target

```
resources/views/components/blocks/
├── hero-sections/
│   └── default.blade.php           # "Elenco segnalazioni" + stats
├── grid-layouts/
│   └── two-columns.blade.php     # 33% / 67% layout
├── sidebar-layouts/
│   └── with-filters.blade.php    # Sidebar + content
├── cta-sections/
│   └── with-button.blade.php     # "Fai una segnalazione"
├── feedback-sections/
│   └── star-rating.blade.php     # "Quanto sono chiare..."
├── vertical-navigation/
│   └── with-icons.blade.php      # "Contatta il comune"
├── tabs/
│   └── default.blade.php         # Mappa / Elenco tabs
├── page-headings/
│   └── with-breadcrumbs.blade.php # Breadcrumb + H1
└── card-headings/
    └── ticket.blade.php          # Card segnalazione
```

## Esempio: hero-sections/default.blade.php

```blade
{{--
    Hero Section - Design Comuni Style
    Fonte: https://tailwindcss.com/plus/ui-blocks/marketing/sections/hero-sections
    Tech: TailwindCSS + Alpine.js (NO Bootstrap)
--}}

<section class="bg-white border-b border-gray-200">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ $title ?? 'Elenco segnalazioni' }}
        </h1>
        <p class="text-lg text-gray-600">
            {{ $subtitle ?? 'Negli ultimi 12 mesi sono state risolte 73 segnalazioni.' }}
        </p>
    </div>
</section>
```

## Esempio: cta-sections/with-button.blade.php

```blade
{{--
    CTA Section - Flowbite Style
    Fonte: https://flowbite.com/blocks/marketing/cta/
    Tech: TailwindCSS + DaisyUI
--}}

<section class="bg-primary-600 rounded-lg p-8 text-white">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-2">{{ $title ?? 'Fai una segnalazione' }}</h2>
            <p class="text-primary-100">{{ $text ?? 'Se vuoi aggiungere una segnalazione...' }}</p>
        </div>
        <a href="{{ $button_url ?? '#' }}" class="btn btn-lg bg-white text-primary-600 hover:bg-gray-100">
            {{ $button_text ?? 'Segnala' }}
        </a>
    </div>
</section>
```

## Collegamenti

- [Flowbite Blocks](https://flowbite.com/blocks/)
- [Tailwind UI Blocks](https://tailwindcss.com/plus/ui-blocks)
- [DaisyUI Components](https://daisyui.com/components/)
- [Alpine.js Docs](https://alpinejs.dev/)
