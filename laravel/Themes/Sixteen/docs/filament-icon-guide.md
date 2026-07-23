# Filament Icon Usage Guide (Filament 5)

> *"Usa `<x-filament::icon>` per tutte le icone nei componenti Blade."*

## 🎯 Icon Convention

**NEVER use**: `<x-icon>` or raw `<svg>`  
**ALWAYS use**: `<x-filament::icon>`

**Filament Version**: 5.x  
**Documentation**: https://filamentphp.com/docs/5.x/forms/icon-picker

---

## 📖 Syntax

```blade
<x-filament::icon 
    icon="heroicon-o-check-circle" 
    class="w-8 h-8 text-green-600" 
    aria-hidden="true" 
/>
```

---

## 🎨 Icon Sets

### Heroicons (Default in Filament)

#### Outline Icons (`heroicon-o-*`)

```blade
{{-- Success/Check --}}
<x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-green-600" />
<x-filament::icon icon="heroicon-o-check" class="w-6 h-6 text-green-600" />
<x-filament::icon icon="heroicon-o-check-badge" class="w-6 h-6 text-green-600" />

{{-- Information --}}
<x-filament::icon icon="heroicon-o-information-circle" class="w-8 h-8 text-blue-600" />
<x-filament::icon icon="heroicon-o-information" class="w-6 h-6 text-blue-600" />

{{-- Warning --}}
<x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-8 h-8 text-yellow-600" />
<x-filament::icon icon="heroicon-o-exclamation" class="w-6 h-6 text-yellow-600" />
<x-filament::icon icon="heroicon-o-exclamation-circle" class="w-8 h-8 text-yellow-600" />

{{-- Error/X --}}
<x-filament::icon icon="heroicon-o-x-circle" class="w-8 h-8 text-red-600" />
<x-filament::icon icon="heroicon-o-x-mark" class="w-6 h-6 text-red-600" />
<x-filament::icon icon="heroicon-o-x" class="w-6 h-6 text-red-600" />

{{-- Navigation --}}
<x-filament::icon icon="heroicon-o-arrow-right" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-arrow-left" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-arrow-up" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-arrow-down" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-chevron-right" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-chevron-left" class="w-5 h-5" />

{{-- Actions --}}
<x-filament::icon icon="heroicon-o-plus" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-minus" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-pencil" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-trash" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-eye" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-download" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-upload" class="w-5 h-5" />

{{-- UI --}}
<x-filament::icon icon="heroicon-o-search" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-filter" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-sort-ascending" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-dots-vertical" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-menu" class="w-6 h-6" />
<x-filament::icon icon="heroicon-o-x" class="w-6 h-6" />

{{-- Communication --}}
<x-filament::icon icon="heroicon-o-envelope" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-phone" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-bell" class="w-5 h-5" />

{{-- File/Folder --}}
<x-filament::icon icon="heroicon-o-document" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-folder" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-archive" class="w-5 h-5" />

{{-- User --}}
<x-filament::icon icon="heroicon-o-user" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-users" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-user-circle" class="w-8 h-8" />

{{-- Status --}}
<x-filament::icon icon="heroicon-o-clock" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-calendar" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-flag" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-bookmark" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-star" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-heart" class="w-5 h-5" />

{{-- Security --}}
<x-filament::icon icon="heroicon-o-lock-closed" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-lock-open" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-key" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-shield-check" class="w-5 h-5" />
```

#### Solid Icons (`heroicon-s-*`)

```blade
<x-filament::icon icon="heroicon-s-check-circle" class="w-8 h-8 text-green-600" />
<x-filament::icon icon="heroicon-s-information-circle" class="w-8 h-8 text-blue-600" />
<x-filament::icon icon="heroicon-s-exclamation-triangle" class="w-8 h-8 text-yellow-600" />
<x-filament::icon icon="heroicon-s-x-circle" class="w-8 h-8 text-red-600" />
<x-filament::icon icon="heroicon-s-star" class="w-5 h-5 text-yellow-500" />
<x-filament::icon icon="heroicon-s-heart" class="w-5 h-5 text-red-500" />
```

---

## 📋 Common Use Cases

### 1. Alert/Notification Icons

```blade
{{-- Success Alert --}}
<div class="bg-green-50 border-l-4 border-green-500 p-4">
    <div class="flex">
        <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-600" />
        <div class="ml-3">
            <p class="text-sm text-green-800">Operazione completata</p>
        </div>
    </div>
</div>

{{-- Info Alert --}}
<div class="bg-blue-50 border-l-4 border-blue-500 p-4">
    <div class="flex">
        <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-blue-600" />
        <div class="ml-3">
            <p class="text-sm text-blue-800">Informazione importante</p>
        </div>
    </div>
</div>

{{-- Warning Alert --}}
<div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
    <div class="flex">
        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 text-yellow-600" />
        <div class="ml-3">
            <p class="text-sm text-yellow-800">Attenzione</p>
        </div>
    </div>
</div>

{{-- Error Alert --}}
<div class="bg-red-50 border-l-4 border-red-500 p-4">
    <div class="flex">
        <x-filament::icon icon="heroicon-o-x-circle" class="w-5 h-5 text-red-600" />
        <div class="ml-3">
            <p class="text-sm text-red-800">Errore</p>
        </div>
    </div>
</div>
```

### 2. Button Icons

