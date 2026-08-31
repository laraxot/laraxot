# Accesso Giulia al pannello GiveBack

## Problema

Giulia ha il permesso relativo al CRM / GiveBack, ma non riesce ad accedere al pannello.

Nel progetto il pannello GiveBack non usa un permesso CRM generico. L accesso dipende da:

- ruolo utente GiveBack;
- abilitazione pannello `giveback-platform`;
- eventuale approvazione utente;
- login dal pannello corretto;
- 2FA email se abilitata.

## Ruoli validi

I ruoli previsti sono:

- `giveback_admin`
- `giveback_merchant`
- `giveback_support`

## Comando utile

Per creare o riallineare l utente:

```bash
php artisan auth:create-giveback-user "email@example.com" "Nome Cognome" --password="password-sicura" --role=giveback_admin
```

## Causa probabile trovata nel codice

Il controllo accesso pannello passa da `User::canAccessPanel()`, che richiede un utente approvato.

Per questo il comando `auth:create-giveback-user` e stato allineato ad altri comandi pannello impostando `approved_at` quando manca.

## Checklist rapida

1. confermare l email usata da Giulia;
2. verificare il ruolo assegnato;
3. confermare accesso da `/giveback-platform/login`;
4. verificare eventuale challenge 2FA email;
5. se necessario, rilanciare il comando utente GiveBack.

## Nota

Se Giulia continua a non entrare anche con ruolo corretto e utente approvato, il passo successivo e verificare la challenge 2FA o la sessione di login.
