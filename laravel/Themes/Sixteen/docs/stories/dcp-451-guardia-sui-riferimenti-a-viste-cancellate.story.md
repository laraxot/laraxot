---
id: dcp-451
title: "DCP-451: guardia sui riferimenti a viste cancellate (632 viste tolte da 483080dcac, 26 nomi ancora citati)"
type: story
status: open
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, guardia, viste, blade, include, componenti, mirror, regressione]
qmd: "guardia riferimenti viste cancellate 483080dcac 632 viste 26 nomi x-banner include extends view componenti anonimi UX-13 PartsIncludesResolveTest WizardIncludesResolveTest"
related:
  - ./front-office-production-readiness-audit.story.md
  - ./dcp-447-tema-ricostruibile-e-guardie-contro-i-mirror.story.md
  - ./dcp-453-ritiro-del-codice-morto-del-tema-decisione.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/103"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94"]
---

# DCP-451: guardia sui riferimenti a viste cancellate

Status: **aperta** (2026-11-12). Fase BMAD: Analysis (audit UX-13); Solution proposta, non scritta. Nessuna modifica fatta.

## Perché esiste

Il commit `483080dcac` ha cancellato **632 viste Sixteen**; il rapporto dell'audit dice che **26 nomi sono ancora citati dal codice** (esempio `<x-banner>` nel Blog). Una vista mancante non rompe né il build né i test che non la rendono:
la pagina va in 500 solo quando qualcuno la apre (è successo con `/it/tickets`, `/it/auth/login`, `/it/domande-frequenti` dopo `6cb55f0d`). Il numero 26 è del rapporto dell'11 novembre: **non l'ho rimisurato oggi**.

## Cosa copre già (e cosa no)

| Guardia esistente | Copre | Non copre |
|---|---|---|
| `tests/Unit/WizardIncludesResolveTest.php` | ogni `@include` del wizard, anche con prefisso `$variabile.` | il resto del tema |
| `tests/Unit/PartsIncludesResolveTest.php` | `@include($var.'.nome')` con `$var` assegnata a `pub_theme::cartella` (la variabile si cerca nel file, poi nelle antenate) | include con nome intero, `@extends`, `view()`, componenti `<x-…>`, `@livewire`, include col nome composto a runtime |

## Proposta

Estendere **la stessa tecnica** (scansione dei `.blade.php` e controllo che il bersaglio esista) invece di scriverne una nuova: ponytail, riuso del test esistente.

1. `@include('ns::cartella.nome')`, `@extends`, `@component`, `view('…')` con stringa letterale → il file deve esistere nel tema o nel modulo del namespace.
2. `<x-nome>` → una vista anonima in `resources/views/components/` del tema o dei moduli, o una classe componente registrata.
3. I nomi composti a runtime non si risolvono: restano fuori dalla guardia e vanno dichiarati (come fa già `PartsIncludesResolveTest`), oppure si provano con la scansione HTTP delle pagine chiave.
4. Prima di accendere la guardia si **misura** la lista dei 26 (o dei nomi veri di oggi): quelli reali si correggono o si ritirano con DCP-453; i falsi positivi si escludono con una lista scritta e motivata, non con un `skip` generico.

Attenzione ai falsi positivi: un componente può stare in un altro modulo o essere registrato dal provider; la risoluzione va fatta con i namespace reali, non con il solo percorso del tema.

## Criteri di accettazione (quando si lavora)

- [ ] Elenco misurato dei riferimenti pendenti (comando e data nella story).
- [ ] Guardia nel tema (`Themes/Sixteen/tests/Unit/`) con `uses(TestCase::class)` quando serve l'app, ed **eventuale** estensione dei test esistenti invece di un duplicato.
- [ ] **Prova inversa**: citare una vista inesistente → rosso; toglierla → verde.
- [ ] Ogni riferimento pendente corretto, ritirato (DCP-453) o escluso con motivo scritto.
- [ ] PHPStan zero e Pint sui file toccati; nessun `RefreshDatabase`.

## Verifica

Nessuna correzione in questa story. Esistenza dei due test già in tema controllata il 2026-11-12.
