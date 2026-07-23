# Analisi Dettagliata Pagine FAIL (<50%)

## Panoramica

- **Data**: 2026-04-03
- **Pagine analizzate**: 3
- **Screenshot**: `screenshots/fail-pages-detail/`

## evento-dettaglio (36.7%)

### Metriche

| Metrica | Valore |
|---------|--------|
| Reference righe | 1448 |
| Local righe | 532 |
| Differenza | -916 righe |
| Match % | 36.7% |

### Componenti Reference

| Componente | Reference | Local | Stato |
|-----------|-----------|-------|-------|
| breadcrumb | ✅ | ✅ | ✅ |
| hero | ❌ | ✅ | ⚠️ |
| heading | ❌ | ❌ | ⚪ |
| card | ✅ | ❌ | ❌ |
| list | ✅ | ✅ | ✅ |
| table | ❌ | ❌ | ⚪ |
| form | ✅ | ✅ | ✅ |
| accordion | ✅ | ❌ | ❌ |
| tabs | ❌ | ❌ | ⚪ |
| modal | ✅ | ✅ | ✅ |
| rating | ✅ | ❌ | ❌ |
| contacts | ✅ | ❌ | ❌ |

### Componenti Mancanti

- ❌ `card`
- ❌ `accordion`
- ❌ `rating`
- ❌ `contacts`

### Componenti Extra (non nel reference)

- ⚠️ `hero`

### Piano di Fix

- Implementare componente: `card`
- Implementare componente: `accordion`
- Implementare componente: `rating`
- Implementare componente: `contacts`

---

## segnalazione-area-personale (37.1%)

### Metriche

| Metrica | Valore |
|---------|--------|
| Reference righe | 1506 |
| Local righe | 559 |
| Differenza | -947 righe |
| Match % | 37.1% |

### Componenti Reference

| Componente | Reference | Local | Stato |
|-----------|-----------|-------|-------|
| breadcrumb | ✅ | ✅ | ✅ |
| hero | ❌ | ✅ | ⚠️ |
| heading | ✅ | ❌ | ❌ |
| card | ✅ | ✅ | ✅ |
| list | ✅ | ✅ | ✅ |
| table | ❌ | ❌ | ⚪ |
| form | ✅ | ✅ | ✅ |
| accordion | ✅ | ❌ | ❌ |
| tabs | ✅ | ❌ | ❌ |
| modal | ✅ | ✅ | ✅ |
| rating | ❌ | ❌ | ⚪ |
| contacts | ✅ | ❌ | ❌ |

### Componenti Mancanti

- ❌ `heading`
- ❌ `accordion`
- ❌ `tabs`
- ❌ `contacts`

### Componenti Extra (non nel reference)

- ⚠️ `hero`

### Piano di Fix

- Implementare componente: `heading`
- Implementare componente: `accordion`
- Implementare componente: `tabs`
- Implementare componente: `contacts`

---

## persona (35.0%)

### Metriche

| Metrica | Valore |
|---------|--------|
| Reference righe | 40 |
| Local righe | 14 |
| Differenza | -26 righe |
| Match % | 35.0% |

### Componenti Reference

| Componente | Reference | Local | Stato |
|-----------|-----------|-------|-------|
| breadcrumb | ❌ | ❌ | ⚪ |
| hero | ❌ | ❌ | ⚪ |
| heading | ❌ | ❌ | ⚪ |
| card | ❌ | ❌ | ⚪ |
| list | ❌ | ❌ | ⚪ |
| table | ❌ | ❌ | ⚪ |
| form | ❌ | ❌ | ⚪ |
| accordion | ❌ | ❌ | ⚪ |
| tabs | ❌ | ❌ | ⚪ |
| modal | ❌ | ❌ | ⚪ |
| rating | ❌ | ❌ | ⚪ |
| contacts | ❌ | ❌ | ⚪ |

### Piano di Fix

- ✅ Tutti i componenti principali sono presenti
- CSS refinements necessari per allineamento visivo

---

## Screenshots

| Pagina | Reference | Local |
|--------|-----------|-------|
| evento-dettaglio | [ref](screenshots/fail-pages-detail/evento-dettaglio-reference.png) | [local](screenshots/fail-pages-detail/evento-dettaglio-local.png) |
| segnalazione-area-personale | [ref](screenshots/fail-pages-detail/segnalazione-area-personale-reference.png) | [local](screenshots/fail-pages-detail/segnalazione-area-personale-local.png) |
| persona | [ref](screenshots/fail-pages-detail/persona-reference.png) | [local](screenshots/fail-pages-detail/persona-local.png) |
