## Mappatura nuovi moduli → panel

I 16 nuovi moduli di dominio provenienti da `gestionale_commesse` vanno integrati nei panel Filament esistenti. Reference SRC: `gestionale-panels-vs-themes.md`.

### Panel admin (esistente)
Tutti i moduli nuovi espongono le loro Resource nel panel admin principale:
- Customer, WorkOrder, Intervention, Catalog, Quotation, Billing, Fiscal, Signature, Vehicle
- Inventory, Bom, Production, Compliance, PublicProcurement, EnergyBroker, TimberBilling

### Panel operator (da creare/estendere)
I moduli operativi hanno visibilità anche su panel `operator`:
- **WorkOrder** — lettura/scrittura assegnazioni
- **Intervention** — timbratura, note, segnalazioni
- **Signature** — acquisizione firma su intervento
- **Quotation** — creazione preventivi (visibilità limitata)

### Panel customer (futuro)
Alcuni moduli potrebbero avere un panel `customer` in futuro:
- **WorkOrder** — stato commessa in sola lettura
- **Intervention** — storico interventi
- **Billing** — fatture emesse

### Temi FO (Folio/Volt)
I moduli nuovi non hanno pagine FO dedicate in prima battuta (Wave 1). Tutta l'interazione è via Filament panel. Eventuali pagine FO (es. portale cliente) sono Wave 6+.

### Vedi anche
- `bmad-output/epics.md`
- `bmad-output/stories/` — storie per ogni modulo
- `../Modules/*/docs/README.md` — docs specifiche per modulo
