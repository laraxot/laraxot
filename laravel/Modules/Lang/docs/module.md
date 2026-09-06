---
title: "Lang Module — Doctrine"
type: doctrine
tags: [lang, i18n, localization, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Lang module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Platform/docs/module.md"
---

# Lang Module — Doctrine

## Scope (Scopo)

Lang gestisce la localizzazione e internazionalizzazione (i18n) del monorepo. Fornisce il sistema di traduzione centralizzato, lo switching linguistico, la configurazione locale, e i file di traduzione per tutte le lingue supportate. Ogni modulo usa Lang per le proprie traduzioni, mai file di traduzione dispersi.

## Religion (Religione)

**"Semplicità vince sulla complessità. Modulare è dare vita."** La convinzione non negoziabile è che ogni label, ogni messaggio, ogni testo visibile all'utente debba essere tradotto tramite Lang, mai hardcoded in italiano o inglese. Lang è la fonte di verità per tutto il testo del sistema.

## Philosophy (Filosofia)

- **XotBase per coerenza**: ogni modello Lang estende XotBase per uniformità
- **Filament v5**: integrazione nativa con Filament 5 per pannello admin tradotto
- **Laravel Queues**: traduzioni pesanti (es. generazione PDF multilingua) in queue
- **PSR-12 + PHPStan L10**: codice e tipi rigorosi

## Policy (Politica)

- Ogni modulo DEVE avere file di traduzione in `lang/it/` e `lang/en/`
- Nessun label hardcoded: ogni testo visibile usa `__('module::key')`
- Le chiavi di traduzione sono nommate con pattern `module.action.operation`
- Aggiunta di nuove lingue passa per Lang: nessun modulo aggiunge lingue proprie

## Why (Perché)

Lang esiste perché senza un sistema di traduzione centralizzato, ogni modulo avrebbe i propri file di traduzione con chiavi non standardizzate, rendendo impossibile la manutenzione e l'aggiunta di nuove lingue. Lang garantisce che il sistema sia multilingua by design.

## Zen

*"Il testo è dato, non codice. Traduci una volta, usa ovunque."*

## Gap

- ARCHITECTURE.md mancante nella root del modulo
- Alcuni adapter di traduzione non completamente testati
- Mancano report di completezza traduzioni per modulo
- Nessun tool per trovare stringhe hardcoded nel codice

## Add

- Tool di analisi per trovare stringhe hardcoded nel codice PHP/Blade
- Report di completezza traduzioni per ogni modulo (percentuale)
- Più backend di traduzione (API esterne, traduzione automatica con revisione)
- Action per validazione traduzioni (chiavi mancanti, duplicati, orfane)
- Dashboard per gestione traduzioni con anteprima live

## Split/Merge

**Mantenere come-is, ma estrarre Filesystem/Translators come sotto-domini se crescono.** Lang è un sistema di supporto che potrebbe essere frammentato, ma la centralizzazione è il suo valore principale — frammentarlo significherebbe perdere la garanzia di coerenza.

## Future Enhancements

1. **Translation memory**: sistema di memoizzazione per traduzioni riutilizzate tra moduli
2. **AI-assisted translation**: integrazione LLM per traduzione automatica con revisione umana
3. **Translation diff**: confronto tra lingue per identificare chiavi obsolete o mancanti
4. **Crowdsourced translation**: UI per contributi traduzione da parte degli utenti
5. **Pluralization rules**: supporto completo per lingue con regole di plurale complesse
6. **RTL support**: supporto completo per lingue right-to-left (arabo, ebraico)
