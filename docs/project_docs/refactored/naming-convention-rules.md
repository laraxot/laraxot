# Convenzioni di Naming per Directory Docs

## Regola Fondamentale

**TUTTI i file e le cartelle nelle directory `docs/` DEVONO utilizzare caratteri minuscoli, con l'UNICA eccezione di `README.md`.**

## Esempi

### ✅ CORRETTO
```
docs/
├── README.md                    # ✅ Unica eccezione consentita
├── architecture.md              # ✅ Tutto minuscolo
├── installation-guide.md        # ✅ Trattini per separare parole
├── api/                         # ✅ Cartelle in minuscolo
│   ├── endpoints.md
│   └── authentication.md
└── troubleshooting/
    ├── common-issues.md
    └── error-codes.md
```

### ❌ ERRATO
```
docs/
├── TECHNICAL.md                 # ❌ Maiuscole non consentite
├── Installation-Guide.md        # ❌ Maiuscole non consentite
├── API/                         # ❌ Cartelle con maiuscole
│   ├── Endpoints.md
│   └── Authentication.md
└── TroubleShooting/
    ├── Common-Issues.md
    └── Error-Codes.md
```

## Motivazioni

1. **Coerenza**: Uniformità in tutto il progetto
2. **Portabilità**: Compatibilità cross-platform (Linux/Windows/macOS)
3. **Manutenibilità**: Facilita la navigazione e la ricerca
4. **Automazione**: Semplifica script e tool automatici
5. **Best Practice**: Segue gli standard moderni di documentazione

## Processo di Correzione

Quando si identificano file o cartelle non conformi:

1. **Rinominare** file e cartelle in lowercase
2. **Aggiornare** tutti i riferimenti nei file di documentazione
3. **Verificare** che i link interni funzionino correttamente
4. **Documentare** le modifiche effettuate

## Script di Verifica

Per verificare la conformità:

```bash
# Trova file con maiuscole (escludendo README.md)
find . -path "*/docs/*" -name "*[A-Z]*" | grep -v README.md

# Trova cartelle con maiuscole
find . -path "*/docs/*" -type d -name "*[A-Z]*"
```

## Applicazione

Questa regola si applica a:

- ✅ Tutti i file `.md` nelle directory `docs/`
- ✅ Tutte le sottocartelle nelle directory `docs/`
- ✅ Tutti i file di documentazione (non solo Markdown)
- ❌ **ECCEZIONE**: Solo `README.md` può contenere maiuscole

## Responsabilità

- **Sviluppatori**: Seguire la convenzione per nuovi file
- **Reviewer**: Verificare la conformità nei PR
- **Maintainer**: Eseguire verifiche periodiche
- **Automazione**: Script di CI/CD per controllo automatico

---

*Ultimo aggiornamento: 2025-07-30*
*Stato: Implementato e verificato in tutto il progetto*