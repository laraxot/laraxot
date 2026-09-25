# Story: Xot — un solo `.code-workspace`, nome dal remote git

## Status
Done — 3 file spuri rimossi/rinominati, root Xot ora conforme.

## Contesto
Regola (`bashscripts/ai/wiki/rules/module-theme-root-hygiene.md` sez. 2): ogni
modulo/tema ha **un solo** `.code-workspace` in root, nome derivato dal
proprio `git remote -v`, non dal nome della cartella:

```
git -C laravel/Modules/Xot remote -v
# git@github.com:laraxot/module_xot_fila5.git
```

`module_xot_fila5` → togli suffisso base `_fila5` → antepone underscore →
`_module_xot.code-workspace`.

L'utente ha segnalato la violazione con l'esempio esatto di questo modulo,
dopo aver verificato lui stesso `git remote -v` dentro `laravel/Modules/Xot/`.

## Cosa c'era (ground-truth sweep sui 22 target moduli/temi)

Unico target non conforme su 22 (tutti gli altri: 1 file, nome corretto).
Xot aveva 4 file `.code-workspace` simultanei:

| File | Verdetto | Storia commit (`--follow`, ultimi 3) |
|---|---|---|
| `_module_xot_fila5.code-workspace` | sbagliato (suffisso `_fila5` non tolto) | `967ddd3a9c`, `ac88e2845e`, `4c596ed614` |
| `_xot.code-workspace` | sbagliato (manca prefisso `module_`) | `967ddd3a9c`, `dc0d386aa3`, `062753b88b` |
| `_activity.code-workspace` | sbagliato (nome di un altro modulo) | identica a `_xot.code-workspace` |
| `_module_xot.code-workspace` | corretto (nome canonico) | `c254b66aa1`, `26a649b623`, `1906c4df33` — storia distinta |

## Perche' (root cause)

`_xot.code-workspace` e `_activity.code-workspace` erano **byte-identici** fra
loro e condividevano gli stessi 3 commit piu' recenti: creati/copiati insieme
nello stesso evento, non due incidenti separati. Coerente con l'incidente
storico gia' documentato in `docs/chat/root-hygiene-workspace-slice-2026-08-24.md`
(cancellazione di massa di tutti i `.code-workspace` seguita da restore da
`git show HEAD:`, dove almeno un file restorato e' finito con un nome preso da
un modulo diverso). `_module_xot_fila5.code-workspace` aveva invece una storia
commit completamente separata e un contenuto leggermente diverso (nessuna
riga commentata nelle settings): artefatto piu' vecchio, precedente
all'adozione della regola "il suffisso `_fila5` si toglie".

Nessuno dei 4 file conteneva dati specifici del modulo nel contenuto stesso
(solo `"path": "."`, generico) — il rischio di perdita contenuto era quindi
nullo, la sola differenza reale era quale file avesse il nome giusto.

## Cosa e' stato fatto

1. `_xot.code-workspace` e `_activity.code-workspace`: gia' rimossi nel
   working tree da una sessione concorrente durante l'investigazione (stato
   verificato coerente col fatto che erano duplicati di contenuto).
2. `_module_xot.code-workspace` (nome corretto) era stato **cancellato** da
   una sessione concorrente mentre `_module_xot_fila5.code-workspace` (nome
   sbagliato) veniva mantenuto con contenuto aggiornato — esito opposto alla
   regola. Rilevato ri-misurando lo stato subito prima di agire (vedi memoria
   `verify-before-edit-concurrent-sessions`).
3. Fix applicato in questa sessione: `git mv
   _module_xot_fila5.code-workspace _module_xot.code-workspace`, preservando
   il contenuto (rich, con settings PHP-CS-Fixer/PHPMD) che era sopravvissuto
   nel working tree.
4. Ri-sweep completo sui 22 target: tutti conformi, un solo file per target,
   nome corretto in ognuno.

## Nota multi-sessione
Come per `uppercase-root-dirs-cleanup.story.md`: piu' sessioni Claude Code
concorrenti sullo stesso working tree hanno toccato questi file durante
l'investigazione, incluso un flip osservato in tempo reale (stato letto due
volte a pochi secondi di distanza, risultato diverso). Nessun lock preso da
nessuna delle sessioni note su questi path specifici.

## Verifica
```bash
find laravel/Modules laravel/Themes -maxdepth 1 -iname '*.code-workspace'
# atteso: esattamente 1 per modulo/tema con remote proprio, nome = _<repo-senza-suffisso-fila5>.code-workspace
```
Sweep eseguito su tutti e 22 i target: 0 violazioni residue.
