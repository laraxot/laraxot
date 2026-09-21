# Story: audit e consolidamento indice documentazione Sixteen

**Fase BMAD**: Documentation (docs-only, nessun codice applicativo toccato).

**Contesto**: `Themes/Sixteen/docs/` contiene 1436 file `.md` (412 alla radice), con almeno 4
tentativi di indice root paralleli (`index.md`, `INDEX.md`, `00-index.md`, `00-INDEX.md`) e
decine di cluster di file duplicati/superati (varianti di case, report datati vs. non datati,
coppie `segnalazione-*`/`ticket-*`).

**Azione**: riscritto `docs/index.md` come indice unico e canonico, organizzato per argomento,
con link a tutti i 412 file root piu' entry point per le sottocartelle (piccole elencate per
intero, grandi cantieri di parity descritte come pattern con link a README/INDEX locali). Nessun
file esistente rinominato, spostato o cancellato. Duplicati raggruppati sotto "Storico / da
consolidare" nello stesso `index.md`.

**Verifica**: script di controllo automatico ha confermato che tutti i link relativi del nuovo
`index.md` puntano a file/cartelle esistenti (740/740, escluso il link a questa story creata
subito dopo).

**Follow-up non incluso in questa story**: consolidamento effettivo dei cluster duplicati elencati
in "Storico / da consolidare" (richiede decisione di merge, fuori scopo per un task solo-indice).
