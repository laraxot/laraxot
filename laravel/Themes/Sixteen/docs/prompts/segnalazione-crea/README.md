# segnalazione-crea

Pagina unificata che raggruppa le pagine legacy:
- `segnalazione-01-privacy`
- `segnalazione-02-dati`
- `segnalazione-03-riepilogo`
- `segnalazione-04-conferma`

## Regole implementative
- naming classi PHP: usare `Ticket`, NON `Segnalazione`
- widget corretto: `Modules\Fixcity\Filament\Widgets\CreateTicketWizardWidget`
- Estende `XotBaseWizardWidget` (specializzazione multi-step su `XotBaseWidget`)
- **Usa** `Filament\Schemas\Components\Wizard` + `Wizard\Step` in `getFormSchema()`; vista Blade solo wrapper + `{{ $this->form }}`
- Navigazione step gestita dal wizard Filament; override `?step=` quando consentito (vedi `ticket-wizard-frontoffice.md`)

## UX target
- aspetto iniziale coerente con `segnalazione-01-privacy`
- step reali del wizard: **3** (segnalazione-04-conferma è pagina separata, NON step del wizard)
- le pagine legacy restano online

## Flusso completo

```
segnalazione-crea (wizard 3 step)
  Step 1: Privacy     → segnalazione-01-privacy
  Step 2: Dati        → segnalazione-02-dati
  Step 3: Riepilogo   → segnalazione-03-riepilogo  [SUBMIT QUI]
  ↓ redirect
segnalazione-04-conferma (pagina separata, NON nel wizard)
```

**CRITICO:** `submit()` nel widget fa redirect a `/{locale}/tests/segnalazione-04-conferma`. NON mostra successo inline.

## Wizard: 3 passi

| Step | Chiave traduzione | Contenuto |
|---|---|---|
| 1 privacy | `fixcity::segnalazione.steps.privacy.label` | Checkbox privacy, testo informativa |
| 2 data | `fixcity::segnalazione.steps.data.label` | Indirizzo, tipo, titolo, dettagli, email |
| 3 summary | `fixcity::segnalazione.steps.summary.label` | Riepilogo dati + pulsante submit → redirect 04-conferma |

Pattern traduzioni: `fixcity::segnalazione.steps.<step>.<tipo>`

## Dati JSON

Il blocco JSON `tests.segnalazione-crea.json` passa al widget:
- `privacy_intro`, `privacy_detail_prefix`, `privacy_link_label`, `privacy_checkbox_label`
- `placeholders.address/title/details`
- `issue_types` — opzioni select
- `current_step`, `total_steps` — per steppers Design Comuni nel widget view
- `contacts.faq/assistenza/phone/appointment`

## Nota per agenti
Se trovi riferimenti a `CreateSegnalazioneWizardWidget` o `SegnalazioneCreateWidget`, trattali come legacy o errore di naming e non reintrodurli nel flusso unificato.
