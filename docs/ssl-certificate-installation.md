# SSL Certificate Installation for sottana.net

## Overview

Questo documento descrive il processo di installazione e configurazione dei certificati SSL per i domini `sottana.net`, `www.sottana.net`, `sottana.com` e `www.sottana.com` utilizzando Let's Encrypt e Certbot.

## Stato Attuale

- ✅ **Certificato SSL installato per**: `sottana.net`, `www.sottana.net`
- ⚠️ **Certificato SSL in attesa per**: `sottana.com`, `www.sottana.com` (problema proxy/CDN)

## Configurazione Server

- **Web Server**: Nginx
- **Document Root**: `/home/ploi/sottana.net/public_html`
- **Certificati Let's Encrypt**: `/etc/letsencrypt/live/sottana.net/`
- **Configurazione SSL Nginx**: `/etc/nginx/ssl/sottana.net`
- **Configurazione Site**: `/etc/nginx/sites-enabled/sottana.net`

## Processo di Installazione

### 1. Preparazione Nginx

Prima di richiedere il certificato, è necessario configurare Nginx per:
- Accettare richieste HTTP sulla porta 80 per la validazione Let's Encrypt
- Servire correttamente i file di challenge da `/.well-known/acme-challenge/`

**File di configurazione chiave**:
- `/etc/nginx/sites-enabled/sottana.net`: Server block principale
- `/etc/nginx/ploi/sottana.net/server/disable-basic-auth-well-known.conf`: Gestione challenge path
- `/etc/nginx/ploi/sottana.net/before/ssl-redirect.conf`: Redirect HTTP → HTTPS

### 2. Richiesta Certificato Let's Encrypt

```bash
# Richiesta certificato per sottana.net e www.sottana.net
sudo certbot certonly --webroot \
  -w /home/ploi/sottana.net/public_html \
  -d sottana.net \
  -d www.sottana.net \
  --non-interactive \
  --agree-tos \
  --email admin@sottana.net
```

### 3. Configurazione SSL in Nginx

Dopo l'installazione del certificato, creare il file di configurazione SSL:

```bash
sudo tee /etc/nginx/ssl/sottana.net << EOF
ssl_certificate /etc/letsencrypt/live/sottana.net/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/sottana.net/privkey.pem;
EOF
```

### 4. Configurazione Server Block HTTPS

Il server block principale (`/etc/nginx/sites-enabled/sottana.net`) deve:
- Ascoltare sulla porta 443 con SSL
- Includere la configurazione SSL
- Configurare i protocolli e cipher SSL appropriati

**Esempio configurazione**:
```nginx
server {
    listen 443 ssl;
    listen [::]:443 ssl;
    http2 on;

    root /home/ploi/sottana.net/public_html;
    server_name sottana.net sottana.com;

    include /etc/nginx/ssl/sottana.net;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ecdh_curve X25519:prime256v1:secp384r1;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:DHE-RSA-CHACHA20-POLY1305;
    ssl_prefer_server_ciphers off;
    ssl_dhparam /etc/nginx/dhparams.pem;
    
    # ... resto della configurazione
}
```

### 5. Redirect HTTP → HTTPS

Il file `/etc/nginx/ploi/sottana.net/before/ssl-redirect.conf` gestisce:
- Redirect di tutte le richieste HTTP a HTTPS
- Eccezione per `/.well-known/acme-challenge/` per permettere la validazione Let's Encrypt

**Esempio configurazione**:
```nginx
server {
     listen 80;
     listen [::]:80;
     server_name sottana.net sottana.com www.sottana.net www.sottana.com;
     
     # Allow Let's Encrypt validation
     location /.well-known/acme-challenge/ {
         root /home/ploi/sottana.net/public_html;
         try_files $uri =404;
     }
     
     # Redirect all other requests to HTTPS
     location / {
         return 301 https://$host$request_uri;
     }
}
```

### 6. Redirect www → dominio principale

```nginx
server {
     listen 443 ssl;
     listen [::]:443 ssl;
     http2 on;
     ssl_certificate /etc/letsencrypt/live/sottana.net/fullchain.pem;
     ssl_certificate_key /etc/letsencrypt/live/sottana.net/privkey.pem;

     server_name www.sottana.net www.sottana.com;
     return 301 https://sottana.net$request_uri;
}
```

