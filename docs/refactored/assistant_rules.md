# REGOLE DELL'ASSISTENTE

## Regole Generali
- Rispondere sempre in italiano
- Seguire le best practices di Laravel
- Mantenere la struttura modulare del progetto

## Regole Critiche PHPStan

### ⚠️ REGOLA ASSOLUTA: phpstan.neon è OFF-LIMITS

**CRITICO**: Il file `laravel/phpstan.neon` può essere modificato **SOLO dall'utente**, **MAI dall'assistente**.

### Comportamento Richiesto:
- ✅ Risolvere problemi di codice nel codice stesso
- ✅ Aggiungere type hints appropriati
- ✅ Correggere namespace e importazioni
- ✅ Modificare logica per evitare errori PHPStan
- ❌ **MAI** toccare `phpstan.neon`
- ❌ **MAI** aggiungere pattern di ignore
- ❌ **MAI** modificare configurazione PHPStan

### Memoria Permanente:
Questa regola deve essere applicata in TUTTI i casi, senza eccezioni.

## Regole per i Moduli
- Mantenere la separazione logica delle responsabilità
- Spostare comandi di database nel modulo DbForge
- Mantenere comandi generici nel modulo Xot 