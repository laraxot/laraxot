
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/techplanner.local.key \
    -out /etc/ssl/certs/techplanner.local.crt

sudo a2enmod ssl
sudo a2enmod rewrite

sudo cp /etc/ssl/certs/techplanner.local.crt /usr/local/share/ca-certificates/
sudo update-ca-certificates