## Problema Critico: Proxy/CDN Intercetta Richieste Let's Encrypt

### Situazione Attuale

`sottana.com` e `www.sottana.com` passano attraverso un proxy/CDN esterno (probabilmente Cloudflare o simile) che:
- Intercetta tutte le richieste HTTP
- Fa redirect automatico da HTTP a HTTPS
- Restituisce header "Server: LiteSpeed" invece di "Server: nginx"
- **Impedisce la validazione HTTP-01 di Let's Encrypt** perché il proxy fa redirect anche per `/.well-known/acme-challenge/`

### Verifica del Problema

```bash
# Richiesta normale (attraverso proxy) - FAIL
curl -v http://sottana.com/.well-known/acme-challenge/test
# Risposta: HTTP/1.1 301 Moved Permanently → https://sottana.com/...

# Richiesta bypassando proxy (diretta a IP) - SUCCESS
curl -v --resolve sottana.com:80:72.61.154.57 http://sottana.com/.well-known/acme-challenge/test
# Risposta: HTTP/1.1 200 OK (Nginx risponde correttamente)
```

### Soluzioni Possibili

#### Soluzione 1: Configurare Proxy per Bypassare Path di Validazione (CONSIGLIATA)

Configurare il proxy/CDN per **non fare redirect** per il path `/.well-known/acme-challenge/`:

**Per Cloudflare:**
1. Accedere al pannello Cloudflare
2. Andare su "Page Rules"
3. Creare una regola: `http://sottana.com/.well-known/acme-challenge/*`
4. Impostare: "SSL: Off" o "Cache Level: Bypass"
5. Salvare e attendere la propagazione (1-5 minuti)

**Per altri proxy/CDN:**
- Cercare opzioni simili per bypassare SSL/redirect per path specifici
- Configurare regole per permettere HTTP per `/.well-known/acme-challenge/`

#### Soluzione 2: Usare Metodo DNS-01 (Alternativa)

Richiede:
- Accesso DNS per creare record TXT
- Plugin DNS per Certbot (es. `certbot-dns-cloudflare`)

```bash
# Installare plugin DNS (esempio per Cloudflare)
sudo apt install python3-certbot-dns-cloudflare

# Configurare credenziali DNS
sudo mkdir -p /etc/letsencrypt
sudo nano /etc/letsencrypt/cloudflare.ini
# dns_cloudflare_api_token = YOUR_API_TOKEN

# Richiedere certificato con DNS-01
sudo certbot certonly --dns-cloudflare \
  --dns-cloudflare-credentials /etc/letsencrypt/cloudflare.ini \
  -d sottana.com -d www.sottana.com
```

#### Soluzione 3: Disabilitare Temporaneamente Proxy

Durante la validazione Let's Encrypt:
1. Disabilitare il proxy/CDN per `sottana.com` e `www.sottana.com`
2. Eseguire la richiesta certificato
3. Riabilitare il proxy dopo la validazione

**Nota**: Il certificato continuerà a funzionare anche con il proxy attivo dopo la validazione.

### Stato Attuale

- ✅ **sottana.net**: Certificato SSL installato e funzionante
- ✅ **www.sottana.net**: Certificato SSL installato e funzionante  
- ⚠️ **sottana.com**: In attesa configurazione proxy
- ⚠️ **www.sottana.com**: In attesa configurazione proxy

### Prossimi Passi

1. Configurare il proxy/CDN per bypassare `/.well-known/acme-challenge/`
2. Attendere propagazione configurazione (1-5 minuti)
3. Richiedere certificato con:
   ```bash
   sudo certbot certonly --webroot \
     -w /home/ploi/sottana.net/public_html \
     -d sottana.net -d www.sottana.net -d sottana.com -d www.sottana.com \
     --non-interactive --agree-tos --email admin@sottana.net --expand
   ```

## Problemi Riscontrati e Soluzioni

### Problema 1: Certificati SSL Mancanti

**Errore**: `nginx: [emerg] cannot load certificate "/etc/letsencrypt/live/sottana.net/fullchain.pem": BIO_new_file() failed`

