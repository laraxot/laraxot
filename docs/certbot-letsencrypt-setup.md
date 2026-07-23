# Certbot/Let's Encrypt Setup per sottana.com

## Alternativa: validazione DNS (consigliata se il webroot dà 404)

Non serve esporre `/.well-known/acme-challenge/` sul web. Let's Encrypt verifica il dominio tramite un record **TXT** nel DNS.

**Cosa aggiungere nel DNS** (presso il provider del dominio, es. Cloudflare, Aruba, ecc.):

| Tipo | Nome / Host | Valore | TTL |
|------|-------------|--------|-----|
| TXT  | `_acme-challenge`       | *(lo fornisce Certbot al passo 2)* | 300 o default |
| TXT  | `_acme-challenge.www`   | *(idem, per www)*                   | 300 o default |

**Procedura:**

1. Avviare Certbot in modalità manuale DNS:
   ```bash
   sudo certbot certonly --manual --preferred-challenges dns -d sottana.com -d www.sottana.com
   ```
2. Certbot mostrerà un valore tipo:
   ```
   Please deploy a DNS TXT record under the name _acme-challenge.sottana.com with the following value:
   xyz123...
   ```
3. Creare nel DNS il record **TXT**:
   - Nome: `_acme-challenge` (per sottana.com) → Certbot userà `_acme-challenge.sottana.com`
   - Valore: esattamente la stringa indicata da Certbot
4. Per **www**: Certbot chiederà un secondo valore; creare un altro TXT:
   - Nome: `_acme-challenge.www` (per www.sottana.com)
   - Valore: la seconda stringa indicata
5. Attendere la propagazione DNS (1–5 minuti, a volte fino a 10), poi premere Invio in Certbot per continuare.

**Vantaggi:** nessuna modifica a Nginx, funziona anche con redirect www, niente problemi IPv6/webroot.

**Record CAA (opzionale):** per consentire solo a Let's Encrypt di emettere certificati per il dominio:
- Tipo: CAA  
- Nome: `@` (o vuoto)  
- Valore: `0 issue "letsencrypt.org"`

---

## Problema (metodo webroot)

Certbot fallisce durante la verifica dei domini con errore:
```
Certbot failed to authenticate some domains (authenticator: webroot)
Detail: Invalid response from http://sottana.com/.well-known/acme-challenge/...: 404
```

## Causa

Let's Encrypt non riesce a scaricare i file di challenge temporanei perché:
1. La directory `.well-known/acme-challenge/` potrebbe non esistere o non avere i permessi corretti
2. **`www.sottana.com` non è configurato nel `server_name` di Nginx** → le richieste a www finiscono in un altro server block (404/301)
3. La configurazione Nginx potrebbe non servire correttamente la directory `.well-known`

## Fix rapido (404 su entrambi i domini)

**Causa tipica**: Ploi crea in `before/redirect.conf` un server block che risponde a **www.sottana.com** con **301** verso sottana.com. Let's Encrypt deve ricevere il file di challenge **sul dominio esatto** (www), non un redirect → 404. Inoltre la location `.well-known` deve servire i file con `try_files`.

1. **Servire ACME su www invece di fare redirect** – modificare `/etc/nginx/ploi/sottana.com/before/redirect.conf` così (la location `.well-known` deve essere prima del `return`):
   ```nginx
   # Redirect configuration file
   server {
       listen 80;
       listen [::]:80;
       server_name www.sottana.com;

       location /.well-known/acme-challenge/ {
           root /home/ploi/sottana.com/public_html;
           allow all;
           default_type "text/plain";
           try_files $uri =404;
       }

       location / {
           return 301 $scheme://sottana.com$request_uri;
       }
   }
   ```
   Comando per applicare (sostituisce il file):
   ```bash
   sudo tee /etc/nginx/ploi/sottana.com/before/redirect.conf << 'NGINX'
   # Redirect configuration file
   server {
       listen 80;
       listen [::]:80;
       server_name www.sottana.com;

       location /.well-known/acme-challenge/ {
           root /home/ploi/sottana.com/public_html;
           allow all;
           default_type "text/plain";
           try_files $uri =404;
       }

       location / {
           return 301 $scheme://sottana.com$request_uri;
       }
   }
   NGINX
   ```

2. **Far servire i file dal server principale (sottana.com)** – la location in `server/disable-basic-auth-well-known.conf` non ha `try_files`; va aggiunta una location con `root` e `try_files` **prima** di `location /` in `/etc/nginx/sites-available/sottana.com`. Se non c’è già, inserire:
   ```nginx
   location /.well-known/acme-challenge/ {
       root /home/ploi/sottana.com/public_html;
       allow all;
       default_type "text/plain";
       try_files $uri =404;
   }
   ```
   (Subito dopo `include /etc/nginx/ploi/sottana.com/server/*;` e prima di `location / {`.)

3. **Verificare e ricaricare Nginx:**
   ```bash
   sudo nginx -t && sudo systemctl reload nginx
   ```

4. **Test da fuori (anche IPv6):** creare un file e verificare che risponda 200 su entrambi i domini:
   ```bash
   echo -n ok > /home/ploi/sottana.com/public_html/.well-known/acme-challenge/test-ssl
   curl -sI http://sottana.com/.well-known/acme-challenge/test-ssl
   curl -sI -H "Host: www.sottana.com" http://127.0.0.1/.well-known/acme-challenge/test-ssl
   ```

5. **Rilanciare Certbot:**
   ```bash
   sudo certbot certonly --webroot -w /home/ploi/sottana.com/public_html -d sottana.com -d www.sottana.com
   ```

## Soluzione

### 1. Creare la directory `.well-known/acme-challenge/`

```bash
mkdir -p /home/ploi/sottana.com/public_html/.well-known/acme-challenge
chmod -R 755 /home/ploi/sottana.com/public_html/.well-known
```

