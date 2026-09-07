---
title: "PHPStan zero — Timber: completato, 10 -> 0 errori"
type: coordination
tags: [phpstan, timber, done, epic4, filament-canonical-structure]
created: 2026-09-07T21:56:00+02:00
status: done
agent: claude (sonnet 5) — this session
scope: Modules/Timber
blocking: false
---

# Modules/Timber — ripresa dopo standdown 19:11

Riferimento: `docs/chat/2026-09-07-phpstan-timber-standdown.md` (standdown alle
~19:11, WIP Epic4 canonical-structure "caldo", mtime piu' recente 18:36:17,
~33-40 min prima di allora).

Riverificato ora (~21:56):
- Nessun mtime piu' recente di 18:36:17 su nessun file del modulo (oltre 3h
  fa).
- `bashscripts/lock/check.sh` LIBERO su tutti i file core coinvolti
  (`TimberCustomerResource.php`, `TimberProductResource.php`,
  `TimberDocumentResource.php`, e sul modulo stesso).
- Nessun `.lock` recente trovato per Timber.
- `docs/chat/INDEX.md` non riporta claim piu' recenti su Timber oltre lo
  standdown gia' noto.
- `phpstan analyse Modules/Timber` conferma esattamente gli stessi 10 errori
  gia' documentati nello standdown (stesso identico set, stesse righe).

WIP fermo/abbandonato, non piu' "caldo". Procedo a chiudere i 10 errori,
stesso pattern gia' generalizzato (metodi statici privati orfani post
estrazione Schemas/Tables Epic4 + return type array<int,X> invece di
array<string,Component> su 2 nuove classi Schemas). Committo selettivamente
solo i file che tocco per questo fix; il resto del WIP Epic4 (24 dir
Schemas/Tables + 20 file dirty) resta come trovato salvo dove il fix stesso
lo richiede (rimozione dei metodi morti nelle Resource + fix dei return type
nelle nuove classi Schemas, che fanno gia' parte dello stesso refactor).

## Esito

`phpstan analyse Modules/Timber --memory-limit=-1 --no-progress` (cache
pulita): **10 -> 0 errori**.

Fix: rimossi 7 metodi statici privati morti (logica gia' duplicata nelle
nuove `Schemas/*Form.php`) + relativi `use` orfani su
`TimberCustomerResource.php`, `TimberProductResource.php`,
`TimberDocumentResource.php`; aggiunta la chiave stringa mancante sul
componente top-level di `getFormSchema()`/`getInfolistSchema()` in
`TimberCustomerForm.php`, `TimberCustomerInfolist.php`,
`TimberProductForm.php`.

Pint (`--test`) passed sui 6 file toccati. PHPMD: solo 1 violazione
preesistente invariata di soglia (`CouplingBetweenObjects` su
`TimberCustomerResource.php`, valore ridotto non peggiorato). Pest: 162
failed/7 passed, stesso gap DB di test preesistente e documentato
(`workorder_data_test` senza le migration Timber/User), non causato da
questo fix, non toccato.

Commit selettivo (11 path passati a `git add`, 14 file nel commit incluse le
directory `Schemas/`/`Tables/` companion di Customer/Product/Document,
verificate con `php -l` prima di committarle — necessarie perche' il
Resource non ha piu' logica form/table inline dopo la rimozione dei metodi
morti): `7666fa7` su `laraxot/dev` (remote unico del modulo), push riuscito
senza conflitti (0 commit di scarto da altri agenti nel frattempo). Story
`01.Timber-phpstan-fix.story.md` aggiornata a `Done`. `docs/coverage.md`
aggiornato con una sezione dedicata a questa sessione.

Non toccati: gli altri ~17 file/dir dirty o untracked del modulo (Agent,
CustomerPrice, EInvoice, Invoice, PaymentInstallment, PriceList,
StockMovement, Supplier, Transporter, WoodSpecies) — gia' a 0 errori
PHPStan, WIP di altri agenti, fuori scope per questo task.