```blade
{{-- Primary Button with Icon --}}
<button class="btn btn-primary flex items-center gap-2">
    <x-filament::icon icon="heroicon-o-plus" class="w-5 h-5" />
    Aggiungi
</button>

{{-- Secondary Button with Icon --}}
<button class="btn btn-outline flex items-center gap-2">
    <x-filament::icon icon="heroicon-o-pencil" class="w-5 h-5" />
    Modifica
</button>

{{-- Danger Button with Icon --}}
<button class="btn btn-danger flex items-center gap-2">
    <x-filament::icon icon="heroicon-o-trash" class="w-5 h-5" />
    Elimina
</button>

{{-- Icon Button --}}
<button class="p-2 rounded-lg hover:bg-gray-100">
    <x-filament::icon icon="heroicon-o-search" class="w-5 h-5" />
</button>
```

### 3. Navigation Icons

```blade
{{-- Breadcrumb Chevron --}}
<nav aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        <li><a href="/">Home</a></li>
        <li>
            <x-filament::icon icon="heroicon-o-chevron-right" class="w-4 h-4 text-gray-400" />
        </li>
        <li><a href="/pagina">Pagina</a></li>
    </ol>
</nav>

{{-- Menu Items --}}
<ul class="space-y-2">
    <li>
        <a href="/dashboard" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100">
            <x-filament::icon icon="heroicon-o-home" class="w-5 h-5 text-gray-600" />
            <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="/settings" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100">
            <x-filament::icon icon="heroicon-o-cog-6-tooth" class="w-5 h-5 text-gray-600" />
            <span>Impostazioni</span>
        </a>
    </li>
</ul>
```

### 4. Form Icons

```blade
{{-- Input with Icon --}}
<div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <x-filament::icon icon="heroicon-o-search" class="w-5 h-5 text-gray-400" />
    </div>
    <input type="text" class="pl-10 form-input" placeholder="Cerca..." />
</div>

{{-- Select with Icon --}}
<div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <x-filament::icon icon="heroicon-o-user" class="w-5 h-5 text-gray-400" />
    </div>
    <select class="pl-10 form-select">
        <option>Seleziona utente...</option>
    </select>
</div>
```

### 5. Status Icons

```blade
{{-- Status Badge --}}
<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-sm bg-green-100 text-green-800">
    <x-filament::icon icon="heroicon-s-check-circle" class="w-4 h-4" />
    Completato
</span>

{{-- Pending Badge --}}
<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
    <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4" />
    In attesa
</span>

{{-- Error Badge --}}
<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-sm bg-red-100 text-red-800">
    <x-filament::icon icon="heroicon-s-x-circle" class="w-4 h-4" />
    Errore
</span>
```

---

## 🎨 Size & Color Classes

### Sizes

```blade
{{-- Extra Small --}}
<x-filament::icon icon="heroicon-o-check" class="w-3 h-3" />

{{-- Small --}}
<x-filament::icon icon="heroicon-o-check" class="w-4 h-4" />
<x-filament::icon icon="heroicon-o-check" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-check" class="w-6 h-6" />

{{-- Medium --}}
<x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8" />

{{-- Large --}}
<x-filament::icon icon="heroicon-o-check-circle" class="w-10 h-10" />
<x-filament::icon icon="heroicon-o-check-circle" class="w-12 h-12" />

{{-- Extra Large --}}
<x-filament::icon icon="heroicon-o-check-circle" class="w-16 h-16" />
```

### Colors

```blade
{{-- Primary (Green) --}}
<x-filament::icon icon="heroicon-o-check-circle" class="w-6 h-6 text-green-600" />

{{-- Info (Blue) --}}
<x-filament::icon icon="heroicon-o-information-circle" class="w-6 h-6 text-blue-600" />

{{-- Warning (Yellow) --}}
<x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-6 h-6 text-yellow-600" />

{{-- Error (Red) --}}
<x-filament::icon icon="heroicon-o-x-circle" class="w-6 h-6 text-red-600" />

{{-- Gray --}}
<x-filament::icon icon="heroicon-o-check" class="w-6 h-6 text-gray-600" />

{{-- White --}}
<x-filament::icon icon="heroicon-o-check" class="w-6 h-6 text-white" />
```

---

## ✅ Best Practices

### DO ✅

```blade
{{-- Use semantic icon names --}}
<x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-green-600" />

{{-- Add aria-hidden for decorative icons --}}
<x-filament::icon icon="heroicon-o-check" class="w-5 h-5" aria-hidden="true" />

{{-- Use consistent sizing --}}
<x-filament::icon icon="heroicon-o-check" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-check" class="w-5 h-5" />

{{-- Use outline icons for UI, solid for status --}}
<x-filament::icon icon="heroicon-o-check-circle" />  {{-- UI element --}}
<x-filament::icon icon="heroicon-s-check-circle" />  {{-- Status indicator --}}
```

### DON'T ❌

```blade
{{-- Don't use raw SVG --}}
<svg class="w-5 h-5" fill="none" stroke="currentColor">...</svg>

{{-- Don't use <x-icon> (deprecated) --}}
<x-icon name="check" />

{{-- Don't use inconsistent sizes --}}
<x-filament::icon icon="heroicon-o-check" class="w-5 h-5" />
<x-filament::icon icon="heroicon-o-check" class="w-6 h-6" />  {{-- Inconsistent! --}}

{{-- Don't forget accessibility --}}
<x-filament::icon icon="heroicon-o-check" />  {{-- Missing aria-hidden --}}
```

---

## 🔗 References

### External
- [Filament Icons Documentation](https://filamentphp.com/docs/5.x/forms/fields/icon-picker)
- [Heroicons](https://heroicons.com/)
- [Filament Components](https://filamentphp.com/docs/5.x/support/components)

### Internal
- [Blocks Structure Convention](./BLOCKS_STRUCTURE_CONVENTION.md)
- [Confirmation Blocks](./confirmation/README.md)

---

**Version**: 1.0  
**Date**: 2026-03-30  
**Status**: ✅ Ready to Use  
**OpenViking URI**: `viking://themes/sixteen/docs/filament-icon-guide`
