<?php

/**
 * Classe responsável pela ligação à base de dados via PDO.
 * Usa o padrão Singleton: garante que só existe UMA ligação aberta
 * durante todo o pedido, mesmo que vários Models a peçam.
 */
class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance === null) {
            $envFile = __DIR__ . '/env.php';

            if (!file_exists($envFile)) {
                die('Ficheiro config/env.php não encontrado. Copia config/env.example.php para config/env.php.');
            }

            $config = require $envFile;

            $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset={$config['DB_CHARSET']}";

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['DB_USER'],
                    $config['DB_PASS'],
                    [
                        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                die('Erro de ligação à base de dados: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    // Impede criar novas instâncias ou clonar (reforça o Singleton)
    private function __construct() {}
    private function __clone() {}
}