<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur
{

  private $oUtilConn;
  private $enchere_id;
  private $timbre_id;

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct()
  {
    $this->oUtilConn = $_SESSION['oUtilConn'] ?? null;
    $this->timbre_id   = $_GET['timbre_id']       ?? null;
    $this->oRequetesSQL = new RequetesSQL;
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
        $oUtilisateur->utilisateur_profil = Utilisateur::PROFIL_MEMBRE;
        $_SESSION['oUtilConn'] = $oUtilisateur;
      }
    }
    echo json_encode($retour);
  }

  /**
   * Déconnecter un utilisateur
   */
  public function deconnecter()
  {
    unset($_SESSION['oUtilConn']);
    echo json_encode(true);
  }



  /**
   * Afficher l'accueil
   * 
   */
  public function afficherAccueil()
  {
    // $nouveautes = $this->oRequetesSQL->getTimbres('nouveautes');
    // $coupsCoeur = $this->oRequetesSQL->getTimbres('coupsCoeur');

    (new Vue)->generer(
      "vAccueil",
      [
        'oUtilConn'      => $this->oUtilConn,
        'titre'          => 'Accueil',
        'titreHB'        => 'La valeur sûre des enchères de timbres en ligne',
        'texteHB'        => 'Avec déjà plus de 100 000 timbres vendus, Stampee est la maison d\'enchères de timbres la plus populaire auprès des collectionneurs depuis 1949. Découvrez les collections uniques du lord Stampee et partez à la recherche de vos nouvelles acquisitions.',
        // 'nouveautes'     => $nouveautes,
        // 'coupsCoeur'     => $coupsCoeur,
      ],
      "gabarit-frontend"
    );
  }


  /**
   * Afficher le catalogue complet, des enchères actives ou des enchères archivées
   * 
   * voir pour ne pas faire 3 requetes mais passer quel catalogue en argument
   * 
   */
  public function afficherCatalogue()
  {
    $catalogue = $this->oRequetesSQL->getEncheresTimbres('public');

    (new Vue)->generer(
      "vCatalogue",
      [
        'oUtilConn'          => $this->oUtilConn,
        'titre'              => 'Catalogue',
        'titreHB'            => 'Catalogue des enchères',
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
  public function voirEnchere()
  {
    $enchere = false;
    if (!is_null($this->enchere_id)) {
      $enchere   = $this->oRequetesSQL->getTimbre($this->timbre_id);
    }
    if (!$enchere) throw new Exception("Enchère inexistant.");

    (new Vue)->generer(
      "vEnchere",
      [
        'oUtilConn'    => $this->oUtilConn,
        'titre'        => $enchere['timbre_titre'],
        'timbre'       => $enchere
      ],
      "gabarit-frontend"
    );
  }
}