### 2. Verificare la configurazione Nginx

La configurazione Nginx dovrebbe includere una location per `.well-known/acme-challenge/`. 
Questa è già presente in `/etc/nginx/ploi/sottana.com/server/disable-basic-auth-well-known.conf`:

```nginx
location /.well-known/acme-challenge/ {
    allow all;
    auth_basic off;
    default_type "text/plain";
}
```

### 3. Aggiungere www.sottana.com al server_name e location esplicita per .well-known

Modificare `/etc/nginx/sites-available/sottana.com`:

**a) Aggiungere www.sottana.com al server_name:**
```nginx
server_name sottana.com www.sottana.com;
```

**b) Aggiungere una location esplicita per `.well-known/acme-challenge/` PRIMA della location `/`:**

```nginx
# Let's Encrypt ACME challenge - DEVE essere prima della location /
location /.well-known/acme-challenge/ {
    root /home/ploi/sottana.com/public_html;
    allow all;
    default_type "text/plain";
    try_files $uri =404;
}

location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**Nota**: Richiede permessi root. Eseguire con sudo:

```bash
# Aggiungere www.sottana.com al server_name
sudo sed -i 's/server_name sottana.com;/server_name sottana.com www.sottana.com;/' /etc/nginx/sites-available/sottana.com

# Aggiungere location esplicita per .well-known (prima della location /)
sudo sed -i '/# Ploi Configuration, do not remove!/a\    # Let'\''s Encrypt ACME challenge - DEVE essere prima della location /\n    location /.well-known/acme-challenge/ {\n        root /home/ploi/sottana.com/public_html;\n        allow all;\n        default_type "text/plain";\n        try_files $uri =404;\n    }\n' /etc/nginx/sites-available/sottana.com
```

**IMPORTANTE**: La location per `.well-known/acme-challenge/` DEVE essere definita PRIMA della location `/` per evitare che Laravel intercetti le richieste.

### 4. Verificare e ricaricare Nginx

```bash
sudo nginx -t
sudo systemctl reload nginx
```

### 5. Testare manualmente la directory

```bash
# Creare un file di test
echo "test" > /home/ploi/sottana.com/public_html/.well-known/acme-challenge/test.txt

# Verificare che sia accessibile
curl http://sottana.com/.well-known/acme-challenge/test.txt
curl http://www.sottana.com/.well-known/acme-challenge/test.txt
```

Entrambi dovrebbero restituire "test".

### 6. Eseguire Certbot con il webroot corretto

```bash
sudo certbot certonly --webroot \
  -w /home/ploi/sottana.com/public_html \
  -d sottana.com \
  -d www.sottana.com
```

## Verifica Post-Installazione

Dopo aver ottenuto il certificato, verificare:

```bash
# Lista certificati installati
sudo certbot certificates

# Verificare che i file esistano
ls -la /etc/letsencrypt/live/sottana.com/
```

## Configurazione Nginx per SSL

Dopo aver ottenuto il certificato, aggiornare la configurazione Nginx per usare SSL.
La configurazione SSL è inclusa tramite:

```nginx
include /etc/nginx/ssl/sottana.com;
```

Questo file dovrebbe contenere le direttive SSL certificate.

## Rinnovo Automatico

Certbot configura automaticamente il rinnovo tramite systemd timer. Verificare:

```bash
sudo systemctl status certbot.timer
sudo certbot renew --dry-run
```

## Troubleshooting

### Errore 404 persistente

**Causa comune**: Laravel sta intercettando le richieste a `.well-known/acme-challenge/` perché la location `/` viene processata prima della location per `.well-known`.

**Soluzione**:
1. Verificare che la location per `.well-known/acme-challenge/` sia definita PRIMA della location `/` nella configurazione Nginx
2. Verificare che la location includa `try_files $uri =404;` per servire i file statici direttamente
3. Verificare che la directory esista:
   ```bash
   ls -la /home/ploi/sottana.com/public_html/.well-known/acme-challenge/
   ```

4. Verificare i permessi:
   ```bash
   chmod -R 755 /home/ploi/sottana.com/public_html/.well-known
   ```

5. Verificare che Nginx serva correttamente:
   ```bash
   curl -I http://sottana.com/.well-known/acme-challenge/test.txt
   ```
   
   Se ricevi ancora 404 con HTML di Laravel, significa che Laravel sta intercettando la richiesta. Verifica l'ordine delle location in Nginx.

6. Verificare che Certbot possa scrivere nella directory:
   ```bash
   sudo touch /home/ploi/sottana.com/public_html/.well-known/acme-challenge/test-certbot.txt
   sudo rm /home/ploi/sottana.com/public_html/.well-known/acme-challenge/test-certbot.txt
   ```

### www.sottana.com non risponde

Verificare che `www.sottana.com` sia nel `server_name`:
```bash
grep "server_name" /etc/nginx/sites-available/sottana.com
```

### Certbot non può scrivere nella directory

Verificare i permessi della directory webroot:
```bash
ls -ld /home/ploi/sottana.com/public_html
```

Certbot deve essere in grado di creare file nella directory `.well-known/acme-challenge/`.

## Note

- La directory `.well-known/acme-challenge/` deve essere accessibile pubblicamente via HTTP
- Non deve richiedere autenticazione
- I file devono essere serviti come `text/plain`
- Entrambi i domini (`sottana.com` e `www.sottana.com`) devono essere verificabili
- In alternativa: usare la **validazione DNS** (record TXT `_acme-challenge`), descritta all’inizio di questo documento.

## Collegamenti Correlati

- [SSL Configuration](./ssl-configuration.md)
- [Nginx Configuration](../laravel/Themes/Zero/.devcontainer/nginx.conf)
