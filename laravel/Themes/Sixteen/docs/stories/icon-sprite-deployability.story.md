---
title: "[STORY] Lo sprite delle icone di Sixteen deve arrivare in produzione"
type: story
status: done
module: Sixteen
priority: high
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, ecc, sixteen, icone, sprite, deploy, gitignore, guardia]
qmd: "sprites.svg 404 icone gitignore dist deploy.sh public manifest check-ignore DesignComuniIconSpriteIsDeployableTest"
related:
  - front-office-production-readiness-audit.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-154-gate-di-messa-in-produzione.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-455-strumenti-del-gate-sopravvivono-alla-sync.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/111"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/95"]
---

# [STORY] Lo sprite delle icone di Sixteen deve arrivare in produzione

**Issue:** [#111](https://github.com/laraxot/theme_sixteen_fila5/issues/111) (aperta il 2026-11-12; resta aperta finche' non c'e' la verifica in produzione).

**Discussione (decisioni e alternative scartate):** [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95), complementare a [#93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93) (head SEO) e [#94](https://github.com/laraxot/theme_sixteen_fila5/discussions/94) (audit, area loggata).

## Fase BMAD

Fatta in due tempi: **Dev** (correzione, 2026-11-11) e **Doc** (questa story, 2026-11-12). Origine: difetti `F-01` e `F-02` dell'audit del front office
([story madre](front-office-production-readiness-audit.story.md)). ECC usato: `verification-loop`, `browser-qa`.

## Sintomo

`sprites.svg` rispondeva **404 su ogni pagina**, sia dal percorso diretto `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`
sia dalla rotta `/{lingua}/assets/...`. Sparivano le icone di header, footer, ricerca e menu utente. Nessun errore visibile all'utente, nessun test rosso.

Prova nello sweep anonimo (128 URL, ordine di esecuzione): le **prime 33 pagine** (desktop) e 32 (mobile) avevano lo sprite in 404; dopo la correzione le
**95 e 96 pagine consecutive** successive avevano 0 asset in errore e 0 errori di console non-404.

## Causa

1. `Themes/Sixteen/.gitignore` escludeva lo sprite in `public/` **due volte**: con una riga esplicita e con `dist/` (riga 11), che esclude la cartella.
   Git non rientra in una cartella esclusa: togliere solo la riga esplicita non basta.
2. `deploy.sh` non esegue il build del tema (`public_html/themes/Sixteen` e' un symlink a `laravel/Themes/Sixteen/public`, servito com'e' nel
   repository). Un checkout pulito non aveva il file, e il deploy controllava solo `manifest.json`.
3. `prepare-static-assets.mjs` scarica lo sprite da jsdelivr solo se manca, ma il deploy non lo lancia.

## Decisione e correzione

| Cosa | Dove |
|---|---|
| Tolta la riga esplicita e riaperti **tutti i livelli** fino al file con una catena di negazioni (`!public/design-comuni/` ... `!.../dist/svg/sprites.svg`), commentata col perche' | `Themes/Sixteen/.gitignore` |
| `test -f` sullo sprite subito dopo il controllo del manifest e **prima di `php artisan down`**: il sito non entra in manutenzione per un deploy che poi si ferma | `deploy.sh` (radice) |
| Guardia a 3 test (sotto) | `Themes/Sixteen/tests/Unit/DesignComuniIconSpriteIsDeployableTest.php` |

## Guardia

1. «ship the icon sprite in the versioned theme public output»: il file esiste in `public/`.
2. «does not git-ignore the sprite that production serves»: **interroga git** (`git check-ignore -q`, exit 1 = non ignorato), salta se il tema non ha `.git`.
3. «makes deploy.sh refuse a theme without the icon sprite»: `deploy.sh` contiene il controllo, nell'ordine giusto.

**Lezione:** la prima versione del secondo test cercava la riga nel `.gitignore` ed era **verde con `dist/` ancora attivo** (falso verde). Riscritta per
chiedere a git e provata **rossa su una copia con le sole regole di prima** (exit 0), poi verde. `git check-ignore -v` stampa anche le negazioni: per il
verdetto serve `-q` e l'exit code.

## Accettazione (con prova)

- [x] `git -C laravel/Themes/Sixteen check-ignore -q -- public/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` esce con **1** (ricontrollato 2026-11-11 21:39 e 2026-11-12).
- [x] `cd laravel && vendor/bin/pest Themes/Sixteen/tests/Unit/DesignComuniIconSpriteIsDeployableTest.php`: **3 passed (5 assertions)**.
- [x] `bash -n deploy.sh` esce 0; `curl` dello sprite dal dev risponde **200**.
- [x] Sweep dopo la correzione: 0 asset in errore sulle pagine successive.
- [x] Sprite, `.gitignore` e `deploy.sh` sono **tracciati, committati e pushati su `dev`**: non da questa sessione ma dalla sync fra le due macchine (tema `43585ff6`, radice `6c515c3364`, 2026-11-12 ~13:45 UTC). Ricontrollato alle 13:48: sprite identico a HEAD, HTTP 200, 93.716 byte, Pest 3 passed. *(Correzione: una versione precedente di questa riga diceva «ancora non tracciato».)*
- [ ] **Verifica in produzione**: lo sprite risponde 200 dal webroot reale dopo il prossimo deploy (smoke automatico nell'onda 1 del [piano di hardening](../plans/front-office-hardening-plan.md)).

## Rischi residui

- Un checkout pulito ha lo sprite perche' e' versionato; se la sync lo cancellasse di nuovo, la guardia 1 e il controllo di `deploy.sh` lo segnalano ma non lo ricreano.
- La sync fra le due macchine ha cancellato file in passato (vedi [DCP-455](../../../../Modules/Fixcity/docs/stories/dcp-455-strumenti-del-gate-sopravvivono-alla-sync.story.md)): ricontrollare che lo sprite esista dopo ogni sync grossa.

## Come verificare da zero

```bash
cd laravel/Themes/Sixteen && git check-ignore -q -- public/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg; echo $?   # atteso: 1
cd ../.. && vendor/bin/pest Themes/Sixteen/tests/Unit/DesignComuniIconSpriteIsDeployableTest.php
curl -s -o /dev/null -w '%{http_code}\n' http://127.0.0.1:8081/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg   # atteso: 200
```
