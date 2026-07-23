# REGOLA FONDAMENTALE: Naming Convention Tema Sixteen

## LEGGE ASSOLUTA E INVIOLABILE

**Il tema Sixteen è completamente AGID-compliant per design. NON aggiungere mai "agid" nei nomi di componenti, file o cartelle.**

## Principio Fondamentale

Il tema Sixteen è stato progettato fin dall'inizio per essere conforme alle linee guida AGID (Agenzia per l'Italia Digitale). Ogni componente, layout e funzionalità è già AGID-compliant per default.

## Regole di Naming

### ✅ CORRETTO
```
components/
├── breadcrumb.blade.php
├── footer-institutional.blade.php  
├── header-institutional.blade.php
├── login-form.blade.php
└── blocks/forms/login-card.blade.php
```

### ❌ ERRATO (DA NON USARE MAI)
```
components/agid/
├── breadcrumb.blade.php
├── footer-institutional.blade.php
├── header-institutional.blade.php
├── login-form.blade.php
└── blocks/forms/login-card-agid.blade.php
```

## Motivazione

1. **Ridondanza**: Aggiungere "agid" è ridondante perché tutto il tema è già AGID-compliant
2. **Confusione**: Crea confusione tra componenti "agid" e "non-agid" quando tutto è AGID
3. **Manutenibilità**: Semplifica la struttura e la manutenzione del codice
4. **Coerenza**: Mantiene coerenza con la filosofia del tema

## Riferimenti Componenti

### ✅ CORRETTO
```blade
<x-pub_theme::breadcrumb />
<x-pub_theme::footer-institutional />
<x-pub_theme::header-institutional />
<x-pub_theme::login-form />
<x-pub_theme::blocks.forms.login-card />
```

### ❌ ERRATO
```blade
<x-pub_theme::agid.breadcrumb />
<x-pub_theme::agid.footer-institutional />
<x-pub_theme::agid.header-institutional />
<x-pub_theme::agid.login-form />
<x-pub_theme::blocks.forms.login-card-agid />
```

## Implementazione

Quando si lavora con il tema Sixteen:

1. **Mai** creare cartelle con nome "agid"
2. **Mai** aggiungere suffissi "-agid" ai componenti
3. **Sempre** usare nomi diretti e descrittivi
4. **Sempre** ricordare che tutto è già AGID-compliant

## Correzioni da Applicare

Se si trovano componenti con naming errato:

1. Rinominare i file rimuovendo "agid" dal nome
2. Spostare i componenti dalla cartella `agid/` alla cartella `components/`
3. Aggiornare tutti i riferimenti nei file blade
4. Aggiornare la documentazione

## Memoria Storica

Questa regola è stata stabilita dopo aver identificato errori ricorrenti nell'uso di suffissi/prefissi "agid" ridondanti nel tema Sixteen.

**Data**: 2025-08-01  
**Motivo**: Correzione logica di naming convention  
**Impatto**: Tutti i componenti del tema Sixteen

---

*Questa è una regola fondamentale che deve essere sempre rispettata e mai violata.*
