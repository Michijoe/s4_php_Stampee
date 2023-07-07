<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Pays de l'application admin
 */

class AdminPays extends Admin
{

  protected $methodes = [
    'l' => ['nom' => 'listerPays',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    'a' => ['nom' => 'ajouterPays',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    'm' => ['nom' => 'modifierPays',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    's' => ['nom' => 'supprimerPays', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct()
  {
    $this->pays_id = $_GET['pays_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les pays
   */
  public function listerPays()
  {
    $pays = $this->oRequetesSQL->getPays();
    (new Vue)->generer(
      'vAdminPays',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des pays',
        'pays'                => $pays,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction
      ],
      'gabarit-admin'
    );
  }

  /**
   * Ajouter un pays
   */
  public function ajouterPays()
  {
    if (count($_POST) !== 0) {
      $pays = $_POST;
      $oPays = new Pays($pays);
      $erreurs = $oPays->erreurs;
      if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->ajouterPays([
          'pays_id'  => $oPays->pays_id,
          'pays_nom' => $oPays->pays_nom
        ]);
        if (preg_match('/^[1-9]\d*$/', $retour)) {
          $this->messageRetourAction = "Ajout du pays $oPays->pays_nom effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout du pays non effectué.";
        }
        $this->listerPays();
        exit;
      }
    } else {
      $pays   = [];
      $erreurs = [];
    }

    (new Vue)->generer(
      'vAdminPaysAjouter',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Ajouter un pays',
        'pays'      => $pays,
        'erreurs'   => $erreurs
      ],
      'gabarit-admin'
    );
  }

  /**
   * Modifier un pays
   */
  public function modifierPays()
  {
    if (count($_POST) !== 0) {
      $pays = $_POST;
      $oPays = new Pays($pays);
      $erreurs = $oPays->erreurs;
      if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->modifierPays([
          'pays_id'  => $oPays->pays_id,
          'pays_nom' => $oPays->pays_nom
        ]);
        if ($retour === true) {
          $this->messageRetourAction = "Modification du pays $oPays->pays_nom effectuée.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Modification du pays $oPays->pays_nom non effectuée.";
        }
        $this->listerPays();
        exit;
      }
    } else {
      $pays = $this->oRequetesSQL->getUnPays($this->pays_id);
      $erreurs = [];
    }

    (new Vue)->generer(
      'vAdminPaysModifier',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => "Modifier le pays",
        'pays'     => $pays,
        'erreurs'   => $erreurs
      ],
      'gabarit-admin'
    );
  }

  /**
   * Supprimer un pays
   */
  public function supprimerPays()
  {
    $retour = $this->oRequetesSQL->supprimerPays($this->pays_id);
    if ($retour === false) $this->classRetour = "erreur";
    $this->messageRetourAction = "Suppression du pays numéro $this->pays_id " . ($retour ? "" : "non ") . "effectuée.";
    $this->listerPays();
  }
}
