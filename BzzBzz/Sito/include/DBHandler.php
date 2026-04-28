<?php
// dbHandler.php — Connessione al database BzzBzz

class DBHandler {
    private static ?PDO $connection = null;

    private const DB_HOST     = 'localhost';
    private const DB_NAME     = 'BzzBzz';
    private const DB_USER     = 'root';
    private const DB_PASSWORD = '';
    private const DB_CHARSET  = 'utf8mb4';

    // Impedisce l'istanziazione diretta (Singleton)
    private function __construct() {}

    /**
     * Restituisce l'unica istanza PDO (Singleton).
     */
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::DB_HOST,
                self::DB_NAME,
                self::DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$connection = new PDO($dsn, self::DB_USER, self::DB_PASSWORD, $options);
            } catch (PDOException $e) {
                // In produzione evita di mostrare dettagli dell'errore
                error_log('[DBHandler] Connessione fallita: ' . $e->getMessage());
                http_response_code(500);
                die(json_encode(['success' => false, 'message' => 'Errore interno del server.']));
            }
        }

        return self::$connection;
    }

    // Chiude la connessione.
    public static function closeConnection(): void {
        self::$connection = null;
    }
}