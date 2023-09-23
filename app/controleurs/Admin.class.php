<?php

/**
 * Classe Contrôleur des requêtes de l'application admin
 */

class Admin extends Routeur
{

  protected $utilisateur_id;
  protected $timbre_id;
  protected $enchere_id;
  protected $enchereTimbre_id;
  protected $mise_id;
  protected $image_id;
  protected $pays_id;

  protected $methodes;
  protected static $entite;
  protected static $action;
  protected static $oUtilConn; // objet Utilisateur connecté

  protected $classRetour = "fait";
  protected $messageRetourAction = "";

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * 
   */
  public function __construct()
  {
    self::$entite = $_GET['entite'] ?? 'enchere';
    self::$action = $_GET['action'] ?? 'l';
  }

  /**
   * Gérer l'interface d'administration 
   */
  public function gererEntite()
  {
    if (isset($_SESSION['oUtilConn'])) {
      self::$oUtilConn = $_SESSION['oUtilConn'];
      $entite = ucwords(self::$entite);
      $classe = "Admin$entite";
      if (class_exists($classe)) {
        (new $classe())->gererAction();
      } else {
        throw new Exception("L'entité " . self::$entite . " n'existe pas.");
      }
    } else {
      $accueil = new Frontend();
      $accueil->afficherAccueil();
    }
  }

  /**
   * Gérer l'interface d'administration d'une entité
   */
  public function gererAction()
  {
    if (isset($this->methodes[self::$action])) {
      $methode = $this->methodes[self::$action]['nom'];
      if (isset($this->methodes[self::$action]['droits'])) {
        $droits = $this->methodes[self::$action]['droits'];
        foreach ($droits as $droit) {
          if ($droit == self::$oUtilConn->utilisateur_profil_id) {
            $this->$methode();
            exit;
          }
        }
        throw new Exception(self::ERROR_FORBIDDEN);
      } else {
        $this->$methode();
      }
    } else {
      throw new Exception("L'action " . self::$action . " de l'entité " . self::$entite . " n'existe pas.");
    }
  }
}
