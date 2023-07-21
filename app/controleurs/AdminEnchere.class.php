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
    $this->enchere_id = $_GET['enchere_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les encheres des timbres
   */
  public function listerEncheresTimbres()
  {
    if (self::$oUtilConn->utilisateur_profil_id === Utilisateur::PROFIL_ADMINISTRATEUR) {
      $encheresMises = $this->oRequetesSQL->getEncheresMises('admin-enchere');
      $titre = 'Toutes les enchères';
    } else {
      $encheresMises = $this->oRequetesSQL->getEncheresMises('membre-owner');
      $titre = 'Mes enchères';
    }

    (new Vue)->generer(
      'vAdminEncheres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => $titre,
        'encheresMises'       => $encheresMises,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction,
        'entite'              => self::$entite
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
      // ajout de l'enchère, du timbre et de l'image
      $enchere = $_POST['enchere'];
      $timbre = $_POST['timbre'];
      $image = [];
      $image['image_nom_fichier'] = $_FILES['image_nom_fichier']['tmp_name'];

      $oEnchere = new Enchere($enchere);
      $oTimbre = new Timbre($timbre);
      $oImage = new Image($image);

      $erreursEnchere = $oEnchere->erreurs;
      $erreursTimbre = $oTimbre->erreurs;
      $erreursImage = $oImage->erreurs;

      // on valide qu'il n'y a pas d'erreur dans enchere ET timbre ET image avant d'insérer dans la bd
      if (count($erreursEnchere) === 0 && count($erreursTimbre) === 0 && count($erreursImage) === 0) {
        $enchere_id = $this->oRequetesSQL->ajouterEnchere([
          'enchere_date_debut'         => $oEnchere->enchere_date_debut,
          'enchere_date_fin'           => $oEnchere->enchere_date_fin,
          'enchere_prix_reserve'       => $oEnchere->enchere_prix_reserve,
          'enchere_coup_coeur'         => $oEnchere->enchere_coup_coeur,
          'enchere_utilisateur_id'     => self::$oUtilConn->utilisateur_id
        ]);

        // ajout du timbre avec enchere_id
        $timbre['timbre_enchere_id'] = $enchere_id;
        $oTimbre = new Timbre($timbre);

        $timbre_id = $this->oRequetesSQL->ajouterTimbre([
          'timbre_titre'               => $oTimbre->timbre_titre,
          'timbre_description'         => $oTimbre->timbre_description,
          'timbre_annee_publication'   => $oTimbre->timbre_annee_publication,
          'timbre_condition_id'        => $oTimbre->timbre_condition_id,
          'timbre_pays_id'             => $oTimbre->timbre_pays_id,
          'timbre_dimensions'          => $oTimbre->timbre_dimensions,
          'timbre_tirage_id'           => $oTimbre->timbre_tirage_id,
          'timbre_couleur_id'          => $oTimbre->timbre_couleur_id,
          'timbre_certification'       => $oTimbre->timbre_certification,
          'timbre_utilisateur_id'      => self::$oUtilConn->utilisateur_id,
          'timbre_enchere_id'          => $oTimbre->timbre_enchere_id
        ]);

        // ajout de l'image
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
      $image = [];
      $erreursTimbre = [];
      $erreursEnchere = [];
      $erreursImage = [];
    }
    $pays = $this->oRequetesSQL->getPays();
    $conditions = $this->oRequetesSQL->getConditions();
    $tirages = $this->oRequetesSQL->getTirages();
    $couleurs = $this->oRequetesSQL->getCouleurs();

    (new Vue)->generer(
      'vAdminEnchereAjouter',
      [
        'oUtilConn'      => self::$oUtilConn,
        'titre'          => 'Ajouter une enchère',
        'timbre'         => $timbre,
        'enchere'        => $enchere,
        'image'          => $image,
        'pays'           => $pays,
        'conditions'     => $conditions,
        'tirages'        => $tirages,
        'couleurs'       => $couleurs,
        'erreursTimbre'  => $erreursTimbre,
        'erreursEnchere' => $erreursEnchere,
        'erreursImage'   => $erreursImage,
      ],
      'gabarit-admin'
    );
  }



  /**
   * Modifier un timbre
   */
  public function modifierEnchereTimbre()
  {
    if (!preg_match('/^\d+$/', $this->enchere_id))
      throw new Exception("Numéro de l'enchère non renseigné pour une modification");

    if (count($_POST) !== 0) {

      // ajout de l'enchère, du timbre et de l'image
      $enchere = $_POST['enchere'];
      $timbre = $_POST['timbre'];

      $oEnchere = new Enchere($enchere);
      $oTimbre = new Timbre($timbre);

      $this->oRequetesSQL->modifierTimbreImage($oTimbre->timbre_id);

      $erreursEnchere = $oEnchere->erreurs;
      $erreursTimbre = $oTimbre->erreurs;

      // on valide qu'il n'y a pas d'erreur dans enchere ET timbre avant d'insérer dans la bd
      if (count($erreursEnchere) === 0 && count($erreursTimbre) === 0) {

        $retour = $this->oRequetesSQL->modifierEnchereTimbre(
          array(
            "enchere" => array(
              'enchere_id'                 => $oEnchere->enchere_id,
              'enchere_date_debut'         => $oEnchere->enchere_date_debut,
              'enchere_date_fin'           => $oEnchere->enchere_date_fin,
              'enchere_prix_reserve'       => $oEnchere->enchere_prix_reserve,
              'enchere_coup_coeur'         => $oEnchere->enchere_coup_coeur,
            ),
            "timbre" => array(
              'timbre_id'                  => $oTimbre->timbre_id,
              'timbre_titre'               => $oTimbre->timbre_titre,
              'timbre_description'         => $oTimbre->timbre_description,
              'timbre_annee_publication'   => $oTimbre->timbre_annee_publication,
              'timbre_condition_id'        => $oTimbre->timbre_condition_id,
              'timbre_pays_id'             => $oTimbre->timbre_pays_id,
              'timbre_dimensions'          => $oTimbre->timbre_dimensions,
              'timbre_tirage_id'           => $oTimbre->timbre_tirage_id,
              'timbre_couleur_id'          => $oTimbre->timbre_couleur_id,
              'timbre_certification'       => $oTimbre->timbre_certification,
            )
          )
        );
      }

      if ($retour) {
        $this->messageRetourAction = "Modification de l'enchère $this->enchere_id effectuée.";
      } else {
        $this->classRetour = "erreur";
        $this->messageRetourAction = "Modification de l'enchère de timbre $this->enchere_id non effectuée. ";
      }
      $this->listerEncheresTimbres();
      exit;
    } else {
      $timbre = $this->oRequetesSQL->getTimbre($this->enchere_id);
      $enchere = $this->oRequetesSQL->getEnchere($this->enchere_id);
      $image = $this->oRequetesSQL->getImage($this->enchere_id);
      $erreursTimbre = [];
      $erreursEnchere = [];
      $erreursImage = [];
    }
    $pays = $this->oRequetesSQL->getPays();
    $conditions = $this->oRequetesSQL->getConditions();
    $tirages = $this->oRequetesSQL->getTirages();
    $couleurs = $this->oRequetesSQL->getCouleurs();

    (new Vue)->generer(
      'vAdminEnchereModifier',
      [
        'oUtilConn'      => self::$oUtilConn,
        'titre'          => 'Modifier une enchère',
        'timbre'         => $timbre,
        'enchere'        => $enchere,
        'image'          => $image,
        'pays'           => $pays,
        'conditions'     => $conditions,
        'tirages'        => $tirages,
        'couleurs'       => $couleurs,
        'erreursTimbre'  => $erreursTimbre,
        'erreursEnchere' => $erreursEnchere,
        'erreursImage'   => $erreursImage
      ],
      'gabarit-admin'
    );
  }

  /**
   * Supprimer un timbre
   */
  public function supprimerEnchereTimbre()
  {
    if (!preg_match('/^\d+$/', $this->enchere_id))
      throw new Exception("Numéro de timbre incorrect pour une suppression.");

    $timbre = $this->oRequetesSQL->getTimbre($this->enchere_id);

    $retour = $this->oRequetesSQL->supprimerEnchereTimbre($timbre["timbre_id"]);
    if ($retour === true) {
      $this->messageRetourAction = "Suppression de l'enchère numéro $this->enchere_id effectuée.";
    } else {
      $this->classRetour = "erreur";
      $this->messageRetourAction = "Suppression de l'enchère numéro $this->enchere_id non effectuée. " . $retour;
    }
    $this->listerEncheresTimbres();
  }
}
