# Filosofia Documentazione - Tema Two

## Principio Fondamentale: Nomi Semantici, Non Temporali

### Perché Nomi Semantici?

I nomi dei file di documentazione devono descrivere **COSA contengono**, non **QUANDO sono stati creati**.

#### Vantaggi dei Nomi Semantici

1. **Ricerca Facilitata**
   - Cerco "analysis" → trovo `complete-analysis-summary.md`
   - Non devo ricordare date o cercare tra file datati

2. **Evita Duplicati**
   - Un solo file `complete-analysis-summary.md` che viene aggiornato
   - Non creo `complete-analysis-summary-2025-01-06.md` e poi `complete-analysis-summary-2026-02-19.md`

3. **Manutenibilità**
   - Il nome descrive il contenuto, non la cronologia
   - Facile capire cosa contiene senza aprire il file

4. **Coerenza**
   - Tutti i file seguono lo stesso pattern semantico
   - Struttura prevedibile e professionale

#### Tracking Temporale

Le date vanno nel **contenuto** del file o in **CHANGELOG.md**, non nel nome:

```markdown
# complete-analysis-summary.md

## Ultimo Aggiornamento
**2026-02-19**: Aggiornato con risultati PHPStan livello 10

## Storia Modifiche
- 2025-01-06: Analisi iniziale completata
- 2026-02-19: Aggiornamento con nuovi moduli
```

## Regole di Naming

### ✅ CORRETTO
- `complete-analysis-summary.md` - Descrizione semantica
- `code-quality-analysis.md` - Cosa contiene
- `refactoring-plan.md` - Scopo del documento
- `business-logic-guide.md` - Contenuto descritto

### ❌ VIETATO
- `complete-analysis-summary-2025-01-06.md` - Data nel nome
- `analysis-2025.md` - Anno nel nome
- `fix-jan-2026.md` - Mese/anno nel nome
- `report-2025-12-19.md` - Data completa nel nome

## Struttura Documentazione

### Organizzazione per Contenuto

```
docs/
├── README.md                    # Entry point
├── architecture.md              # Architettura tecnica
├── business-logic.md            # Regole business
├── code-quality.md              # Standard qualità codice
├── troubleshooting.md           # Problemi comuni
└── CHANGELOG.md                 # Storia modifiche con date
```

### Tracking Temporale

Usa **CHANGELOG.md** per la cronologia:

```markdown
# CHANGELOG.md

## [2026-02-19]
### Updated
- `complete-analysis-summary.md` - Aggiornato con risultati PHPStan livello 10

## [2025-01-06]
### Added
- `complete-analysis-summary.md` - Analisi iniziale qualità codice
```

## Best Practices

1. **Prima di creare un file**: Cerca se esiste già un file sullo stesso argomento
2. **Se esiste**: Aggiorna il file esistente invece di crearne uno nuovo
3. **Se non esiste**: Crea con nome semantico, senza date
4. **Dopo creazione**: Aggiorna CHANGELOG.md con la data

## Filosofia DRY + KISS

- **DRY**: Un argomento = un file (non multipli con date diverse)
- **KISS**: Nomi semplici che descrivono il contenuto
- **Business Logic Focus**: Documenta il PERCHÉ, non solo il COSA

## Riferimenti

- [Documentation Naming Rules](../../../../.cursor/rules/documentation-naming.mdc)
- [Docs Naming Conventions](../../../../.cursor/rules/docs-naming-conventions.mdc)
- [Documentation Consolidation Strategy](../../../Modules/Xot/docs/documentation-consolidation-strategy.md)