**Soluzione**: Commentare temporaneamente tutte le direttive SSL in Nginx fino a quando il certificato non viene installato.

### Problema 2: Conflitti Server Name

**Errore**: `nginx: [warn] conflicting server name "sottana.net" on 0.0.0.0:80`

**Soluzione**: Assicurarsi che solo un server block gestisca ogni combinazione di porta/server_name. Commentare i server block duplicati.

### Problema 3: Duplicazione Location Block

**Errore**: `nginx: [emerg] duplicate location "/.well-known/acme-challenge/"`

**Soluzione**: Rimuovere i blocchi `location /.well-known/acme-challenge/` duplicati, lasciando solo quello in `disable-basic-auth-well-known.conf`.

### Problema 4: Proxy/CDN Intercetta Richieste

**Errore**: `Invalid response from http://sottana.com/.well-known/acme-challenge/...: 404`

**Causa**: `sottana.com` e `www.sottana.com` passano attraverso un proxy/CDN (probabilmente Cloudflare) che restituisce "Server: LiteSpeed" invece di permettere a Nginx di servire i file di challenge.

**Soluzione**: 
- Verificare la configurazione del proxy/CDN per permettere il pass-through delle richieste `/.well-known/acme-challenge/`
- Considerare l'uso del metodo DNS-01 invece di HTTP-01 per la validazione (richiede accesso DNS)
- Disabilitare temporaneamente il proxy durante la validazione

### Problema 5: Rate Limit Let's Encrypt

**Errore**: `too many failed authorizations (5) for "www.sottana.com" in the last 1h0m0s`

**Soluzione**: Attendere che il rate limit scada prima di riprovare. Il rate limit si resetta dopo 1 ora.

## Verifica e Test

### Verifica Configurazione Nginx

```bash
sudo nginx -t
```

### Verifica Certificato SSL

```bash
sudo certbot certificates
```

### Test Connessione HTTPS

```bash
# Test sottana.net
curl -I https://sottana.net

# Test www.sottana.net (dovrebbe redirectare a sottana.net)
curl -I https://www.sottana.net
```

### Verifica Validità Certificato

```bash
sudo openssl x509 -in /etc/letsencrypt/live/sottana.net/fullchain.pem -text -noout | grep -E "Subject:|Issuer:|Not Before|Not After"
```

## Rinnovo Automatico

Certbot configura automaticamente un task schedulato per il rinnovo dei certificati. Verificare con:

```bash
sudo systemctl status certbot.timer
```

Il certificato viene rinnovato automaticamente quando mancano meno di 30 giorni alla scadenza.

## Aggiungere Domini al Certificato Esistente

Per aggiungere domini a un certificato esistente:

```bash
sudo certbot certonly --webroot \
  -w /home/ploi/sottana.net/public_html \
  -d sottana.net \
  -d www.sottana.net \
  -d sottana.com \
  -d www.sottana.com \
  --non-interactive \
  --agree-tos \
  --email admin@sottana.net \
  --expand
```

**Nota**: Tutti i domini devono essere validabili tramite HTTP-01. Se alcuni domini passano attraverso un proxy/CDN, potrebbe essere necessario usare DNS-01.

## Collegamenti Correlati

- [Configurazione SSL Nginx](./ssl-configuration.md)
- [Documentazione Nginx](https://nginx.org/en/docs/)
- [Documentazione Certbot](https://certbot.eff.org/docs/)
- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)

## Note Importanti

1. **Non rompere certificati esistenti**: Quando si aggiungono nuovi domini, assicurarsi di non compromettere i certificati SSL già funzionanti per altri domini.

2. **Alias di dominio**: `sottana.com` e `www.sottana.com` sono alias di `sottana.net`. La configurazione deve gestire correttamente tutti i domini senza conflitti.

3. **Document Root**: Tutti i domini condividono lo stesso document root (`/home/ploi/sottana.net/public_html`).

4. **Ploi**: Questo server è gestito tramite Ploi, che può avere configurazioni aggiuntive che influenzano il comportamento SSL.

5. **Proxy/CDN**: Se `sottana.com` passa attraverso un proxy/CDN, potrebbe essere necessario configurare il proxy per permettere la validazione Let's Encrypt o usare il metodo DNS-01.
