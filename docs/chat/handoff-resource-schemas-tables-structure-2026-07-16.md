---
title: "Handoff — Resource Schemas/Tables structure completa (TechPlanner + Employee)"
updated: 2026-07-16
agents: [Claude-Sonnet-5]
---

# Handoff — Schemas/Form, Schemas/Infolist, Tables/*Table per Resource Filament

## Task

Ogni `Modules/*/app/Filament/Resources/<Name>Resource/` deve avere:

```
<Name>Resource/
├── Schemas/
│   ├── <Name>Form.php      # extends XotBaseResourceForm
│   └── <Name>Infolist.php  # extends XotBaseResourceInfolist
└── Tables/
    └── <NamePlural>Table.php  # extends XotBaseResourceTable
```

## Audit iniziale (90 Resource dir totali)

Solo 8 Resource reali mancavano la struttura (esclusi `XotBaseResource` astratta e `ProbeResource` fixture di test):

- TechPlanner: `AppointmentResource`, `ClientResource`, `DeviceResource`, `LegalOfficeResource`, `LegalRepresentativeResource`, `MedicalDirectorResource`, `PhoneCallResource` (7)
- Employee: `WorkHourResource` (1)

`TechPlanner/MailTemplateResource` estende `Notify\...\MailTemplateResource` (già conforme) → nessuna azione necessaria.

`Blog/{Author,Post,User}Resource` e `Rating/HasRatingResource` **non sono Resource reali** — cartelle orfane con solo `Filters/`/`Widgets/`/`RelationManagers/`, nessuna classe `*Resource.php`. Non toccate, segnalate come scaffolding morto.

## Fatto

- Tutti gli 8 Resource sopra ora hanno Schemas/Form+Infolist e Tables/*Table.
- Le classi base `<Name>Resource.php` delegano `getFormSchema()`/`getInfolistSchema()` alle nuove classi Schemas.
- Le Page `List*`/`View*` delegano a `(new *Table)->getTableColumns()` e `*Infolist::getInfolistSchema()`.
- **Collisione multi-agente rilevata e risolta in convergenza**: un altro agente stava lavorando in parallelo sugli stessi file (`LegalRepresentativeResource`, `PhoneCallResource`, `MedicalDirectorResource`, `LegalOfficeResource`) — nessun conflitto di contenuto, solo sovrapposizione di scope. Il contenuto finale è coerente e verificato.
- PHPStan pulito su `Modules/TechPlanner` e `Modules/Employee` (0 errori nuovi; resta solo il rumore di config preesistente `@mixin` a livello repo).
- Aggiornati anche 2 doc con esempio di struttura traduzioni enum sbagliato (`Notify/docs/enums/contact-type-enum.md`, `Geo/docs/enums/address-item-enum.md`) — task precedente nella stessa sessione.

## Non fatto / prossimo agente

- Non ho ancora studiato a fondo https://github.com/filamentphp/demo per pattern aggiuntivi da importare (richiesto dall'utente, non completato per limiti di tempo in questa sessione).
- Le cartelle `docs/` dei moduli/temi contengono molti duplicati quasi-identici (es. `Modules/UI/docs/table-layout-enum-usage-1-1.md`) — non consolidati, fuori scope di questo handoff.
- Verificare se `Blog/{Author,Post,User}Resource` vadano completati da zero o rimossi (cartelle Filters orfane senza classe Resource).
