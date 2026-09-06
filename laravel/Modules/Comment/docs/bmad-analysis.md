---
title: "Comment - Analisi BMAD Completa"
type: decision
tags: [bmad, comment, reviews, community]
created: 2026-09-05
updated: 2026-09-05
qmd: "bmad comment module analisi scopo religione filosofia politica zen future enhancements"
module: Comment
related:
  - ./README.md
  - ./index.md
  - ../Xot/docs/bmad-analysis-core-foundation.md
---

# Comment - Analisi BMAD Completa

## Scopo
Commenti e recensioni senza configurazione. Supporto moderazione, notifiche e integrazioni per permettere agli utenti di commentare contenuti e lasciare recensioni.

## Religione
- Ogni commento ha: autore, contenuto, modulo di appartenenza, oggetto (polimorfo), stato (approvato/pending/rimosso)
- La moderazione è a opt-in - i commenti sono disabilitati di default, vanno abilitati per modulo
- Le notifiche sono canali multipli (mail, sms, whatsapp) via modulo Notify
- I commenti sono polimorfici - appartengono a qualsiasi modello tramite morphs
- Le recensioni hanno rating (usano modulo Rating) e corpo testo

## Filosofia
- Ogni voce ha diritto di parola - ma con responsabilità
- La moderazione è servizio, non censura - approvare, non bloccare di per sé
- Le notifiche tengono tutti informati senza spam
- I commenti sono parte del contenuto, non "afterthought"

## Politica
- I commenti sono disabilitati di default in tutto il sistema
- Per abilitare i commenti su un modulo, deve essere configurato esplicitamente
- Ogni commento richiede approvazione se la moderazione è attiva (non automatico)
- Gli spam sono filtrati tramite Akismet-like service o blacklist parole-chiave
- Le risposte (thread) ai commenti sono supportate ma opzionali
- Le recensioni richiedono sempre un rating (1-5 stelle)

## Perché
Fornire un sistema di commenti e recensioni "pronto all'uso" che funzioni subito senza configurazione complessa, ma che sia abbastanza flessibile da adattarsi a qualsiasi modulo che ne abbia bisogno.

## Zen
Commenti e recensioni senza configurazione - la voce si alza, la moderazione aiuta, nessuna fatica d'impostazione.

## Best Practices
- **✅ Polymorphic ovunque**: ogni entità può avere commenti, non solo articoli
- **✅ Moderazione pre-pubblicazione**: mai pubblicare senza review se moderazione attiva
- **✅ Notifiche opt-in**: l'utente sceglie come ricevere notifiche
- **✅ Thread depth limit**: max 3 livelli di risposta, poi flat view
- **✅ Edit window**: permetti modifica entro 5 minuti, poi lock
- **✅ Profanity filter**: blacklist parole offensivo + whitelist contesti
- **✅ Rate limiting per utente**: max N commenti/ora, anti-spam
- **✅ Rich text support**: Markdown o BBCode, mai HTML libero
- **✅ IP logging per moderation**: tracciabilità per spam/abuse
- **✅ Edit history**: ogni modifica è loggata, visible a moderatori
- **✅ User reputation**: score basato su commenti approvati/rifiutati
- **✅ Merge duplicates**: suggestion di unire commenti identici

## Bad Practices
- **❌ HTML libero in commenti**: XSS vulnerability, mai
- **❌ Pubblicazione immediata**: sempre moderazione o almeno queue
- **❌ Notifiche spam**: una per commento, no digest senza consenso
- **❌ Thread infiniti**: degradano UX, cut-off a 3 livelli
- **❌ Edit illimitato**: lock dopo tempo, altrimenti abuse
- **❌ Ignorare spam**: Akismet o equivalente, sempre
- **❌ Commenti senza autore**: anonimo ammesso ma tracciato (IP)
- **❌ Nessun rate limit**: easy target per DoS
- **❌ Delete vs soft delete**: soft delete per audit trail
- **❌ Nessuna notifica a risposta**: l'utente non sa che qualcuno ha risposto
- **❌ Merge automatico duplicati**: suggerisci, non auto-merge
- **❌ Moderazione senza criteria**: avere linee guida scritte

