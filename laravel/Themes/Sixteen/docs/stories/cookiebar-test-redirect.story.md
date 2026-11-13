---
title: "BootstrapItaliaComponentsTest: cookiebar punta a cookie-consent::index"
type: story
status: completed
created: 2026-10-15
updated: 2026-10-15
module: Sixteen
tags: [test, cookiebar, gdpr, refactor]
---

# BootstrapItaliaComponentsTest: cookiebar punta a cookie-consent::index

## Richiesta
Il test `cookiebar component has gdpr features` usava `$this->view('pub_theme::bootstrap-italia.cookiebar')` su una vista
legacy inesistente in questa workorder (il test era skipato da un `beforeEach`). Doveva essere reindirizzato sulla vista
canonica del modulo Gdpr.

## Analisi
- `pub_theme::bootstrap-italia.cookiebar` → `resources/views/bootstrap-italia/cookiebar.blade.php` → bridge per
  `components/feedback/cookiebar` (legacy, non usato da layout attivi).
- Vista canonica: `cookie-consent::index` (Gdpr), iniettata da `InjectCookieBanner`.

## Modifiche
- Rimozione del `beforeEach` skip.
- Sostituzione del test con `view('cookie-consent::index')->render()` e `Assert::assertStringContainsString/NotContainsString`:
  - `class="gdpr-cookiebar"`, `data-cookie-key="__cookie_consent"`, `data-cookiebar="info"` (solo cookie tecnici),
  - `data-cookie-action="reject"` (bottone "Ho capita"), `data-cookie-preferences` (link «Gestisci cookie»),
  - `data-cookie-days="180"`.
  - Negazione: `lcc-modal`, `js-lcc-` (vista statikbe), `data-bs-accept` (niente Bootstrap Italia),
    `role="alert"` (nessun alert Bootstrap).

## Verifica
```bash
php -l Themes/Sixteen/tests/Feature/Components/BootstrapItaliaComponentsTest.php  # OK
```
> Nota: il test non è stato eseguito in questo ambiente perché `TestCase` non è in scope per il theme Sixteen
> (`Pest\Exceptions\TestCaseClassOrTraitNotFound`). Il test è scritto per essere eseguito dal test suite radice.

## Decisioni
- **D-1**: il test ora documenta il contratto visivo del banner reale, non di una vista legacy.
- **D-2**: l'uso di `Assert::assertStringContainsString` (non `assertSee`) permette di controllare attributi HTML.

## Aperto
- Eseguire il test dalla test suite radice (`php artisan test`) quando l'ambiente DB sarà disponibile.