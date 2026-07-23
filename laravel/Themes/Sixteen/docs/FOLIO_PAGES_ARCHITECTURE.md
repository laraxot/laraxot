# Folio Pages Architecture - FixCity Fila5

**Project:** FixCity Fila5
**Date:** 2026-04-01
**Status:** ✅ **Documented**
**Priority:** 🔴 **Critical Architecture**

---

## 🎯 Scopo

Questo documento definisce l'architettura delle pagine Folio nel tema Sixteen, applicando i principi **DRY** e **KISS**.

---

## 📐 Folder Structure

### ✅ ALLOWED (3 cartelle)

```
laravel/Themes/Sixteen/resources/views/pages/
├── [container0]/          ✅ CMS pages dinamiche
│   ├── index.blade.php    ✅ Listing page
│   └── [slug].blade.php   ✅ Dynamic page handler
│
├── auth/                  ✅ Autenticazione
│   ├── login.blade.php
│   ├── register.blade.php
│   └── ...
│
└── tests/                 ✅ Design Comuni replication
    ├── index.blade.php
    └── [slug].blade.php
```

---

### ❌ PROHIBITED (Page-specific folders)

Queste cartelle **NON DEVONO ESISTERE** perché violano DRY:

```
❌ administration/    → Usa [container0]/ con JSON: "administration"
❌ ambiente/          → Usa [container0]/ con JSON: "ambiente"
❌ article/           → Usa [container0]/ con JSON: "article"
❌ articles/          → Usa [container0]/ con JSON: "articles"
❌ categories/        → Usa [container0]/ con JSON: "categories"
❌ cultura/           → Usa [container0]/ con JSON: "cultura"
❌ dashboard/         → Usa auth/ o rimuovi
❌ eventi/            → Usa [container0]/ con JSON: "eventi"
❌ famiglia/          → Usa [container0]/ con JSON: "famiglia"
❌ genesis/           → Rimuovi
❌ lavoro/            → Usa [container0]/ con JSON: "lavoro"
❌ learn/             → Usa [container0]/ con JSON: "learn"
❌ mobilita/          → Usa [container0]/ con JSON: "mobilita"
❌ news/              → Usa [container0]/ con JSON: "news"
❌ pages/             → Rimuovi (troppo generico)
❌ profile/           → Usa auth/
❌ salute/            → Usa [container0]/ con JSON: "salute"
❌ segnalazioni/      → Usa [container0]/ con JSON: "segnalazioni"
❌ services/          → Usa [container0]/ con JSON: "services"
❌ sport/             → Usa [container0]/ con JSON: "sport"
❌ tickets/           → Usa [container0]/ con JSON: "tickets"
❔ turismo/           → Usa [container0]/ con JSON: "turismo"
```

---

## 🔧 Why This Structure?

### 1. DRY (Don't Repeat Yourself)

❌ **SBAGLIATO:**
```
pages/administration/administration.blade.php
pages/ambiente/ambiente.blade.php
pages/cultura/cultura.blade.php
... (22 cartelle con stessa logica)
```

✅ **CORRETTO:**
```
pages/[container0]/[slug].blade.php  ← UNICA logica dinamica

JSON files:
- config/local/.../pages/administration.json
- config/local/.../pages/ambiente.json
- config/local/.../pages/cultura.json
```

**Vantaggi:**
- 1 file invece di 22
- Logica centralizzata
- Facile manutenzione
- Scalabile

---

### 2. KISS (Keep It Simple, Stupid)

❌ **COMPLESSO:**
```php
// 22 file diversi con stessa logica
pages/administration/administration.blade.php
pages/ambiente/ambiente.blade.php
...
```

✅ **SEMPLICE:**
```php
// 1 file dinamico
pages/[container0]/[slug].blade.php

mount($container, $slug) {
    $pageSlug = $container . '.' . $slug;
    $blocks = Page::getBlocksBySlug($pageSlug);
}
```

---

## 📋 URL Mapping

### Pattern [container0]

```
URL: /it/{container}
  ↓
Folio: pages/[container0]/index.blade.php
  ↓
pageSlug = "{container}"
  ↓
JSON: config/local/.../pages/{container}.json

Esempio:
/it/administration → pageSlug = "administration"
                   → JSON: administration.json
```

