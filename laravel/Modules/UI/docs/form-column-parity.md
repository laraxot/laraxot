---
title: "Parita' Forms/Components <-> Tables/Columns in UI"
type: rule
tags: [filament, forms, columns, parity, ui]
created: 2026-09-01
updated: 2026-09-01
qmd: "form column parity ui addresscolumn openinghourscolumn"
related:
  - "../../Ptv/docs/form-column-parity.md"
  - "../app/Filament/Tables/Columns/GroupColumn.php"
---

# Parita' Forms/Components <-> Tables/Columns in UI

SSoT della regola: [`Modules/Ptv/docs/form-column-parity.md`](../../Ptv/docs/form-column-parity.md).
Questo file registra solo l'applicazione della regola dentro UI.

## Misura del 2026-09-01 (da Ptv/form-column-parity.md, tabella "altri moduli")

Su 15 componenti in `Modules/UI/app/Filament/Forms/Components/`, 2 avevano gemello
(`SelectState`, `TreeField`), 13 no. Applicando il discriminante della regola (raccoglie
piu' campi del model / e' un fatto di dominio → gemello; e' un modo di inserire un
valore → nessun gemello), solo **2 erano legittimi da creare**:

- `AddressField` (5 campi: country, street, city, state, zip, via relazione) → nessun gemello
- `OpeningHoursField` (orari settimanali per fascia) → nessun gemello

Gli altri 11 senza gemello sono widget di input (picker, editor, checkbox list, select)
senza fatto di dominio da mostrare in lista — nessun gemello dovuto, per lo stesso
discriminante.

## Fatto

- **`AddressColumn`** (`app/Filament/Tables/Columns/AddressColumn.php`) — estende
  `GroupColumn` come `AssenzeColumn` in Ptv, un `TextColumn` per campo con nome
  `{relazione}.{campo}` (dot-notation Filament per relazione). `fields()` per
  restringere l'elenco.
- **`OpeningHoursColumn`** (`app/Filament/Tables/Columns/OpeningHoursColumn.php`) —
  **non** estende `GroupColumn`: 28 `TimePicker` per riga non sono leggibili in
  tabella (il form ne ha uno per giorno/fascia). Estende `TextColumn` con un
  riepilogo testuale per giorno (`Lun 09:00-13:00, 14:00-18:00 · Mar chiuso · ...`),
  logica esposta come metodo pubblico statico `summarizeOpeningHours()` per essere testabile
  senza reflection sugli interni di Filament — vedi "Parita' non significa clone",
  punto 3, in Ptv/form-column-parity.md: la variabilita' si sposta nello stato della
  cella, non nello schema.

Test: `tests/Feature/AddressColumnTest.php`, `tests/Feature/OpeningHoursColumnTest.php`.
