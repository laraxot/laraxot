# Docs Refactoring Plan - Employee Module

## Analisi Situazione Attuale

### Problemi Identificati

1. **Naming Inconsistencies**
   - File con maiuscole: `README.md` (corretto), ma altri con convenzioni miste
   - Duplicati con naming diverso: `business-logic.md` vs `business_logic.md`
   - Underscore vs dash inconsistenti

2. **Struttura Disorganizzata**
   - File business logic sparsi in root e sottocartella
   - Contenuti duplicati in file diversi
   - Mancanza di gerarchia logica chiara

3. **Contenuti Duplicati**
   - `business-logic.md` (root) vs `business_logic/business_logic_overview.md`
   - Multiple versioni di PHPStan fixes
   - Documentazione architetturale frammentata

## Piano di Rifattorizzazione

### Fase 1: Convenzioni Naming (PRIORITÀ ASSOLUTA)

**Regola Laraxot**: Tutti i file e cartelle in `docs/` devono essere in minuscolo, eccetto `README.md`

```
✅ CORRETTO:
- README.md
- business_logic.md  
- phpstan_fixes.md
- time_tracking.md

❌ ERRATO:
- business-logic.md (dash invece underscore)
- PHPStan-fixes.md (maiuscole)
- timeTracking.md (camelCase)
```

### Fase 2: Struttura Target

```
docs/
├── README.md                           # Overview principale
├── architecture/
│   ├── README.md
│   ├── technical_architecture.md
│   ├── data_architecture.md
│   └── module_structure.md
├── business_logic/
│   ├── README.md
│   ├── overview.md
│   ├── time_tracking.md
│   ├── employee_management.md
│   ├── security_authorization.md
│   └── workflows.md
├── implementation/
│   ├── README.md
│   ├── setup_guide.md
│   ├── phpstan_fixes.md
│   └── corrections_log.md
├── features/
│   ├── README.md
│   ├── requirements.md
│   └── specifications.md
├── maintenance/
│   ├── README.md
│   ├── troubleshooting.md
│   └── optimization_guide.md
├── testing/
│   ├── README.md
│   └── test_strategies.md
└── analysis/
    ├── README.md
    ├── dipendentincloud_comparison.md
    └── feature_analysis.md
```

### Fase 3: Consolidamento Contenuti

**File da Consolidare:**
1. `business-logic*.md` → `business_logic/overview.md`
2. `phpstan*.md` → `implementation/phpstan_fixes.md`
3. `technical*.md` → `architecture/technical_architecture.md`
4. `employee-module*.md` → `implementation/corrections_log.md`

## Implementazione Rifattorizzazione

### Step 1: Backup e Analisi
- Backup contenuti importanti
- Identificazione file da mantenere vs eliminare
- Mappatura contenuti unici

### Step 2: Creazione Struttura Target
- Creazione cartelle con naming corretto
- Creazione file README per ogni sezione
- Migrazione contenuti consolidati

### Step 3: Aggiornamento Links
- Aggiornamento riferimenti interni
- Creazione backlinks bidirezionali
- Verifica integrità collegamenti

### Step 4: Cleanup
- Rimozione file duplicati/obsoleti
- Verifica finale struttura
- Validazione contenuti

## Mapping File Attuali → Target

```
MANTIENI (già corretti):
✅ README.md → README.md
✅ analysis/ → analysis/
✅ architecture/ → architecture/ 
✅ development/ → implementation/
✅ features/ → features/
✅ testing/ → testing/
✅ maintenance/ → maintenance/

RINOMINA (naming scorretto):
business-logic*.md → business_logic/
phpstan*.md → implementation/phpstan_fixes.md
technical*.md → architecture/

ELIMINA (duplicati):
business_logic.md (se duplicato)
implementation_*.md (se duplicato)
employee-module-*.md (se obsoleto)
```

## Checklist Qualità

### Convenzioni Naming
- [ ] Tutti i file in minuscolo (eccetto README.md)
- [ ] Underscore invece di dash
- [ ] Nomi descrittivi e consistenti
- [ ] Struttura cartelle logica

### Contenuti
- [ ] Nessun contenuto duplicato
- [ ] Link interni funzionanti
- [ ] Backlinks bidirezionali
- [ ] Contenuti aggiornati e accurati

### Struttura
- [ ] Gerarchia logica e navigabile
- [ ] README per ogni cartella
- [ ] Indici e sommari aggiornati
- [ ] Collegamenti con docs root

---

*Piano creato: 2025-01-06*  
*Target: Struttura docs conforme Laraxot*  
*Stato: Ready for implementation*
