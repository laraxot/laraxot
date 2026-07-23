# Block View Bridge For Missing Pages

## Contesto

Nel contenuto `tests.*` esistevano molte dichiarazioni `data.view = pub_theme::components.blocks.*` senza il corrispondente file Blade nel tema Sixteen.

Questo produceva due classi di errore:
- `Unable to locate a class or view for component [...]`
- pagine JSON presenti ma non renderizzabili, quindi percepite come "mancanti"

## Decisione

Invece di introdurre nuovi namespace pagina-specifici o duplicare markup per ogni slug, il tema espone ora un set di view bridge minime e riusabili.

Queste view hanno due ruoli:
- aliasare nomi legacy o incompleti verso blocchi già presenti
- offrire una baseline renderizzabile per famiglie ancora in consolidamento

## Blocchi bridge aggiunti

- `breadcrumb/default`
- `header/page`
- `info/default`
- `contact/info`
- `feedback/rating`
- `hero/argomenti`
- `hero/homepage`
- `news/{featured,header,content,related,tags}`
- `event/{header,details,info,related,calendar}`
- `service/{header,details,contact}`
- `services/related`
- `steps/horizontal`
- `topics/featured`
- `administration/{sections,documents,transparency}`
- `tests/{intro,body,governance-note,source-link}`

## Regola architetturale

Questi bridge sono un adattatore forward-only.

Non sono la destinazione finale del design system. Servono a garantire che ogni `pub_theme::components.blocks.*` dichiarata nei JSON del CMS punti a una view reale, mentre si prosegue la convergenza verso blocchi più semantici e più vicini al reference Design Comuni.

## Verifica

Controllo eseguito il 30 marzo 2026:
- tutte le view `pub_theme::components.blocks.*` estratte dai JSON `config/local/fixcity/database/content/pages` hanno ora un file Blade nel tema Sixteen
- il bootstrap del provider non usa più `dddx()` per fallire su path opzionali dei componenti
