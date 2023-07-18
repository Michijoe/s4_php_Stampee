<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur
{

  private $oUtilConn;
  private $enchere_id;
  private $catalogue_type;
  private $filtres;

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct()
  {
    $this->oUtilConn         = $_SESSION['oUtilConn'] ?? null;
    $this->enchere_id        = $_GET['enchere_id'] ?? null;
    $this->catalogue_type    = $_GET['catalogue'] ?? null;
    $this->filtres           = $_POST['filtres'] ?? null;
    $this->oRequetesSQL      = new RequetesSQL;
  }

  /**
   * Connecter un utilisateur
   */
  public function connecter()
  {
    $utilisateur = $this->oRequetesSQL->connecter($_POST);
    if ($utilisateur !== false) {
      $_SESSION['oUtilConn'] = new Utilisateur($utilisateur);
    }
    echo json_encode($utilisateur);
  }

  /**
   * Créer un compte utilisateur
   */
  public function creerCompte()
  {
    $oUtilisateur = new Utilisateur($_POST);
    $erreurs = $oUtilisateur->erreurs;
    if (count($erreurs) > 0) {
      $retour = $erreurs;
    } else {
      $retour = $this->oRequetesSQL->creerCompteUtilisateur($_POST);
      if (!is_array($retour) && preg_match('/^[1-9]\d*$/', $retour)) {
        $oUtilisateur->utilisateur_profil_id = Utilisateur::PROFIL_MEMBRE;
        $_SESSION['oUtilConn'] = $oUtilisateur;
      }
    }
    echo json_encode($retour);
  }


  /**
   * Afficher l'accueil
   * 
   */
  public function afficherAccueil()
  {
    $nouveautes = $this->oRequetesSQL->getEncheresMises('public-nouveaute');

    // $coupsCoeur = $this->oRequetesSQL->getTimbres('coupsCoeur');

    (new Vue)->generer(
      "vAccueil",
      [
        'oUtilConn'      => $this->oUtilConn,
        'titre'          => 'Accueil',
        'titreHB'        => 'La valeur sûre des enchères de timbres en ligne',
        'texteHB'        => 'Avec déjà plus de 100 000 timbres vendus, Stampee est la maison d\'enchères de timbres la plus populaire auprès des collectionneurs depuis 1949. Découvrez les collections uniques du lord Stampee et partez à la recherche de vos nouvelles acquisitions.',
        'nouveautes'     => $nouveautes,
        // 'coupsCoeur'     => $coupsCoeur,
      ],
      "gabarit-frontend"
    );
  }


  /**
   * Afficher le catalogue complet, des enchères actives ou des enchères archivées
   * 
   */
  public function afficherCatalogue()
  {
    // affichage filtres
    $pays        = $this->oRequetesSQL->getPays();
    $conditions  = $this->oRequetesSQL->getConditions();
    $couleurs    = $this->oRequetesSQL->getCouleurs();
    $tirages     = $this->oRequetesSQL->getTirages();

    // reset des filtres au submit du bouton reset
    if (isset($_POST['reset'])) $this->filtres = null;

    // montage du tableau de filtres sans entrées vides
    if ($this->filtres) {
      $filtres = [];
      foreach ($this->filtres as $key => $value) {
        if (!empty($value)) {
          $filtres[$key] = $value;
        }
      }
    }

    $catalogue = $this->oRequetesSQL->getEncheresMises($this->catalogue_type, $filtres ?? null);

    switch ($this->catalogue_type) {
      case 'public-actif':
        $typeEnchere = "enchères en cours";
        break;
      case 'public-archive':
        $typeEnchere = "enchères archivées";
        break;
      case 'public-futur':
        $typeEnchere = "enchères à venir";
        break;
    }

    (new Vue)->generer(
      "vCatalogue",
      [
        'oUtilConn'          => $this->oUtilConn,
        'pays'               => $pays,
        'conditions'         => $conditions,
        'couleurs'           => $couleurs,
        'tirages'            => $tirages,
        'filtres'            => $filtres ?? null,
        'titre'              => 'Catalogue',
        'titreHB'            => 'Catalogue des ' . $typeEnchere,
        'typeEnchere'        => $typeEnchere,
        'texteHB'            => '',
        'catalogue'          => $catalogue,
      ],
      "gabarit-frontend"
    );
  }


  /**
   * Afficher la fiche d'une enchere
   * 
   */
  public function afficherEnchere()
  {
    $enchere = false;
    $timbre = false;
    if (!is_null($this->enchere_id)) {
      $enchere = $this->oRequetesSQL->getEnchere($this->enchere_id);
      $timbre = $this->oRequetesSQL->getTimbre($this->enchere_id);
    }
    if (!$enchere || !$timbre) throw new Exception("Enchère ou timbre inexistants.");

    (new Vue)->generer(
      "vEnchere",
      [
        'oUtilConn'    => $this->oUtilConn,
        'titre'        => 'Timbre',
        'titreHB'      => 'Fiche Timbre',
        'texteHB'      => '',
        'enchere'      => $enchere,
        'timbre'       => $timbre
      ],
      "gabarit-frontend"
    );
  }

  /**
   * Miser sur une enchère
   */
  public function miser()
  {
    $oMise = new Mise($_POST);
    $erreurs = $oMise->erreurs;
    if (count($erreurs) > 0) {
      $retour = $erreurs;
    } else {
      $retour = $this->oRequetesSQL->miser($_POST);
    }
    echo json_encode($retour);
  }
}
