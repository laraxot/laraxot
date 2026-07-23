# Analisi Comparativa Pagine Segnalazione - Task Attivo

**Data**: 2026-04-06
**Target**: Pagine segnalazione-* da allineare a design-comuni-statiche
**Metodo**: Analisi strutturale HTML + comparazione visual report esistente

## Riepilogo Parity Score (da report precedente)

| Pagina | Parity | Status |
|--------|--------|--------|
| segnalazione-dettaglio | 93% | ✅ Buono |
| segnalazione-01-privacy | 97% | ✅ Eccellente |
| segnalazione-02-dati | 52% | 🔴 Da correggere |
| segnalazione-03-riepilogo | 51% | 🔴 Da correggere |
| segnalazione-04-conferma | 80% | ⚠️ Discreto |
| segnalazione-area-personale | 59% | 🔴 Da correggere |
| ticket-list | 85% | ⚠️ Buono |
| segnalazione-disservizio | 4% | 🔴 Critico |

## Struttura HTML Riferimento (Bootstrap Italia)

Dall'analisi delle pagine reference, la struttura comune per le pagine segnalazione è:

```html
<!-- Step pages (01-04) -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10">
      <!-- Breadcrumb -->
      <!-- Stepper indicator -->
      <!-- Form content -->
      <!-- Navigation buttons (Indietro/Avanti) -->
    </div>
  </div>
</div>
```

## Componenti Chiave Identificati

1. **Breadcrumb**: Presente in tutte le pagine
2. **Stepper**: Indicatore progressione (solo step pages)
3. **Form Fields**: Input per dati segnalazione
4. **Action Buttons**: Indietro/Avanti/Conferma

## Prossimi Passi

1. Analizzare CSS specifici per le pagine con basso parity
2. Identificare gap nei componenti Blade
3. Procedere con interventi CSS/JS mirati

## Riferimenti

- Documentazione esistente: `docs/COMPLETE-VISUAL-PARITY-REPORT.md`
- Componenti: `resources/views/components/blocks/tests/`
- JSON content: `config/local/fixcity/database/content/pages/tests.*`