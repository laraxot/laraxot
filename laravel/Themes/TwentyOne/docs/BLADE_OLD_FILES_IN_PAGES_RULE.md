# 🔴 REGOLA: File .blade.php.old Nella Cartella Pages

**Data**: 2026-03-22  
**Stato**: ✅ OBBLIGATORIO  
**Priorità**: CRITICAL  

---

## 📜 La Regola

```
❌ VIETATO
Themes/TwentyOne/resources/views/pages/predicts/[slug].blade.php
Themes/TwentyOne/resources/views/pages/predicts/[slug].blade.php.old

✅ CORRETTO
Themes/TwentyOne/resources/views/pages/[container0]/[slug].blade.php
```

**I file `.blade.php.old` nella cartella `pages` devono essere ELIMINATI, non rinominati.**

---

## 🧘 La Filosofia: Folio Non Conosce il Passato

> **"Il passato non esiste. Folio vive nel presente."**

### Perché i File .blade.php.old Sono Nebbia

1. **Folio legge solo `.blade.php`**
   - Folio scansiona automaticamente `resources/views/pages/*.blade.php`
   - Estensioni diverse (`*.blade.php.old`, `*.blade.backup`) vengono IGNORATE
   - Non creano rotte, non vengono renderizzati, non servono a nulla

2. **Sono zombie tecnici**
   - Occupano spazio senza funzione
   - Creano confusione negli sviluppatori
   - Suggeriscono che qualcosa "potrebbe" tornare utile

3. **Violano il principio Zen**
   - Il theme è il vestito: indossa e basta
   - Non tenere vestiti vecchi nell'armadio
   - Ogni file deve avere uno scopo preciso

---

## 🏗️ L'Architettura Folio

### Come Funziona Folio

```
resources/views/pages/
├── [container0]/
│   ├── index.blade.php      ← Folio → /{container0}
│   └── [slug].blade.php    ← Folio → /{container0}/{slug}
├── predicts/
│   ├── index.blade.php     ← Folio → /predicts ⚠️ VIOLAZIONE
│   └── [slug].blade.php    ← Folio → /predicts/{slug} ⚠️ VIOLAZIONE
└── index.blade.php         ← Folio → /
```

### Il Pattern Corretto

```
resources/views/pages/[container0]/
├── index.blade.php         ← GENERIC per ogni container
└── [slug].blade.php        ← GENERIC per ogni slug
```

Esempi validi:
- `/it/predicts` → `pages/[container0]/index.blade.php` con `$container0 = 'predicts'`
- `/it/predicts/spacex-lancera-...` → `pages/[container0]/[slug].blade.php`

---

## ⚡ La Politica: Zero Tolleranza

### Prima di Ogni Commit

- [ ] Nessun file `.blade.php.old` in `pages/`
- [ ] Nessun file `.blade.php` specifico per modulo
- [ ] Usa solo pattern `[container0]/` generico

### Come Eliminare

```bash
# ❌ SBAGLIATO - Lascia zombie
mv [slug].blade.php [slug].blade.php.old

# ✅ CORRETTO - Elimina
rm [slug].blade.php
```

---

## 📖 La Logica Tecnica

### Folio Route Matching

```php
// Folio cerca:
// 1. pages/predicts/[slug].blade.php
// 2. pages/[container0]/[slug].blade.php (con @ render)
// 3. pages/[container0]/index.blade.php

// Non cerca MAI:
// - pages/predicts/[slug].blade.php.old
// - pages/predicts/[slug].bak
// - pages/predicts/[slug].backup
```

### Perché l'Estensione Conta

```php
// Folio/src/Routing/PageFinder.php
// Cerca solo file con estensione .blade.php

$files = $this->directory->getFiles('*.blade.php');
// I file .blade.php.old NON sono inclusi
```

---

## 🔗 Riferimenti

- `docs/project/ARCHITECTURE_ZEN.md` - Architettura base
- `NO_PREDICT_SPECIFIC_PAGES.md` - Regola pagine specifiche
- `THEME_PHILOSOPHY_ZEN.md` - Filosofia theme

---

**Ultimo Aggiornamento**: 2026-03-22  
**Stato**: ✅ OBBLIGATORIO
