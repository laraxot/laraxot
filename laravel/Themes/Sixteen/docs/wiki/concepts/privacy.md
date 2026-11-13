---
title: Privacy Policy Concept
slug: privacy
qmd: "concept privacy policy gdpr compliant data protection"
document_type: concept
category: documentation
status: stable
source_of_truth: true
scope: [laravel/Themes/Sixteen/docs/wiki/concepts]
related:
  - ../rules/no-domain-css-classes.md
  - ../entities/design-comuni-class-mapping.md
type: note
updated: 2026-10-15
tags: [docs, sixteen, wiki]
---

# Privacy Policy Concept

## Scope
Defines the structure and content requirements for the privacy policy page that complies with GDPR and Italian privacy regulations (Garante Privacy).

## Core Principles
- **Trasparenza**: Chiaro e semplice linguaggio per spiegare quali dati vengono raccolti e perché
- **Limitazione della finalità**: I dati vengono raccolti solo per scopi specifici, espliciti e legittimi
- **Minimizzazione dei dati**: Vengono raccolti solo i dati strettamente necessari
- **Precisione**: I dati devono essere accurati e tenuti aggiornati
- **Limitazione della conservazione**: I dati vengono conservati solo per il tempo necessario
- **Integrità e riservatezza**: Misure di sicurezza adeguate per proteggere i dati
- **Responsabilità**: Il titolare del trattamento deve poter dimostrare il rispetto dei principi

## Required Sections (GDPR Art. 13-14)
1. **Identità e dati di contatto del titolare** (Nome PA, indirizzo, email, telefono, PEC)
2. **Dati di contatto del Responsabile della Protezione dei Dati (DPO)** se nominato
3. **Finalità del trattamento** per cui sono destinati i dati personali nonché la base giuridica del trattamento
4. **Legittimi interessi perseguiti dal titolare o da terzi** qualora si faccia riferimento all'articolo 6, paragrafo 1, lettera f)
5. **Destinatari o le categorie di destinatari** dei dati personali, se presenti
6. **Trasferimento di dati personali verso un paese terzo o un'organizzazione internazionale** e le garanzie
7. **Periodo di conservazione** dei dati personali oppure, se non è possibile, i criteri utilizzati per determinare tale periodo
8. **Esistenza del diritto di chiedere al titolare** l'accesso ai dati personali e la rettifica o la cancellazione degli stessi o la limitazione del trattamento che li riguardano o di opporsi al loro trattamento, nonché il diritto alla portabilità dei dati
9. **Diritto di proporre reclamo a un'autorità di controllo**
10. **Obbligatorietà o meno del conferimento dei dati personali** e le possibili conseguenze del mancato conferimento
11. **Esistenza di un processo decisionale automatizzato**, compresa la profilazione, e informazioni significative sulla logica utilizzata, nonché l'importanza e le conseguenze previste di tale trattamento per l'interessato

## Technical Implementation
- Page: `resources/views/pages/privacy.blade.php`
- Route: `Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');`
- Layout: Estende `themes::Sixteen.layouts.guest`
- Styling: Utilizza token CSS dal sistema design (`resources/css/app.css`)
- Multilingua: Supporta it/en/de/es/fr/ru tramite file di lingua
- Accessibilità: WCAG 2.1 AA conforme

## Data Collected (Esempio)
In base all'analisi dei cookie tecnici:
- **Cookie tecnici**: XSRF-TOKEN, fixcity_session (necessari per il funzionamento)
- **Cookie di analisi**: Solo se esplicitamente accettati dall'utente (Google Analytics con IP anonimizzato)
- **Cookie di marketing**: Mai attivati di default, solo con consenso esplicito

## Legal Basis
- **Interesse pubblico** (art. 6(1)(e) GDPR): Per attività istituzionali della PA
- **Esecuzione di un contratto** (art. 6(1)(b) GDPR): Per servizi richiesti dall'utente
- **Consenso** (art. 6(1)(a) GDPR): Per cookie non tecnici e comunicazioni di marketing
- **Obbligo legale** (art. 6(1)(c) GDPR): Per adempimento di obblighi di legge

## Rights of Data Subjects
Gli utenti hanno il diritto di:
1. Accesso ai propri dati personali
2. Rettifica di dati inesatti
3. Cancellazione ("diritto all'oblio") nei casi previsti
4. Limitazione del trattamento
5. Portabilità dei dati
6. Opposizione al trattamento
7. Non essere sottoposto a decisione basata unicamente sul trattamento automatizzato
8. Revocare il consenso in qualsiasi momento

## Contact Information
Il titolare deve fornire:
- Nome e indirizzo completo della pubblica amministrazione
- Indirizzo email dedicato alla privacy (es. privacy@comune.it)
- Numero di telefono
- PEC (Posta Elettronica Certificata)
- Indirizzo del Responsabile della Protezione dei Dati (se nominato)

## Related Concepts
- `cookiebar`: Banner che raccoglie il consenso prima del trattamento
- `gdpr-consent`: Sistema di gestione dei consensi
- `registration`: Form che deve includere i collegamenti alla privacy policy
- `unsubscribe`: Meccanismo per revocare il consenso al marketing

## Open Objectives
- [ ] Verificare che tutti i link interni alla privacy policy funzionino
- [ ] Aggiornare la policy con eventuali nuovi trattamenti dati
- [ ] Tradurre in tutte le lingue supportate (it/en/de/es/fr/ru)
- [ ] Verificare conformità con le ultime linee guida Garante Privacy

## References
- GDPR Regolamento UE 2016/679
- D.Lgs. 101/2018 (disposizioni per l'adeguamento al GDPR)
- Linee guida Garante Privacy sul trattamento dei dati personali nei siti web e app mobili di pubbliche amministrazioni (doc. web n. 9347754)
- Indicazioni AGID sui siti web delle PA