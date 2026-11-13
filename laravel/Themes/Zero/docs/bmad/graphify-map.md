---
title: "Zero Theme — Mappa Graphify"
type: guide
tags: [theme, zero, graphify]
created: 2026-08-03
updated: 2026-08-03
qmd: "zero theme zero theme   mappa graphify"
id: zero-docs-bmad-graphify-map
slug: graphify-map
description: "Mappa Graphify del tema Zero: cosa fornisce, architettura essenziale e punti di ingresso."
document_type: guide
category: documentation
status: active
issues: []
discussions: []
---
# Zero Theme — Mappa Graphify

**Versione:** 1.0.0 | **Tema:** Zero | **Data:** 2026-08-02

---

## 📌 Cosa fa il tema Zero

Il tema **Zero** fornisce:
- Tema UI base/minimalista di sistema con Vite, Tailwind CSS, layouts guest ed app

---

## 🏗️ Architettura Essenziale Tema

### Entry Points Visivi

| Tipo | File | Path |
|------|------|------|
| **View Layout** | `layouts/app.blade.php` | `resources/views/layouts/app.blade.php` |
| **View Layout** | `layouts/guest.blade.php` | `resources/views/layouts/guest.blade.php` |
| **View Layout** | `pages/home.blade.php` | `resources/views/pages/home.blade.php` |
| **Component** | `ui/logo.blade.php` | `resources/views/components/ui/logo.blade.php` |
| **Component** | `blocks/hero/main.blade.php` | `resources/views/components/blocks/hero/main.blade.php` |
| **Component** | `blocks/stats/overview.blade.php` | `resources/views/components/blocks/stats/overview.blade.php` |

### Dependencies (Incoming)

```
Tutti i moduli → Theme Zero (tema base fallback)
```

### Dependencies (Outgoing)

```
Theme Zero → Vite
Theme Zero → Tailwind CSS
Theme Zero → PostCSS
```

---

## 📊 Grafo Locale (Query Rapide Tema)

### Scoprire Componenti Tema

```bash
graphify query "Zero theme components and layouts"
```

### Tracciare Dipendenze CSS/Vite

```bash
graphify query "Zero theme CSS assets and dependencies"
```

---

## 🎯 Task Comuni Tema + Graphify

### Task 1: Personalizzazione Layout e Componenti

**Domanda Graphify:**
```bash
graphify query "Zero component architecture and Blade structure"
```

**Workflow:**
1. Ispeziona views in `resources/views/components/`
2. Modifica o crea nuovo componente Blade
3. Verifica la resa visiva

---

## 🚀 Comandi Rapidi Tema

```bash
# Esplora struttura tema
graphify query "Zero theme components"

# Dipendenze
graphify query "modules using Zero theme"
```

---

## 📚 Riferimenti

- **Graphify Central:** `docs/graphify-integration.md`
- **Theme Template:** `laravel/themes/GRAPHIFY_THEME_TEMPLATE.md`

---

**Responsabile:** @marco76tv | **Last updated:** 2026-08-02
