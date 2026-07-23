# Regole per un Sito Web Efficace (InputComm)

Riferimento: [InputComm - Le 11 Regole per un Sito Web Efficace](https://www.inputcomm.it/regole-sito-web-efficace/)

Questo documento mappa le 11 regole InputComm al progetto Predict/TwentyOne e definisce checklist operative.

## 1. Obiettivi chiari

- **Obiettivo primario**: Piattaforma di mercati di previsione category-leading
- **Obiettivi secondari**: Registrazione, trading, fiducia, SEO
- **Checklist**: Ogni pagina ha uno scopo definito; CTA allineate agli obiettivi

## 2. Hosting di qualità

- Responsabilità infrastruttura (non tema)
- Verificare: SSL, uptime, CDN per asset statici

## 3. Veloce, semplice, intuitivo

- **Velocità**: Lazy loading, asset ottimizzati, cache
- **Semplicità**: Navigazione chiara, filtri collassabili (nascosti di default)
- **Intuitività**: UX coerente, ordinamento esplicito (Hot, Nuovi, Volume, ecc.)
- **Checklist**: Filtri nascosti di default; ordinamento visibile; ricerca debounced

## 4. Responsive

- Layout mobile-first; breakpoint Tailwind
- Test su viewport multipli

## 5. SEO

- Meta tag, schema.org, URL semantici
- Titoli reali (no "Mercato #N"); predict senza titolo = draft = non mostrare

## 6. Contenuti di qualità

- Titoli reali; no fallback generici
- Draft (senza titolo) non mostrati al pubblico

## 7. CTA chiare e visibili

- "Inizia Gratis", "Trade Now", "Registrati"
- Posizionate in punti strategici (hero, card, footer)

## 8. Grafica curata

- Design pulito, colori equilibrati
- Less is more: no effetti eccessivi

## 9. Branding e coerenza visiva

- Palette coerente (indigo, emerald, slate)
- Font e icone uniformi

## 10. Sito aggiornato

- Manutenzione continua; contenuti freschi

## 11. Sicurezza e privacy

- SSL, GDPR, policy privacy

## Applicazione al progetto

| Regola | Implementazione TwentyOne |
|--------|---------------------------|
| 3 | Filtri collassabili (`filtersOpen: false`), ordinamento Hot/Nuovi/Volume |
| 5 | Scope `hasTitle()` su Predict; no "Mercato #N" |
| 6 | Featured market con `hasTitle()`; draft esclusi |
| 7 | CTA hero, featured card, footer |

## Collegamenti

- [blade-generic-architecture](blade-generic-architecture.md)
- [filters-collapsible-philosophy](filters-collapsible-philosophy.md)
- [route-names-philosophy](route-names-philosophy.md)