## False Friends
- **❌ "Comment" = commento**: corretto, "osservazione" è più formale
- **❌ "Review" = revisione**: in contesto è "recensione", non "revisione documentale"
- **❌ "Thread" = discussione**: corretto, ma in italiano è "filo" o "thread"
- **❌ "Reply" = risposta**: corretto, "replica" è più polemico
- **❌ "Moderation" = moderazione**: corretto, sinonimo di "gestione contenuti"
- **❌ "Spam" = spam**: anglicismo universalmente accettato in italiano
- **❌ "Troll" = provocatore**: in forum italiano "troll" o "provocatore"
- **❌ "Flame" = flame war**: in italiano "botta e risposta" o "flame"
- **❌ "Ping" = notifica**: "ping" è tecnico, "notifica" è italiano
- **❌ "Upvote/Downvote" = vota su/giu**: in italiano "vota positivo/negativo"
- **❌ "Report" = segnala**: in italiano "segnala" o "reporta"
- **❌ "Embed" = incorpora**: corretto, "incorpora" in senso stretto

## Cosa manca
- Editor richiamo (richiesto plugin CKEditor/TinyMCE configurazione)
- Anti-spam più avanzato (CAPTCHA, honeypot)
- Reporting commenti abusivi da parte utente
- Moderazione a livello di gruppo/ruolo
- Integrazione con modulo User per gravatar/avatar personalizzati
- Email digest riepilogativo commenti
- Dashboard moderazione con statistiche
- Bulk moderation actions
- AI-powered spam detection
- Sentiment analysis sui commenti

## Cosa aggiungerei
- Thread annidati illimitati con "view more replies"
- Moderazione a massa (seleziona più commenti, approva/rifiuta)
- Notifiche "risposta al mio commento" configurabili per canale
- Integrazione con modulo Rating per recensioni prodotto-specifiche
- Antispam basato su machine learning (tramite modulo AI opzionale)
- User badges basati su reputation
- Comment reactions (emoji) oltre rating
- Rich text editor WYSIWYG integrato
- Media embedding (image, video, link preview)
- Comment bookmarking
- Moderation workflow con escalation

## Future Enhancements

### Fase 1: Foundation (Q4 2026)
- **AI Spam Detection**: machine learning per identificare spam automaticamente
- **Sentiment Analysis**: analytics su tono dei commenti
- **Rich Media Support**: embedding immagini, video, link preview
- **User Badges**: reputation system visivo

### Fase 2: Experience (Q1 2027)
- **Real-time Updates**: commenti nuovi senza refresh
- **Mention Support**: @username notifica automatica
- **Emoji Reactions**: reactions oltre rating numerico
- **Comment Bookmarking**: salva commenti per dopo

### Fase 3: Moderation (Q2 2027)
- **Workflow Automation**: escalation automatica basata su contenuto
- **Bulk Actions**: mass moderation con criteri
- **AI Moderation Assistant**: suggerimenti per moderatore
- **Transparency Dashboard**: statistiche moderazione pubbliche

### Fase 4: Integration (Q3 2027)
- **Social Login**: commento con Google/Facebook
- **Discourse Import**: migrazione da Discourse
- **API Public**: expose commenti per app esterne
- **Webhooks**: notifiche a sistemi esterni

### Fase 5: Future Community (Q4 2027+)
- **Community Guidelines AI**: enforcement automatico linee guida
- **Voice Comments**: commenti audio
- **Community Awards**: riconoscimenti per contributori
- **AI-Facilitated Discussion**: riassunti e pointing di discussioni

## Divisione o Unione
- **Mantieni separato**: Comment ha responsabilità community-specifiche
- **Potenziale unione**: potrebbe essere unito a Blog per "Blog with comments completo", ma Comment è modulo indipendente che può essere attaccato a qualsiasi risorsa
- **Conflitto**: con Rating (recensioni), con User (autori), con Notify (notifiche)

## Note
I commenti sono il cuore pulsante della community - devono essere facili da dare ma controllati per evitare abuse. L'equilibrio tra libertà e sicurezza è chiave.