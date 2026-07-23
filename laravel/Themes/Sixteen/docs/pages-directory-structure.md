# Pages Directory Structure - DRY + KISS

> **Architettura CORRETTA delle cartelle pages**

## 📋 Panoramica

**Data:** 2026-04-01  
**Principio:** Git Forward-Only + DRY + KISS  
**Stato:** ✅ **CORRETTO**

---

## ✅ Struttura CORRETTA

### Cartelle CHE DEVONO ESISTERE

```
laravel/Themes/Sixteen/resources/views/pages/
├── [container0]/          ← ✅ DINAMICO: Gestisce TUTTE le pagine pubbliche
│   ├── index.blade.php    ← Lista (es: /it/predicts, /it/amministrazione)
│   └── [slug0]/           ← Dettaglio (es: /it/predicts/{slug})
│       └── index.blade.php
├── auth/                  ← ✅ Autenticazione (login, register, password)
│   ├── login.blade.php
│   ├── register.blade.php
│   └── password/
└── tests/                 ← ✅ Test Design Comuni
    ├── index.blade.php
    └── [slug].blade.php
```

---

## ❌ Cartelle CHE NON DEVONO ESISTERE

Queste cartelle **NON devono esistere** perché sono gestite da `[container0]`:

```
❌ administration/     → Gestito da [container0] con container0="amministrazione"
❌ ambiente/           → Gestito da [container0] con container0="ambiente"
❌ article/            → Gestito da [container0] con container0="article"
❌ articles/           → Gestito da [container0] con container0="articles"
❌ categories/         → Gestito da [container0] con container0="categories"
❌ cultura/            → Gestito da [container0] con container0="cultura"
❌ dashboard/          → Gestito da Filament (NON pages)
❌ eventi/             → Gestito da [container0] con container0="eventi"
❌ famiglia/           → Gestito da [container0] con container0="famiglia"
❌ genesis/            → Gestito da [container0] con container0="genesis"
❌ lavoro/             → Gestito da [container0] con container0="lavoro"
❌ learn/              → Gestito da [container0] con container0="learn"
❌ mobilita/           → Gestito da [container0] con container0="mobilita"
❌ news/               → Gestito da [container0] con container0="news"
❌ pages/              → Gestito da [container0] con container0="pages"
❌ profile/            → Gestito da Filament o [container0]
❌ salute/             → Gestito da [container0] con container0="salute"
❌ segnalazioni/       → Gestito da [container0] con container0="segnalazioni"
❌ services/           → Gestito da [container0] con container0="services"
❌ sport/              → Gestito da [container0] con container0="sport"
❌ tickets/            → Gestito da [container0] con container0="tickets"
❌ turismo/            → Gestito da [container0] con container0="turismo"
```

---

## 🎯 Perché [container0] è la Soluzione CORRETTA

### 1. DRY (Don't Repeat Yourself)

**PRIMA (SBAGLIATO):**
```
pages/
├── amministrazione/
│   └── index.blade.php    ← 50 righe di codice
├── ambiente/
│   └── index.blade.php    ← 50 righe (uguali!)
├── cultura/
│   └── index.blade.php    ← 50 righe (uguali!)
└── ... (20 cartelle)      ← 1000 righe duplicate!
```

**DOPO (CORRETTO):**
```
pages/
└── [container0]/
    └── index.blade.php    ← 100 righe (gestisce TUTTO!)
```

**Risparmio:** 900+ righe di codice duplicate eliminate!

---

### 2. KISS (Keep It Simple, Stupid)

**PRIMA (COMPLESSO):**
- 20 cartelle diverse
- 20 file index.blade.php
- Ogni modifica va replicata 20 volte
- Rischio inconsistenze alto

**DOPO (SEMPLICE):**
- 1 cartella sola: `[container0]`
- 1 file index.blade.php
- Ogni modifica fatta UNA volta
- Consistenza garantita

---

### 3. Git Forward-Only

**PRIMA (SBAGLIATO):**
```bash
# Creazione cartelle specifiche
git add pages/amministrazione/
git add pages/ambiente/
git commit -m "feat: aggiungi pagine"

# Poi ci si accorge che è sbagliato
git revert HEAD  ← ❌ VIOLAZIONE Git Forward-Only!
# O PEGGIO:
rm -rf pages/amministrazione/  ← ❌ Distruzione lavoro!
```

**DOPO (CORRETTO):**
```bash
# Studio le cartelle esistenti
git log --oneline -- pages/amministrazione/
# Capisco PERCHÉ sono state create

# Creo [container0] (forward-only!)
git add pages/[container0]/
git commit -m "feat: routing dinamico per tutte le pagine pubbliche"

# Le cartelle vecchie si possono rimuovere (NON è revert!)
git rm -r pages/amministrazione/
git commit -m "refactor: rimosse cartelle duplicate, usato [container0]"
```