```
URL: /it/{container}/{slug}
  ↓
Folio: pages/[container0]/[slug].blade.php
  ↓
pageSlug = "{container}.{slug}"
  ↓
JSON: config/local/.../pages/{container}.{slug}.json

Esempio:
/it/administration/organi → pageSlug = "administration.organi"
                          → JSON: administration.organi.json
```

---

## 🚀 Migration Guide

### Da Page-Specific a [container0]

**PRIMA:**
```
pages/administration/administration.blade.php
```

**DOPO:**
```
1. Elimina: pages/administration/administration.blade.php
2. Crea JSON: config/local/.../pages/administration.json
3. Usa: pages/[container0]/index.blade.php (già esistente)
```

**JSON Example:**
```json
{
  "slug": "administration",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.default",
          "title": "Amministrazione"
        }
      }
    ]
  }
}
```

---

## 📁 Special Cases

### auth/ Folder

**Permessò** perché:
- Dominio specifico (autenticazione)
- Logica diversa (non CMS-driven)
- Middleware specifici

**Files:**
- `login.blade.php`
- `register.blade.php`
- `forgot-password.blade.php`
- `verify-email.blade.php`

---

### tests/ Folder

**Permessò** perché:
- Dominio specifico (Design Comuni replication)
- Testing purposes
- Fixed routes

**Files:**
- `index.blade.php` (listing)
- `[slug].blade.php` (dynamic handler)

---

## ✅ Checklist: New Page

Per creare una nuova pagina CMS:

1. [ ] **NON** creare cartella `pages/{name}/`
2. [ ] Crea JSON: `config/local/.../pages/{name}.json`
3. [ ] Definisci blocchi nel JSON
4. [ ] URL sarà: `/it/{name}`
5. [ ] Gestito da: `pages/[container0]/index.blade.php`

Per creare una pagina con slug:

1. [ ] **NON** creare cartella `pages/{name}/`
2. [ ] Crea JSON: `config/local/.../pages/{name}.{slug}.json`
3. [ ] Definisci blocchi nel JSON
4. [ ] URL sarà: `/it/{name}/{slug}`
5. [ ] Gestito da: `pages/[container0]/[slug].blade.php`

---

## 🔗 Cross-References

### Internal Documents

- → [Theme Detection System](../../../docs/project/THEME_DETECTION_SYSTEM.md) - Theme detection
- → [Page Component Architecture](../../../Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md) - Page component
- → [JSON Content Guide](../../../Modules/Cms/docs/JSON_CONTENT_GUIDE.md) - JSON structure
- → [Master Index](../../../docs/project/MODULE_DOCS_INDEX.md) - Central navigation

### External Resources

- → [Laravel Folio Documentation](https://laravel.com/docs/folio)
- → [Livewire Volt Documentation](https://livewire.laravel.com/docs/volt)

---

## 🚨 Anti-Patterns

### 1. ❌ Create Page-Specific Folder

```bash
mkdir pages/administration  # SBAGLIATO!
```

✅ **CORRETTO:**
```bash
# Usa [container0] esistente
# Crea JSON: administration.json
```

---

### 2. ❌ Duplicate Logic

```blade
{{-- pages/administration/administration.blade.php --}}
{{-- pages/ambiente/ambiente.blade.php --}}
{{-- Stessa logica duplicata 22 volte! --}}
```

✅ **CORRETTO:**
```blade
{{-- pages/[container0]/[slug].blade.php --}}
{{-- Logica singola, dinamica --}}
```

---

### 3. ❌ Hardcoded HTML

```blade
{{-- Hardcoded in blade file --}}
<div class="hero">
    <h1>Amministrazione</h1>
</div>
```

✅ **CORRETTO:**
```json
{
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {"title": "Amministrazione"}
      }
    ]
  }
}
```

---

**📝 Documento preparato da:** Multi-Agent Team (BMad + GSD)
**📅 Data:** 2026-04-01
**🔄 Status:** ✅ **Documented**

🐮 **Folio Architecture Documented!**
