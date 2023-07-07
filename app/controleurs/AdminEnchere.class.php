<?php

/**
 * Classe Contrôleur des requêtes sur l'entité EnchereTimbre de l'application admin
 */

class AdminEnchere extends Admin
{

  protected $methodes = [
    'l' => ['nom' => 'listerEncheresTimbres',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'a' => ['nom' => 'ajouterEnchereTimbre',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'm' => ['nom' => 'modifierEnchereTimbre',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    's' => ['nom' => 'supprimerEnchereTimbre', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct()
  {
    $this->enchereTimbre_id = $_GET['enchereTimbre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les encheres des timbres
   */
  public function listerEncheresTimbres()
  {
    if (self::$oUtilConn->utilisateur_profil === Utilisateur::PROFIL_ADMINISTRATEUR) {
      $encheresTimbres = $this->oRequetesSQL->getEncheresTimbres('membre_admin');
      $titre = 'Toutes les enchères de timbres';
    } else {
      $encheresTimbres = $this->oRequetesSQL->getEncheresTimbres('membre_owner');
      $titre = 'Mes enchères de timbres';
    }

    (new Vue)->generer(
      'vAdminEncheres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => $titre,
        'encheresTimbres'     => $encheresTimbres,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction
      ],
      'gabarit-admin'
    );
  }


  /**
   * Ajouter une enchère de timbre
   */
  public function ajouterEnchereTimbre()
  {
    if (count($_POST) !== 0) {

      // ajout de l'enchère et du timbre
      $enchere = $_POST['enchere'];
      $timbre = $_POST['timbre'];

      $oEnchere = new Enchere($enchere);
      $oTimbre = new Timbre($timbre);

      $erreursEnchere = $oEnchere->erreurs;
      $erreursTimbre = $oTimbre->erreurs;

      // on valide qu'il n'y a pas d'erreur dans enchere ET timbre avant d'insérer dans la bd
      if (count($erreursEnchere) === 0 && count($erreursTimbre) === 0) {
        $enchere_id = $this->oRequetesSQL->ajouterEnchere([
          'enchere_date_debut'         => $oEnchere->enchere_date_debut,
          'enchere_date_fin'           => $oEnchere->enchere_date_fin,
          'enchere_prix_plancher'      => $oEnchere->enchere_prix_plancher,
          'enchere_coups_coeur_lord'   => $oEnchere->enchere_coups_coeur_lord,
          'enchere_utilisateur_id'     => self::$oUtilConn->utilisateur_id
        ]);

        // ajout du timbre avec enchere_id
        $timbre['timbre_enchere_id'] = $enchere_id;
        $oTimbre = new Timbre($timbre);

        $timbre_id = $this->oRequetesSQL->ajouterTimbre([
          'timbre_titre'               => $oTimbre->timbre_titre,
          'timbre_description'         => $oTimbre->timbre_description,
          'timbre_annee_publication'   => $oTimbre->timbre_annee_publication,
          'timbre_condition'           => $oTimbre->timbre_condition,
          'timbre_pays_id'             => $oTimbre->timbre_pays_id,
          'timbre_dimensions'          => $oTimbre->timbre_dimensions,
          'timbre_tirage'              => $oTimbre->timbre_tirage,
          'timbre_couleur'             => $oTimbre->timbre_couleur,
          'timbre_certification'       => $oTimbre->timbre_certification,
          'timbre_utilisateur_id'      => self::$oUtilConn->utilisateur_id,
          'timbre_enchere_id'          => $oTimbre->timbre_enchere_id
        ]);

        // ajout des images
        $image_id = $this->oRequetesSQL->modifierTimbreImage($timbre_id);


        if (preg_match('/^[1-9]\d*$/', $enchere_id) && preg_match('/^[1-9]\d*$/', $timbre_id) && preg_match('/^[1-9]\d*$/', $image_id)) {
          $this->messageRetourAction = "Ajout de l'enchère numéro $enchere_id effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout de l'enchère non effectué. " . $enchere_id . $timbre_id . $image_id;
        }
        $this->listerEncheresTimbres();
        exit;
      }
    } else {
      $timbre = [];
      $enchere = [];
      $erreursTimbre = [];
      $erreursEnchere = [];
    }
    $pays = $this->oRequetesSQL->getPays();

    (new Vue)->generer(
      'vAdminEnchereAjouter',
      [
        'oUtilConn'      => self::$oUtilConn,
        'titre'          => 'Ajouter une enchère',
        'timbre'         => $timbre,
        'enchere'        => $enchere,
        'pays'           => $pays,
        'erreursTimbre'  => $erreursTimbre,
        'erreursEnchere' => $erreursEnchere,
        'erreursTimbre'  => $erreursTimbre
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
      // if ($_FILES['timbre_image']['tmp_name'] !== "") $oTimbre->setTimbre_image($_FILES['timbre_image']['name']);
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
        $this->listerEncheresTimbres();
        exit;
      }
    } else {
      $timbre = $this->oRequetesSQL->getTimbre($this->timbre_id, 'admin');
      $erreurs = [];
    }
    $pays = $this->oRequetesSQL->getPays();

    (new Vue)->generer(
      'vAdminEnchereModifier',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => "Modifier le timbre numéro $this->timbre_id",
        'timbre'    => $timbre,
        'pays'      => $pays,
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
    $this->listerEncheresTimbres();
  }
}
