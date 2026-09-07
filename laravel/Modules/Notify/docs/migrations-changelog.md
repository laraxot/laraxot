# Changelog Migrazioni Notify Module

<<<<<<< HEAD
<<<<<<< HEAD
## 2024-03-20: Aggiunta Campo Slug a Mail Templates
=======
## [DATE]: Aggiunta Campo Slug a Mail Templates
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
## [DATE]: Aggiunta Campo Slug a Mail Templates
>>>>>>> a988596b (first)

### Modifiche
- Aggiunto campo `slug` alla tabella `mail_templates`
- Implementato nella sezione `tableUpdate` della migrazione originale
- Aggiunto controllo di esistenza colonna

### Motivazioni
1. **Miglioramento Identificazione Template**
   - Riferimento stabile e prevedibile ai template
   - Indipendenza dalla classe Mailable
   - Facilità di migrazione

2. **Struttura Standardizzata**
   - Seguito pattern `XotBaseMigration`
   - Implementato nella sezione `tableUpdate` esistente
   - Mantenuta retrocompatibilità

3. **Best Practices**
   - Verifica esistenza colonna prima dell'aggiunta
   - Utilizzo metodi helper di `XotBaseMigration`
   - Documentazione completa delle modifiche

### Impatto
- Miglioramento gestione template
- Nessun impatto su dati esistenti
- Mantenuta compatibilità con codice esistente

### Collegamenti Correlati
<<<<<<< HEAD
<<<<<<< HEAD
- [Proposta Slug](./SPATIE_EMAIL_SLUG_PROPOSAL.md)
- [Sistema Template Email](./EMAIL_TEMPLATES.md)
- [Email Dottori](./DOCTOR_EMAILS.md) 
=======
- [Proposta Slug](./spatie-email-slug-proposal.md)
- [Sistema Template Email](./email_templates.md)
- [Email Dottori](./doctor-emails.md) 
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- [Proposta Slug](./spatie_email_slug_proposal.md)
- [Sistema Template Email](./email_templates.md)
- [Email Dottori](./doctor_emails.md) 
>>>>>>> a988596b (first)
