<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Timbre de l'application admin
 */

class AdminTimbre extends Admin
{

  protected $methodes = [
    'l' => ['nom' => 'listerTimbres',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'a' => ['nom' => 'ajouterTimbre',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'm' => ['nom' => 'modifierTimbre',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    's' => ['nom' => 'supprimerTimbre', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct()
  {
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les timbres
   */
  public function listerTimbres()
  {
    $timbres = $this->oRequetesSQL->getTimbres();
    (new Vue)->generer(
      'vAdminTimbres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Mes timbres',
        'timbres'             => $timbres,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction
      ],
      'gabarit-admin'
    );
  }


  /**
   * Ajouter un timbre
   */
  public function ajouterTimbre()
  {
    $pays = $this->oRequetesSQL->getPays();
    if (count($_POST) !== 0) {
      $timbre = $_POST;
      $oTimbre = new Timbre($timbre);
      $erreurs = $oTimbre->erreurs;
      if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->ajouterTimbre([
          'timbre_titre'               => $oTimbre->timbre_titre,
          'timbre_description'         => $oTimbre->timbre_description,
          'timbre_annee_publication'   => $oTimbre->timbre_annee_publication,
          'timbre_condition'           => $oTimbre->timbre_condition,
          'timbre_pays_id'             => $oTimbre->timbre_pays_id,
          'timbre_dimensions'          => $oTimbre->timbre_dimensions,
          'timbre_tirage'              => $oTimbre->timbre_tirage,
          'timbre_couleur'             => $oTimbre->timbre_couleur,
          'timbre_certification'       => $oTimbre->timbre_certification,
          'timbre_utilisateur_id'      => self::$oUtilConn->utilisateur_id
        ]);
        if (preg_match('/^[1-9]\d*$/', $retour)) {
          $this->messageRetourAction = "Ajout du timbre numéro $retour effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout du timbre non effectué. " . $retour;
        }
        $this->listerTimbres();
        exit;
      }
    } else {
      $timbre    = [];
      $erreurs = [];
    }

    (new Vue)->generer(
      'vAdminTimbreAjouter',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Ajouter un timbre',
        'timbre'    => $timbre,
        'pays'      => $pays,
        'erreurs'   => $erreurs
      ],
      'gabarit-admin'
    );
  }

  /**
   * Modifier un timbre
   */
  public function modifierTimbre()
  {
    if (!preg_match('/^\d+$/', $this->timbre_id))
      throw new Exception("Numéro du timbre non renseigné pour une modification");

    if (count($_POST) !== 0) {
      $timbre = $_POST;
      $oTimbre = new Timbre($timbre);
      $erreurs = $oTimbre->erreurs;
      if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->modifierTimbre([
          'timbre_titre'               => $oTimbre->timbre_titre,
          'timbre_description'         => $oTimbre->timbre_description,
          'timbre_annee_publication'   => $oTimbre->timbre_annee_publication,
          'timbre_condition'           => $oTimbre->timbre_condition,
          'timbre_pays_id'             => $oTimbre->timbre_pays_id,
          'timbre_dimensions'          => $oTimbre->timbre_dimensions,
          'timbre_tirage'              => $oTimbre->timbre_tirage,
          'timbre_couleur_dominante'   => $oTimbre->timbre_couleur_dominante,
          'timbre_certification'       => $oTimbre->timbre_certification,
          'timbre_debut_enchere'       => $oTimbre->timbre_debut_enchere,
          'timbre_fin_enchere'         => $oTimbre->timbre_fin_enchere,
          'timbre_prix_plancher'       => $oTimbre->timbre_prix_plancher,
          'timbre_utilisateur_id'      => $oTimbre->timbre_utilisateur_id,
          'timbre_statut'              => $oTimbre->timbre_statut
        ]);
        $this->messageRetourAction = "";
        if ($retour === true) {
          $this->messageRetourAction .= "Modification du timbre numéro $this->timbre_id effectuée.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction .= "modification du timbre numéro $this->timbre_id non effectuée.";
          if ($retour !== false) $this->messageRetourAction .= " " . $retour;
        }
        $this->listerTimbres();
        exit;
      }
    } else {
      $timbre = $this->oRequetesSQL->getTimbre($this->timbre_id, 'admin');
      $erreurs = [];
    }

    (new Vue)->generer(
      'vAdminTimbreModifier',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => "Modifier le timbre numéro $this->timbre_id",
        'timbre'    => $timbre,
        'erreurs'   => $erreurs
      ],
      'gabarit-admin'
    );
  }

  /**
   * Supprimer un timbre
   */
  public function supprimerTimbre()
  {
    if (!preg_match('/^\d+$/', $this->timbre_id))
      throw new Exception("Numéro de timbre incorrect pour une suppression.");

    $retour = $this->oRequetesSQL->supprimerTimbre($this->timbre_id);
    if ($retour === true) {
      $this->messageRetourAction = "Suppression du timbre numéro $this->timbre_id effectuée.";
    } else {
      $this->classRetour = "erreur";
      $this->messageRetourAction = "Suppression du timbre numéro $this->timbre_id non effectuée. " . $retour;
    }
    $this->listerTimbres();
  }
}
