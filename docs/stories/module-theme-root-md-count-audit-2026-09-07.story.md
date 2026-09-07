---
name: module-theme-root-md-count-audit-2026-09-07
description: Audit .md file count in every module/theme root against the max-6 rule; result already compliant
metadata:
  type: story
  status: done
  date: 2026-09-07
  claimed_by: claude-sonnet-5 (this session)
---

# Story: audit max-6 `.md` files in module/theme roots

## Richiesta utente

"nelle root dei moduli ci devono stare al max 6 files .md, controlla e
sistema tutti i moduli" — con richiesta di capire prima il perche' della
regola.

## Perche' la regola (capito dal codice/memoria, non assunto)

Segnale/rumore alto: una root di modulo con decine di `.md` sparsi rende
impossibile orientarsi (`README.md` + `ARCHITECTURE.md` + `CHANGELOG.md` +
`CONTRIBUTING.md` + `TESTING.md` + un sesto file a scelta — es.
`GETTING_STARTED.md`). Tutto il resto va negli overflow semantici sotto
`docs/{features,config,examples,guides,stories,archived}/`. Regola gia'
applicata in una sessione precedente (2026-09-05, vedi second brain
`project_module_doc_consolidation_audit`): Xot 129→6, UI 29→6, Cms 15→6,
Notify 13→6, Gdpr 8→6.

## Controllo altri agenti (obbligatorio, non fidarsi)

`docs/chat/` non ha una nota specifica su questo audit oggi. Data la
giornata caotica (script rotto che corrompe `BaseModel.php` in piu' moduli,
vedi `docs/chat/2026-09-07-URGENT-broken-script-corrupting-basemodel-files.md`,
e altri due incidenti URGENT su HasXotFactory), non fidarsi della memoria
del 2026-09-05 senza ricontrollare oggi.

## Verifica reale (oggi, sul filesystem)

```bash
for d in Modules/*/ Themes/*/; do
  n=$(find "$d" -maxdepth 1 -iname "*.md" | wc -l)
  echo "$n $d"
done | sort -rn
```

**Risultato: 52/52 directory in `Modules/*/` sono gia' a 6 file o meno**
(massimo osservato: 6, in `User`, `Notify`, `Gdpr`, `Modules/docs/`). **5/5
temi reali in `Themes/*/` sono ben sotto soglia** (`Zero` 4, `Sixteen` 3,
`TwentyOne` 1, `Meetup` 0, `Barthelemy` 0).

**Unico outlier:** `Themes/docs/` con 30 file `.md`. Non e' pero' la root
di un tema — e' l'hub di documentazione condivisa cross-tema, stesso ruolo
di `Modules/docs/` (che infatti e' gia' a 6, quindi gia' trattato con lo
stesso pattern in passato). La memoria della sessione 2026-09-05 nota
esplicitamente questo hub come eccezione alla regola dei 6 file (non e' un
modulo/tema, e' un indice).

## Esito

**Nessuna violazione trovata tra i moduli e i temi reali.** Il lavoro di
consolidamento del 2026-09-05 ha gia' portato tutto a compliance ed e'
rimasto tale. Non ho "sistemato" nulla perche' non c'era nulla da
sistemare — dichiararlo fatto senza controllare sarebbe stato un altro
errore dello stesso tipo gia' contestato oggi (BaseModel/BasePivot).

**Raccomandazione opzionale (non richiesta dalla regola letterale, per
coerenza):** `Themes/docs/` (30 file, hub condiviso) potrebbe ricevere lo
stesso trattamento di `Modules/docs/` in futuro (consolidare a un indice +
sottocartelle tematiche), ma essendo un hub e non una root di
modulo/tema non rientra nell'ambito letterale di questa richiesta. Nessuna
azione presa qui, segnalata per decisione futura dell'owner.
