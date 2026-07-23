# REGOLE PHPSTAN - DOCUMENTAZIONE

## ⚠️ REGOLA ASSOLUTA: phpstan.neon è OFF-LIMITS

### Regola Critica
Il file `laravel/phpstan.neon` può essere modificato **SOLO dall'utente**, **MAI dall'assistente**.

### Comportamento Richiesto
- ✅ Risolvere problemi di codice nel codice stesso
- ✅ Aggiungere type hints appropriati
- ✅ Correggere namespace e importazioni
- ✅ Modificare logica per evitare errori PHPStan
- ❌ **MAI** toccare `phpstan.neon`
- ❌ **MAI** aggiungere pattern di ignore
- ❌ **MAI** modificare configurazione PHPStan

### Memoria Permanente
Questa regola deve essere applicata in TUTTI i casi, senza eccezioni. 