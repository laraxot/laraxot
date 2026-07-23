# SSL Certificate Setup for techplanner.local

## Issue Description
Apache configuration error: `AH00526: Syntax error on line 9 of /etc/apache2/sites-enabled/techplanner.local.conf: SSLCertificateFile: file '/etc/ssl/certs/techplanner.local.crt' does not exist or is empty`

## Root Cause
The Apache virtual host configuration is referencing SSL certificate files that don't exist in the system paths:
- `/etc/ssl/certs/techplanner.local.crt` (certificate)
- `/etc/ssl/private/techplanner.local.key` (private key)

## Solution
The issue can be resolved by generating the required SSL certificate files and properly configuring Apache.

### Automated Solution
Run the provided setup script:

```bash
bashscripts/ssl/setup_techplanner_ssl.sh
```

This script will:
1. Generate self-signed SSL certificates
2. Place them in the correct system locations
3. Create and enable the Apache virtual host configuration
4. Restart Apache to apply changes

### Manual Solution
If you prefer to set it up manually:

1. Create the certificate directories:
   ```bash
   sudo mkdir -p /etc/ssl/certs
   sudo mkdir -p /etc/ssl/private
   ```

2. Generate the certificates:
   ```bash
   # Generate private key
   sudo openssl genpkey -algorithm RSA -out /etc/ssl/private/techplanner.local.key
   
   # Create a config file to avoid interactive prompts
   cat > ssl_config.conf << EOF
   [req]
   default_bits = 2048
   prompt = no
   default_md = sha256
   distinguished_name = dn
   
   [dn]
   C=IT
   ST=Italy
   L=Local
   O=TechPlanner
   CN=techplanner.local
   EOF
   
   # Generate certificate signing request
   sudo openssl req -new -key /etc/ssl/private/techplanner.local.key -out techplanner.local.csr -config ssl_config.conf
   
   # Generate self-signed certificate
   sudo openssl x509 -req -in techplanner.local.csr -signkey /etc/ssl/private/techplanner.local.key -out /etc/ssl/certs/techplanner.local.crt -days 365
   
   # Set proper permissions
   sudo chmod 644 /etc/ssl/certs/techplanner.local.crt
   sudo chmod 600 /etc/ssl/private/techplanner.local.key
   ```

3. Enable SSL module:
   ```bash
   sudo a2enmod ssl
   ```

4. Create virtual host configuration:
   ```bash
   sudo a2ensite techplanner.local.conf
   ```

5. Restart Apache:
   ```bash
   sudo systemctl restart apache2
   ```

## Additional Notes
- The SSL certificate is self-signed, so browsers will show a security warning
- For production environments, use certificates from a trusted Certificate Authority (CA)
- The certificates are valid for 365 days
- Apache SSL module must be enabled for HTTPS to work

## Testing
After setup, verify the SSL configuration with:
```bash
sudo apache2ctl configtest
```

The output should show "Syntax OK" if the configuration is correct.

You can then access your site at: https://techplanner.local