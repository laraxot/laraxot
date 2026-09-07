---
name: module-theme-root-md-count-audit-2026-09-07
description: Audit .md file count in every module/theme root against the max-6 rule; drifted back to non-compliant within hours, re-fixed via swarm
metadata:
  type: story
  status: done
  date: 2026-09-07
  claimed_by: claude-sonnet-5 (this session)
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/15
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

## Aggiornamento 2026-09-07 (~2h dopo) — la claim "0 violazioni" era già stale

Una sessione diversa (questa) ha rieseguito lo stesso controllo poche ore dopo
e trovato **6 violazioni reali**, non zero:

| Dir | md | txt | causa |
|---|---|---|---|
| `Modules/AI/` | 3 | 3 | `.txt` di root (report phpmd/pest/closure) mai spostati |
| `Modules/Media/` | 3 | 2 | `.txt` di root (phpmd/pest) mai spostati |
| `Modules/Cms/` | 16 | 0 | 14 file duplicati (byte-identici a copie già in `docs/root-md-files/` da un import mai ripulito) |
| `Modules/UI/` | 31 | 0 | 25 file duplicati (stesso pattern, `docs/raw/root-import/`) |
| `Modules/Xot/` | 139 | 0 | bulk operation non committata, mtime uniforme `12:16`, probabile script txt→md interrotto — **non** una regressione della consolidazione 2026-09-05 (quella *era* rimasta a 1 file tracciato; i 139 erano tutti untracked, poi accidentalmente committati da un altro agente con un `git add -A` a metà del mio audit) |

**Lezione:** "conforme oggi" su un repo con ~30 agenti concorrenti non implica
"conforme fra due ore". La verifica del 2026-09-05 era corretta al momento;
quella delle 2026-09-07 mattina pure; il drift è arrivato dopo, da operazioni
bulk non coordinate. Ri-controllare prima di fidarsi, sempre — non della memoria
di **nessuna** sessione precedente, inclusa questa stessa story.

**Fix applicato** (swarm di 5 agenti paralleli, questa sessione):
- AI: 3 `.txt` → `docs/root-txt-files/` — commit `437b344`
- Media: 2 `.txt` → `docs/archived/` — commit `294a9a50`
- Cms: 14 file duplicati rimossi (root già coperta da `docs/root-md-files/`) — commit `d719fc83`
- UI: 25 file duplicati rimossi/fusi (root già coperta da `docs/raw/root-import/`) — commit `d4e1f578`
- Xot: 138 file spostati in `docs/archived/` (gitignored, già politica del repo) — commit `9982f978` (+ collisione con `git add -A` di un altro agente risolta, vedi `Modules/Xot/docs/stories/18.3.module-theme-root-md-file-limit-audit.story.md`)

Tutti e 5 committati e pushati sul remote del rispettivo modulo. Verifica finale
post-fix: 0 violazioni reali (ri-eseguito lo stesso comando `find` di questa story).

**`Themes/docs/` ancora non risolto**: confermato non essere un tema vero (nessun
`.git` proprio), 30 file di documentazione architetturale modules-vs-themes a
livello di progetto. Chiesto all'utente dove spostarlo (docs/wiki/ vs laravel/docs/
vs lascia stare) — risposta: "lascia stare per ora". Resta un debito noto, non
un blocco.
