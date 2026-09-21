# MySQL/MariaDB Remoto — Configurazione

**Data:** 2026-09-10
**IP macchina:** 192.168.1.35
**Utente:** marco
**Password:** marco
**Host:** % (qualsiasi IP)

## Stato

✅ **Operativo** — connessione remota confermata da esterno.

## Configurazione applicata

| Parametro | Valore | Dove |
|-----------|--------|------|
| bind-address | 0.0.0.0 | `/etc/mysql/mariadb.conf.d/50-server.cnf` |
| utente | marco@% | `CREATE USER 'marco'@'%' IDENTIFIED BY 'marco'` |
| privilegi | ALL PRIVILEGES | `GRANT ALL PRIVILEGES ON *.* TO 'marco'@'%' WITH GRANT OPTION` |
| firewall | UFW inattivo | Nessun blocco porta 3306 |

## Verifica

```bash
# Da qualsiasi IP nella rete:
mysql -h 192.168.1.35 -u marco -p

# Test locale:
mysql -h 192.168.1.35 -u marco -pmarco -e "SELECT VERSION(), CURRENT_USER();"
```

## Note di sicurezza

- Utente `marco@%` con password `marco` — accetta connessioni da **qualsiasi** IP
- UFW inattivo — nessun filtro firewall sulla porta 3306
- Per ambiente produzione: usare password forte, host specifici, SSL/TLS

## Riferimenti

- [MariaDB bind-address](https://mariadb.com/kb/en/server-system-variables/#bind_address)
- [MariaDB user accounts](https://mariadb.com/kb/en/create-user/)