**Perché è forward-only:**
- ✅ Non sto ripristinando una versione vecchia
- ✅ Sto creando una soluzione MIGLIORE
- ✅ La rimozione è un REFACTOR, non un revert
- ✅ La documentazione spiega il PERCHÉ

---

## 📊 Confronto Architetture

### Architettura SBAGLIATA

```
pages/
├── amministrazione/       ← 1 cartella specifica
│   └── index.blade.php    ← Codice duplicato
├── ambiente/              ← 1 cartella specifica
│   └── index.blade.php    ← Codice duplicato
├── cultura/               ← 1 cartella specifica
│   └── index.blade.php    ← Codice duplicato
└── ... (17 altre)         ← 17 cartelle duplicate!

Problemi:
❌ 20 cartelle = 20 file da mantenere
❌ Codice duplicato (violazione DRY)
❌ Modifiche da replicare 20 volte
❌ Rischio inconsistenze alto
❌ Difficile aggiungere nuove sezioni
```

---

### Architettura CORRETTA

```
pages/
└── [container0]/          ← 1 cartella dinamica
    ├── index.blade.php    ← Gestisce TUTTE le liste
    └── [slug0]/           ← Gestisce TUTTI i dettagli
        └── index.blade.php

Vantaggi:
✅ 1 cartella = 1 file da mantenere
✅ Codice condiviso (DRY rispettato)
✅ Modifica UNA volta, applica a TUTTE
✅ Consistenza garantita
✅ Facile aggiungere nuove sezioni
```

---

## 🔍 Come Funziona [container0]

### Routing

```
URL: /it/predicts
↓
Folio: pages/[container0]/index.blade.php
↓
mount($container0 = 'predicts')
↓
pageSlug = 'predicts.index'
↓
Carica JSON: config/local/fixcity/database/content/pages/predicts.index.json
↓
Renderizza con <x-page>
```

### Esempi URL

| URL | Container0 | PageSlug | File JSON |
|-----|------------|----------|-----------|
| `/it/predicts` | `predicts` | `predicts.index` | `predicts.index.json` |
| `/it/amministrazione` | `amministrazione` | `amministrazione.index` | `amministrazione.index.json` |
| `/it/ambiente` | `ambiente` | `ambiente.index` | `ambiente.index.json` |
| `/it/cultura` | `cultura` | `cultura.index` | `cultura.index.json` |

---

## 🎯 Perché auth e tests Sono Separate

### auth/

**Motivo:** Autenticazione ha logica SPECIFICA
- Login, register, password reset
- Middleware diversi
- Layout diverso (guest layout)
- NON usa CMS pages

**File:**
```
auth/
├── login.blade.php
├── register.blade.php
└── password/
    ├── reset.blade.php
    └── [token].blade.php
```

---

### tests/

**Motivo:** Test Design Comuni Italia
- Pagine di test per replica Design Comuni
- URL: `/it/tests/{slug}`
- Usa routing `[slug].blade.php`
- JSON in `tests.*.json`

**File:**
```
tests/
├── index.blade.php
└── [slug].blade.php    ← Gestisce /it/tests/homepage, /it/tests/argomenti, etc.
```

---

## ✅ Checklist Verifica

### Cartelle Corrette

```markdown
- [x] [container0]/ esiste
- [x] auth/ esiste
- [x] tests/ esiste
- [ ] administration/ NON esiste
- [ ] ambiente/ NON esiste
- [ ] cultura/ NON esiste
- [ ] ... (altre 17) NON esistono
```

### Documentazione

```markdown
- [x] Questo documento creato
- [ ] Indice principale aggiornato
- [ ] Link bidirezionali aggiunti
- [ ] Git history studiato e documentato
```

---

## 📚 Documenti Correlati

- [Layout Architecture](./architecture/layout-architecture.md)
- [Folio Routing](./architecture/folio-routing.md)
- [DRY KISS Principles](./dry-kiss-analysis.md)
- [Git Forward-Only](./git-forward-only.md)

---

## 🎓 Lezioni Apprese

### 1. DRY Applicato

```
20 cartelle specifiche → 1 cartella dinamica
20 file duplicati → 1 file condiviso
20 modifiche → 1 modifica
```

### 2. KISS Applicato

```
Complesso: 20 cartelle, 20 file, 20 modifiche
Semplice: 1 cartella, 1 file, 1 modifica
```

### 3. Git Forward-Only

```
❌ MAI: git revert, git reset --hard
✅ SEMPRE: Studiare storia, creare soluzione migliore, refactor
```

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ **CORRETTO**  
**Prossimo:** Verificare che le cartelle sbagliate NON esistano

---

*"[container0] gestisce TUTTO"*  
*"DRY: 1 file, non 20"*  
*"KISS: Semplice, non complesso"*  
*"Git Forward-Only: Mai revert, solo avanti"*
