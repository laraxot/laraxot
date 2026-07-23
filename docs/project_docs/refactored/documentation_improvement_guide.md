# Guida Miglioramento Documentazione - DRY e KISS

## Principi Fondamentali

### DRY (Don't Repeat Yourself)
- **Evitare duplicazioni** di informazioni tra documenti
- **Centralizzare** le informazioni comuni
- **Riferimenti** invece di ripetere contenuti
- **Template** per documenti simili

### KISS (Keep It Simple, Stupid)
- **Semplicità** nella struttura e nel linguaggio
- **Chiarezza** nell'organizzazione
- **Accessibilità** per tutti i livelli di competenza
- **Efficienza** nella navigazione

## Struttura Standard Documenti

### 1. Header Documento
```markdown
# Titolo Documento

## Descrizione Breve
Breve descrizione dello scopo del documento

## Collegamenti Correlati
- [Documento Correlato 1](link1.md)
- [Documento Correlato 2](link2.md)

## Ultimo Aggiornamento
Data: YYYY-MM-DD
Versione: X.Y.Z
```

### 2. Sezioni Standard
```markdown
## Overview
Descrizione generale del contenuto

## Prerequisiti
Cosa serve per comprendere il documento

## Contenuto Principale
### Sezione 1
### Sezione 2

## Esempi
Esempi pratici di utilizzo

## Troubleshooting
Problemi comuni e soluzioni

## Collegamenti
Link a documentazione esterna
```

## Regole di Naming

### File e Cartelle
- ✅ **Corretto**: `auth_best_practices.md`
- ❌ **Errato**: `Auth_Best_Practices.md`
- ✅ **Corretto**: `user_module_structure.md`
- ❌ **Errato**: `UserModuleStructure.md`

### Eccezioni
- `README.md` - Unica eccezione per maiuscole

## Organizzazione Contenuti

### 1. Gerarchia Informazioni
```
1. Overview/Introduzione
2. Prerequisiti
3. Contenuto principale
4. Esempi pratici
5. Troubleshooting
6. Collegamenti
```

### 2. Evitare Duplicazioni
```markdown
<!-- ❌ ERRATO -->
# Modulo User
## Installazione
1. Composer install
2. Migrations
3. Config

# Modulo Geo  
## Installazione
1. Composer install
2. Migrations
3. Config

<!-- ✅ CORRETTO -->
# Modulo User
## Installazione
Vedi [Guida Installazione Moduli](../installation.md)

# Modulo Geo
## Installazione  
Vedi [Guida Installazione Moduli](../installation.md)
```

## Template Standard

### Template Modulo
```markdown
# Modulo {NomeModulo}

## Overview
Descrizione del modulo e delle sue funzionalità principali

## Installazione
Vedi [Guida Installazione Moduli](../installation.md)

## Configurazione
### File di Config
- `config/{modulo}.php`

### Variabili Ambiente
- `{MODULO}_ENABLED=true`

## Struttura
```
app/
├── Models/
├── Controllers/
└── Services/
```

## API
### Endpoints Principali
- `GET /api/{modulo}` - Lista
- `POST /api/{modulo}` - Crea
- `PUT /api/{modulo}/{id}` - Aggiorna
- `DELETE /api/{modulo}/{id}` - Elimina

## Esempi
```php
// Esempio di utilizzo
```

## Troubleshooting
### Problemi Comuni
1. **Errore X**: Soluzione Y
2. **Errore Z**: Soluzione W

## Collegamenti
- [Documentazione Laravel](https://laravel.com/docs)
- [Modulo Correlato](../altro-modulo/README.md)
```

### Template Best Practices
```markdown
# Best Practices - {Argomento}

## Regole Critiche
### 1. Regola Principale
- ✅ **Corretto**: Descrizione
- ❌ **Errato**: Descrizione

### 2. Regola Secondaria
- ✅ **Corretto**: Descrizione
- ❌ **Errato**: Descrizione

## Esempi Pratici
```code
// Esempio corretto
```

```code
// Esempio errato
```

## Checklist Implementazione
- [ ] Punto 1
- [ ] Punto 2
- [ ] Punto 3

## Collegamenti
- [Documentazione Correlata](link.md)
```

## Miglioramenti Specifici

### 1. Documentazione Moduli
- **Centralizzare** installazione e configurazione
- **Standardizzare** struttura dei moduli
- **Evitare** duplicazioni tra moduli simili

### 2. Documentazione Temi
- **Separare** frontoffice e backoffice
- **Documentare** componenti UI disponibili
- **Standardizzare** layout e struttura

### 3. Documentazione API
- **Centralizzare** autenticazione e autorizzazione
- **Standardizzare** formati di risposta
- **Documentare** errori comuni

### 4. Documentazione Deployment
- **Centralizzare** configurazioni ambiente
- **Standardizzare** procedure di deploy
- **Documentare** troubleshooting

## Processo di Miglioramento

### 1. Analisi
- Identificare duplicazioni
- Trovare informazioni obsolete
- Valutare chiarezza e accessibilità

### 2. Ristrutturazione
- Applicare template standard
- Centralizzare informazioni comuni
- Creare collegamenti invece di duplicare

### 3. Validazione
- Verificare coerenza
- Testare navigabilità
- Controllare aggiornamenti

### 4. Manutenzione
- Aggiornare regolarmente
- Verificare collegamenti
- Mantenere versioning

## Checklist Miglioramento

### Struttura
- [ ] Header standard presente
- [ ] Sezioni organizzate logicamente
- [ ] Collegamenti correlati aggiornati
- [ ] Naming conforme alle regole

### Contenuto
- [ ] Informazioni non duplicate
- [ ] Esempi pratici inclusi
- [ ] Troubleshooting presente
- [ ] Collegamenti funzionanti

### Qualità
- [ ] Linguaggio chiaro e semplice
- [ ] Struttura logica
- [ ] Informazioni aggiornate
- [ ] Principi DRY e KISS rispettati

## Strumenti di Supporto

### 1. Script di Validazione
```bash
# Verifica naming convention
find . -name "*.md" -exec basename {} \; | grep -E '[A-Z]' | grep -v README.md

# Verifica collegamenti rotti
grep -r "\[.*\](" . --include="*.md" | grep -v "http"
```

### 2. Template Generator
```bash
# Crea nuovo documento con template
./scripts/create_doc.sh "nome_documento" "tipo_template"
```

### 3. Validator
```bash
# Valida struttura documenti
./scripts/validate_docs.sh
```

---
*Principi DRY e KISS applicati alla documentazione* 