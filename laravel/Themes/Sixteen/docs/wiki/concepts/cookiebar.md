---
title: Cookie Bar Concept
slug: cookiebar
qmd: "concept cookie bar gdpr consent bootstrap italia"
document_type: concept
category: documentation
status: stable
source_of_truth: true
scope: [laravel/Themes/Sixteen/docs/wiki/concepts]
related:
  - ../rules/no-bootstrap-runtime-assets-rule.md
  - ../entities/bootstrap-italia-class-inventory.md
  - ../design/auth-login-ux-design-wcag.md
type: note
updated: 2026-10-15
tags: [docs, sixteen, wiki]
---

# Cookie Bar Concept

## Scope
Defines the implementation of the cookie consent banner that complies with GDPR and Italian regulations (Garante Privacy, provvedimento 10 giugno 2021).

## Core Principles
- **Preventive blocking**: Scripts di tracciamento e cookie non tecnici sono bloccati finché l'utente non dà il consenso
- **Granularità**: L'utente può scegliere di accettare solo cookie tecnici, oppure anche quelli di analisi e di marketing
- **Trasparenza**: Chiare informazioni su cosa sono i cookie, quali vengono utilizzati e per quanto tempo
- **Facile revoca**: L'utente deve poter modificare le proprie scelte in qualsiasi momento
- **Documentazione**: Il consenso deve essere registrato e dimostrabile (onere della prova)

## Legal Basis (Garante Privacy, 10 giugno 2021)
- **Cookie tecnici**: Non richiedono consenso, ma è necessario fornire informativa (art. 122 D.Lgs. 196/2003)
- **Cookie di analisi**: Richiedono consenso se non anonimizzati e se utilizzati per profilazione
- **Cookie di marketing**: Sempre richiedono consenso esplicito

## Technical Implementation
- **Component**: `resources/views/components/bootstrap-italia/cookiebar.blade.php`
- **Attributes**:
  - `data-bs-accept="cookiebar"`: Attributo Bootstrap Italia per il funzionamento
  - Link alla pagina `/disclaimer` (che in realtà punta alla privacy policy)
  - Testo multilingua (it/en/de/es/fr/ru)
- **Styling**: Utilizza TailwindCSS e token CSS centralizzati (`resources/css/components/cookiebar-tokens.css`)
- **Behavior**:
  - Alla prima visita: mostra il banner
  - Dopo accettazione: nasconde il banner e ricorda la scelta (via localStorage o cookie tecnico)
  - Dopo rifiuto: permette la navigazione con solo cookie tecnici
  - L'utente può richiamare il banner tramite un link nel footer o nella privacy policy

## Consent Storage
- Utilizza il modulo `Gdpr` per salvare i consensi nel database (tabella `consents`)
- Ogni consenso è associato a:
  - `user_id` (se loggato) o `ip_address` (se guest)
  - `consent_type` (privacy, terms, marketing)
  - `accepted_at` (timestamp)
  - `version` (versione dell'informativa)
- Questo soddisfa l'onere della prova del consenso (GDPR art. 7(1))

## Multilingua
- I testi sono definiti nei file di lingua:
  - `resources/lang/en/cookiebar.php`
  - `resources/lang/it/cookiebar.php`
  - etc.
- Il componente Blade utilizza `__()` per la traduzione

## Accessibilità
- WCAG 2.1 AA conforme
- Focus gestibile (`:focus-visible`)
- Contrasto adeguato tra testo e sfondo
- Navigabile da tastiera (Tab, Enter, Esc)

## Related Concepts
- `privacy`: Pagina a cui il banner linka per l'informativa completa
- `gdpr-consent`: Sistema di gestione dei consensi nel modulo Gdpr
- `registration`: Form di registrazione che deve rispettare gli stessi principi di consenso
- `unsubscribe`: Meccanismo per revocare il consenso al marketing

## Open Objectives
- [ ] Verificare che il banner compaia solo sui primi accessi e dopo la scadenza del consenso
- [ ] Aggiornare il testo con eventuali nuovi trattamenti dati
- [ ] Tradurre in tutte le lingue supportate
- [ ] Verificare conformità con le ultime linee guida Garante Privacy (aggiornamenti successivi al 2021)

## References
- Provvedimento del Garante Privacy 10 giugno 2021, n. 231
- Linee guida Garante Privacy sui cookie e altri strumenti di tracciamento (8 maggio 2014)
- GDPR Regolamento UE 2016/679, art. 6 e 7
- Indicazioni AGID sui siti web delle PA riguardo ai cookie