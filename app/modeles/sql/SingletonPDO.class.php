<?php

class SingletonPDO extends PDO
{
  private static $instance = null;

  const DB_SERVEUR  = '127.0.0.1';
  const DB_NOM      = (ENV == "DEV") ?  'stampee' : 'e2296540';
  const DB_DSN      = 'mysql:host=' . self::DB_SERVEUR . ';dbname=' . self::DB_NOM . ';charset=utf8';
  const DB_LOGIN    = (ENV == "DEV") ?  'root' : 'e2296540';
  const DB_PASSWORD = (ENV == "DEV") ?  'root' : 'iloIGaO6rJ1ZYQq8gKTl';

  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  private function __construct()
  {

    $options = [
      PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs par des exceptions de la classe PDOException
      PDO::ATTR_EMULATE_PREPARES  => true                    // Préparation des requêtes émulée
    ];
    $this->debug_to_console("je suis avant construct");

    parent::__construct(self::DB_DSN, self::DB_LOGIN, self::DB_PASSWORD, $options);
    $this->query("SET lc_time_names = 'fr_FR'"); // Pour afficher les jours en français

    $this->debug_to_console("je suis apres construct");
  }

  private function __clone()
  {
  }

  public static function getInstance()
  {
    debug_to_console("je suis dans getinstance");

    if (is_null(self::$instance)) {
      self::$instance = new SingletonPDO();
    }
    return self::$instance;
  }
}
