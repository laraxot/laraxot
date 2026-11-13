---
title: "Code Quality Improvement Report — Employee"
type: report
tags: [code-quality, phpstan, pest, maintainability]
module: "Employee"
created: 2026-07-17
updated: 2026-07-17
qmd: "code quality baseline PHPStan Pest strict types Laraxot Employee"
story: STORY-001
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/46"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/47"
related:
  - "../../../../docs/stories/STORY-001-code-quality-moduli-temi.md"
---

# Code Quality Improvement Report — Employee

> Baseline statica riproducibile per orientare il miglioramento. I conteggi sono segnali, non sostituiscono PHPStan, Pest o la review del flusso reale.

## Baseline

| Indicatore | Valore |
|---|---:|
| File PHP applicativi/database/route | 64 |
| File di test PHP | 13 |
| Rapporto test/file PHP | 20% |
| Candidati senza strict types | 62 |
| Marker TODO/FIXME/HACK/XXX | 5 |
| Estensioni Filament potenzialmente dirette | 0 |
| Controller da classificare FO/BO | 1 |
| Classi in app/Services o app/Support | 0 |
| Priorità iniziale | **alta** |

Rilevazione del 17 luglio 2026 sul working tree locale; esclusi vendor e dipendenze esterne.

## Rischi e priorità

1. **Type safety:** verificare i candidati e introdurre strict types nei file toccati, con tipi concreti e senza nuovi mixed.
2. **Regressioni:** il rapporto file/test non misura copertura. Proteggere prima autorizzazioni, scritture DB, business rule e bug noti.
3. **Laraxot:** confrontare ogni estensione Filament segnalata con XotBase/LangBase. Classificare i controller: vietati nel front office.
4. **Debito:** ogni marker residuo deve avere owner, motivazione e criterio di rimozione.
5. **Boundary:** non aggiungere business logic in Service/Support; riusare Actions con QueueableAction.

## Piano

### P0 — baseline affidabile

- Eseguire PHPStan L10 e Pest sul solo componente, senza modificare phpstan.neon per occultare errori.
- Classificare gli esiti come errore reale, dipendenza, test fragile o falso positivo documentato.
- Conservare comando ed esito ripetibile per ogni correzione.

### P1 — rischio di regressione

- Aggiungere il test minimo che fallisce per ogni flusso critico scoperto.
- Correggere la causa nel punto condiviso dopo aver verificato tutti i caller.
- Sostituire estensioni Filament dirette con la base Laraxot omologa.

### P2 — manutenibilità

- Eliminare codice morto, duplicati e wrapper senza valore prima di nuove astrazioni.
- Riportare business logic dispersa nelle Actions owner già esistenti.
- Separare metodi solo lungo responsabilità osservabili.

### P3 — continuità

- Gate CI scoped: PHPStan L10, Pest, formattazione e audit architetturali già presenti.
- Aggiornare il report solo con metriche ripetibili e tracciamento pertinente.

## Modifiche effettive da fare

1. **database/factories/TimeRecordFactory.php — strict types e firme.** Inserire declare(strict_types=1) subito dopo l’apertura PHP; eseguire PHPStan e sostituire i tipi impliciti o mixed emersi con tipi concreti. Verifica: PHPStan scoped e test che esercita la prima API pubblica del file.
2. **app/Providers/Filament/AdminPanelProvider.php:34:        // TODO: Riabilitare quando il problema di binding sarà risolto — debito eseguibile.** Verificare il caller e scegliere una sola uscita: implementare il comportamento con un test che fallisce prima della patch, oppure eliminare ramo e marker se non raggiungibili. Non lasciare il TODO come documentazione.
3. **app/Http/Controllers/EmployeeController.php — confine HTTP.** Cercare route e caller. Se serve il front office, sostituire il controller con pagina Folio/Volt e spostare la logica in una Action owner; se è API/back office, mantenere solo validazione e delega alla Action. Aggiungere un test HTTP della route reale.
6. **app/Http/Controllers/EmployeeController.php: store/update.** I metodi sono stub TODO: rimuovere le route se inutilizzate oppure creare Actions dedicate per create/update, validare input e aggiungere test per successo, autorizzazione ed errore.


- [ ] PHPStan L10 scoped senza errori non giustificati.
- [ ] Pest scoped verde sui flussi critici.
- [ ] Nessuna nuova estensione Filament diretta o controller FO.
- [ ] Nessuna nuova business logic in Services/Support.
- [ ] File PHP modificati con strict types e tipi concreti.
- [ ] Debito residuo con owner e criterio di rimozione.

## Criteri di uscita

## Verifica

Dalla cartella laravel/:

    ./vendor/bin/phpstan analyse Modules/Employee --memory-limit=-1
    ./vendor/bin/pest Modules/Employee/tests

Limite deliberato: niente coverage, mutation score o metriche di complessità finché PHPStan, Pest e review mirata bastano a decidere.