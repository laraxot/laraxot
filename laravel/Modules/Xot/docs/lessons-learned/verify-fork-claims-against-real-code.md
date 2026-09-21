---
name: verify-fork-claims-against-real-code
description: "Il report finale di una fork è un log di cosa ha fatto, non lo stato attuale del file: verifica sul codice prima di riportarlo all'utente"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues: []
---

# Il report di una fork non è il codice

## L'errore che si è ripetuto

Il coordinatore ha risposto a una domanda specifica dell'utente ("che icona hai messo su
Salva, metterai un floppy!") ripetendo il testo del report finale di una fork
("heroicon-o-arrow-up-circle, niente floppy") senza aprire il file. L'utente aveva fatto
una battuta puntuale — un segnale che meritava verifica diretta, non fiducia nel riassunto
altrui.

Verifica successiva (`git show <commit> -- file` + `git status`/`git blame` sullo stesso
file): il commit della fork conteneva davvero l'icona Heroicon corretta. Ma il working
tree aveva una modifica **successiva e non committata** che aveva sostituito l'attributo
Filament con un `<svg>` inline disegnato a mano — il path è esattamente l'icona standard
"salva/floppy disk". Il floppy non era nel commit riportato: era arrivato dopo, nel
working tree condiviso, da un intervento successivo non tracciato da nessuna fork nota.

## Perché è sbagliato

Un report di fork descrive cosa quella fork ha fatto **in quel momento**, non lo stato
attuale del file. In una sessione con decine di fork concorrenti sullo stesso working
tree, un file può cambiare di nuovo dopo che il report è stato scritto. Trattare il report
come "la verità corrente" invece che come "un log di un'azione passata" produce
affermazioni che sembrano sicure ma sono già scadute.

## Come si fa correttamente

Se l'utente fa una domanda specifica e verificabile su un dettaglio di codice/UI ("che
icona hai messo", "cosa contiene ora quel file"), rispondi leggendo il file in quel
momento:

```bash
git show <commit-riportato-dal-report> -- <file>   # cosa fu committato
git status --short -- <file>                        # è cambiato da allora?
git blame -L <righe>,<righe> <file>                  # chi/quando, "Not Committed Yet" = modifica pendente
```

Non ripetere il riassunto di un report precedente come se fosse osservazione diretta.

## Come riconoscerlo in futuro

Segnali che richiedono verifica diretta invece di fidarsi del report:
- La domanda dell'utente è specifica e puntuale (non generica), specialmente se in tono
  scherzoso o dubbioso — spesso significa che ha già visto qualcosa che non torna.
- È passato del tempo/altro lavoro fra il report della fork e la domanda dell'utente, in
  un working tree condiviso da fork concorrenti.
- Il dettaglio in questione è visivo/di rendering (icona, testo, layout) — più facile da
  cambiare "silenziosamente" in un secondo passaggio rispetto a una struttura di codice.

## Riferimenti

- [[output-che-vedo-viene-da-li]] — stesso principio applicato a script bash con codice
  morto dopo un `exit`; qui applicato a un report di sub-agent come fonte non affidabile
  quanto il file reale.
- Commit `6c7600a` (IndennitaResponsabilita) — icona corretta committata; modifica
  successiva al floppy non committata, trovata solo con verifica diretta.
