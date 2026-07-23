---
title: "Translation Keys Rules"
type: rule
tags: [translation, keys, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "translation-keys-rules translation keys rules"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

---

## [2024-07-07] Aggiornamento regole e best practice traduzioni modulo Notify

### Errori riscontrati
- Chiavi di traduzione non strutturate gerarchicamente
- Valori come 'send sms.navigation' o simili non conformi
- Mancanza di coerenza tra i file di traduzione dei vari canali (SMS, WhatsApp, Email, ecc.)
- Assenza di sezioni 'fields' e 'actions' in alcuni file

### Correzioni applicate
- Tutte le chiavi ora sono strutturate ad array annidati
- I valori sono descrittivi e localizzati, mai chiavi in italiano
- Aggiunte sezioni 'fields' e 'actions' dove mancanti
- Aggiornata la documentazione e le regole interne

### Best practice
- Prima di ogni modifica, consultare questa documentazione e quella centrale in `../../Lang/docs`
- Usare sempre nomi chiave descrittivi e struttura gerarchica
- Aggiornare contestualmente la documentazione in caso di nuove regole

### Esempio pratico

```php
return [
    'navigation' => [
        'label' => 'Invio WhatsApp',
        'group' => 'Notifiche',
    ],
    'fields' => [
        'to' => [
            'label' => 'Destinatario',
            'placeholder' => 'Inserisci il numero',
        ],
        'message' => [
            'label' => 'Messaggio',
            'placeholder' => 'Scrivi il messaggio',
        ],
    ],
    'actions' => [
        'send' => [
            'label' => 'Invia',
        ],
    ],
];
```

### Riferimenti
- [TRANSLATION_KEYS_RULES.md](../../Lang/docs/TRANSLATION_KEYS_RULES.md)
- [TRANSLATION_KEYS_BEST_PRACTICES.md](../../Lang/docs/TRANSLATION_KEYS_BEST_PRACTICES.md) 