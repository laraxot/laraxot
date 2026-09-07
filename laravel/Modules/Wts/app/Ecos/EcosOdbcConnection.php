<?php

declare(strict_types=1);

namespace Modules\Wts\Ecos;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Service class to handle connection to external SQL Server Database (ECOS)
 */
class EcosOdbcConnection
{
    /**
     * Timeout di connessione: senza limite esplicito una pagina Filament resta
     * appesa finche' il TCP non scade (oltre 60s) quando ECOS non e' raggiungibile.
     */
    private const int CONNECT_TIMEOUT_SECONDS = 8;

    private string $host;

    private string $port;

    private string $database;

    private string $username;

    private string $password;

    private string $encrypt;

    private string $trustServerCertificate;

    private ?\PDO $pdo = null;

    /**
     * Initialize connection parameters from environment variables
     */
    public function __construct()
    {
        // Legge da config/database.php e non da env(): con `php artisan config:cache`
        // in produzione env() restituisce null e la connessione partirebbe senza host.
        $this->host = self::setting('host', '');
        $this->port = self::setting('port', '1433');
        $this->database = self::setting('database', '');
        $this->username = self::setting('username', '');
        $this->password = self::setting('password', '');
        $this->encrypt = self::setting('encrypt', 'no');
        $this->trustServerCertificate = self::setting('trust_server_certificate', 'false');
    }

    /**
     * Legge una chiave della connessione ECOS normalizzandola a stringa.
     */
    private static function setting(string $key, string $default): string
    {
        $value = config('database.connections.ecos.'.$key);

        // env('X', 'true') restituisce il booleano true, non la stringa: va
        // normalizzato o i confronti sui flag (encrypt, trust cert) falliscono.
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $default;
    }

    /**
     * Establish PDO connection to ECOS database
     */
    public function connect(): \PDO
    {
        if ($this->pdo instanceof \PDO) {
            return $this->pdo;
        }

        // IMPORTANTE: `extension_loaded()` non basta. Su hosting condiviso
        // pdo_sqlsrv puo' risultare caricato ma fallire in connessione perche'
        // manca il driver di sistema Microsoft ODBC (errore IMSSP). Per questo
        // proviamo i driver in CASCATA: se uno fallisce si passa al successivo,
        // cosi' l'ambiente usa quello davvero funzionante (es. FreeTDS/pdo_odbc).
        $candidates = $this->candidateDsns();

        if ($candidates === []) {
            throw new \RuntimeException(
                'Nessun driver SQL Server disponibile per PHP (pdo_sqlsrv, pdo_odbc o pdo_dblib).'.
                ' Abilitare una delle estensioni sull\'hosting oppure esporre ECOS tramite un bridge HTTP.'
            );
        }

        $lastException = null;
        foreach ($candidates as $scheme => $dsn) {
            try {
                $this->pdo = new \PDO($dsn, $this->username, $this->password, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_TIMEOUT => self::CONNECT_TIMEOUT_SECONDS,
                ]);

                return $this->pdo;
            } catch (Throwable $e) {
                // Throwable (non solo PDOException): il fallimento di pdo_sqlsrv
                // per driver mancante puo' arrivare come tipo diverso; catturarlo
                // tutto garantisce che la cascata prosegua sul driver successivo.
                $lastException = $e;
                Log::warning('ECOS: driver '.$scheme.' non utilizzabile, provo il successivo.', [
                    'driver' => $scheme,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // $lastException e' garantito valorizzato: $candidates non e' vuoto
        // (altrimenti avremmo gia' lanciato sopra) e ogni tentativo fallito lo imposta.
        // Diagnostica: quali driver esistono DAVVERO in questo SAPI (CLI e
        // PHP-FPM del sito possono differire). Serve a sapere cosa abilitare.
        Log::error('ECOS: nessun driver SQL Server e\' riuscito a connettersi.', [
            'sapi' => PHP_SAPI,
            'tried' => array_keys($candidates),
            'pdo_drivers' => \PDO::getAvailableDrivers(),
            'ext' => [
                'pdo_sqlsrv' => extension_loaded('pdo_sqlsrv'),
                'pdo_odbc' => extension_loaded('pdo_odbc'),
                'pdo_dblib' => extension_loaded('pdo_dblib'),
                'odbc' => extension_loaded('odbc'),
            ],
            'error' => $lastException->getMessage(),
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
        ]);

        throw $lastException;
    }

    /**
     * DSN candidati, in ordine di preferenza, per ogni estensione disponibile.
     * Vengono provati in cascata finche' uno si connette davvero.
     *
     * @return array<string, string>
     */
    private function candidateDsns(): array
    {
        $candidates = [];

        // 1) pdo_sqlsrv (driver nativo Microsoft) — il migliore DOVE il driver
        //    di sistema msodbcsql e' installato; su shared hosting spesso no.
        if (extension_loaded('pdo_sqlsrv')) {
            $dsn = 'sqlsrv:server='.$this->host.','.$this->port.';Database='.$this->database.';';
            $dsn .= 'LoginTimeout='.self::CONNECT_TIMEOUT_SECONDS.';';

            if ($this->encrypt === 'yes') {
                $dsn .= 'Encrypt=true;';
            }

            if ($this->trustServerCertificate === 'true') {
                $dsn .= 'TrustServerCertificate=true;';
            }

            $candidates['pdo_sqlsrv'] = $dsn;
        }

        // 2) pdo_odbc + FreeTDS — funziona su Linux/shared hosting senza msodbcsql.
        //    Nome driver configurabile (ECOS_ODBC_DRIVER): su alcuni host non e'
        //    "FreeTDS" ma es. "ODBC Driver 17 for SQL Server".
        //    TDS_Version esplicito: senza, FreeTDS negozia un protocollo legacy
        //    che SQL Server 2012+ rifiuta durante l'handshake.
        if (extension_loaded('pdo_odbc')) {
            $odbcDriver = self::setting('odbc_driver', 'FreeTDS');
            $dsn = 'odbc:Driver={'.$odbcDriver.'};Server='.$this->host.';Port='.$this->port
                .';Database='.$this->database.';TDS_Version=7.4;ClientCharset=UTF-8;';

            if ($this->encrypt === 'yes') {
                $dsn .= 'Encryption=require;';
            }

            $candidates['pdo_odbc'] = $dsn;
        }

        // 3) pdo_dblib (php-sybase/FreeTDS) — ultima spiaggia, usata in locale.
        if (extension_loaded('pdo_dblib')) {
            $candidates['pdo_dblib'] = 'dblib:host='.$this->host.':'.$this->port
                .';dbname='.$this->database.';charset=UTF-8';
        }

        return $candidates;
    }

    /**
     * Execute a select query and return the result
     *
     * @param  array<string|int, scalar|null>  $params
     * @return array<int, array<string, mixed>>
     */
    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        /** @var array<int, array<string, mixed>> */
        return $stmt->fetchAll();
    }

    /**
     * Execute a scalar query (a single value) and return it
     *
     * @param  array<string|int, scalar|null>  $params
     */
    public function scalar(string $sql, array $params = []): mixed
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }
}
