# SSL Configuration for Laravel Application

## Configurazione SSL per sottana.net

Quando l'applicazione viene eseguita con SSL/HTTPS, è necessario aggiornare il file `.env` per riflettere l'URL sicuro:

```env
APP_URL=https://sottana.net
```

Questo garantisce:
- Gli URL degli asset vengono generati con HTTPS
- I redirect mantengono il protocollo HTTPS
- I cookie di sessione vengono gestiti correttamente con il flag secure quando appropriato

## Certificati SSL

I certificati SSL sono gestiti tramite Let's Encrypt e Certbot. Per dettagli completi sull'installazione, vedere [SSL Certificate Installation](./ssl-certificate-installation.md).

### Domini Configurati

- ✅ `sottana.net` - Certificato SSL attivo
- ✅ `www.sottana.net` - Certificato SSL attivo (redirect a sottana.net)
- ⚠️ `sottana.com` - In attesa (problema proxy/CDN)
- ⚠️ `www.sottana.com` - In attesa (problema proxy/CDN)

### Configurazione Nginx

I certificati SSL sono configurati in Nginx con:
- Certificati Let's Encrypt: `/etc/letsencrypt/live/sottana.net/`
- Configurazione SSL: `/etc/nginx/ssl/sottana.net`
- Server block principale: `/etc/nginx/sites-enabled/sottana.net`

### Verifica Configurazione

Dopo la configurazione SSL, verificare che tutto funzioni:

```bash
# Verifica configurazione Nginx
sudo nginx -t

# Verifica certificati installati
sudo certbot certificates

# Test connessione HTTPS
curl -I https://sottana.net
```

## Collegamenti Correlati

- [Installazione Certificato SSL](./ssl-certificate-installation.md)
- [Documentazione Nginx](https://nginx.org/en/docs/)
When running the application with SSL/HTTPS, you may need to update your .env file to reflect the secure URL:

```
APP_URL=https://techplanner.local
```

This ensures:
- Asset URLs are generated with HTTPS
- Redirects maintain the HTTPS protocol
- Session cookies are properly handled with secure flag when appropriate

The SSL certificates have been generated and configured in Apache with the setup script located at:
- `bashscripts/ssl/setup_techplanner_ssl.sh`

The certificates are valid for 365 days and are self-signed, which is appropriate for development environments but should be replaced with certificates from a trusted Certificate Authority in production.

To access the application securely after SSL setup:
1. Run the SSL setup script: `bash bashscripts/ssl/setup_techplanner_ssl.sh`
2. Update your APP_URL in .env to use https://techplanner.local
3. Access the application at: https://techplanner.local

Note: You may need to accept the self-signed certificate warning in your browser.
- [Documentazione Certbot](https://certbot.eff.org/docs/)