<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Pays de l'application membre
 */

class MembrePays extends Membre
{

  protected $methodes = [
    'l'  => ['nom' => 'listerPays',        'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    'a'  => ['nom' => 'ajouterPays',       'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    'm'  => ['nom' => 'modifierPays',      'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    's'  => ['nom' => 'supprimerPays',     'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    'lf' => ['nom' => 'listerPaysTimbre',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'af' => ['nom' => 'ajouterPaysTimbre',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'sf' => ['nom' => 'supprimerPaysTimbre', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]]
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
      'vMembrePays',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des pays',
        'pays'                => $pays,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction
      ],
      'gabarit-membre'
    );
  }

  /**
   * Lister les pays d'un timbre
   */
  public function listerPaysTimbre()
  {
    $pays = $this->oRequetesSQL->getPaysTimbre($this->timbre_id, true);
    echo json_encode($pays);
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
          'pays_nom' => $oPays->pays_nom
        ]);
        if (preg_match('/^[1-9]\d*$/', $retour)) {
          $this->messageRetourAction = "Ajout du pays numéro $retour effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout du pays non effectué.";
        }
        $this->listerPays();
        exit;
      }
    } else {
      $pays    = [];
      $erreurs = [];
    }

    (new Vue)->generer(
      'vMembrePaysAjouter',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Ajouter un pays',
        'pays'      => $pays,
        'erreurs'   => $erreurs
      ],
      'gabarit-membre'
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
          $this->messageRetourAction = "Modification du pays numéro $this->pays_id effectuée.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Modification du pays numéro $this->pays_id non effectuée.";
        }
        $this->listerPays();
        exit;
      }
    } else {
      $pays    = $this->oRequetesSQL->getUnPays($this->pays_id);
      $erreurs = [];
    }

    (new Vue)->generer(
      'vMembrePaysModifier',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => "Modifier le pays numéro $this->pays_id",
        'pays'      => $pays,
        'erreurs'   => $erreurs
      ],
      'gabarit-membre'
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

  /**
   * Ajouter un pays à un film dans la table de jonction film_pays
   */
  public function ajouterPaysTimbre()
  {
    $retour = $this->oRequetesSQL->ajouterPaysTimbre(
      [
        'pays_id' => $this->pays_id,
        'timbre_id' => $this->timbre_id
      ]
    );
    echo json_encode($retour);
  }

  /**
   * Supprimer un pays d'un film dans la table de jonction film_pays
   */
  public function supprimerPaysTimbre()
  {
    $retour = $this->oRequetesSQL->supprimerPaysTimbre(
      [
        'pays_id' => $this->pays_id,
        'timbre_id' => $this->timbre_id
      ]
    );
    echo json_encode($retour);
  }
}
