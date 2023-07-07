<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO
{

  /* GESTION DES UTILISATEURS 
     ======================== */

  /**
   * Récupération des utilisateurs
   * @return array tableau d'objets Utilisateur
   */
  public function getUtilisateurs()
  {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel,
             utilisateur_renouveler_mdp, utilisateur_profil
      FROM utilisateur ORDER BY utilisateur_id ASC";
    return $this->getLignes();
  }

  /**
   * Récupération d'un utilisateur
   * @param int $utilisateur_id, clé du utilisateur  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getUtilisateur($utilisateur_id)
  {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel,
             utilisateur_renouveler_mdp, utilisateur_profil
      FROM utilisateur
      WHERE utilisateur_id = :utilisateur_id";
    return $this->getLignes(['utilisateur_id' => $utilisateur_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Contrôler si adresse courriel non déjà utilisée par un autre utilisateur que utilisateur_id
   * @param array $champs tableau utilisateur_courriel et utilisateur_id (0 si dans toute la table)
   * @return array|false utilisateur avec ce courriel, false si courriel disponible
   */
  public function controlerCourriel($champs)
  {
    $this->sql = 'SELECT utilisateur_id FROM utilisateur
                  WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_id != :utilisateur_id';
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Connecter un utilisateur
   * @param array $champs, tableau avec les champs utilisateur_courriel et utilisateur_mdp  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function connecter($champs)
  {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom,
             utilisateur_courriel, utilisateur_renouveler_mdp, utilisateur_profil
      FROM utilisateur
      WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_mdp = SHA2(:utilisateur_mdp, 512)";
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */
  public function ajouterUtilisateur($champs)
  {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => 0]
    );
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      INSERT INTO utilisateur SET
      utilisateur_nom            = :utilisateur_nom,
      utilisateur_prenom         = :utilisateur_prenom,
      utilisateur_courriel       = :utilisateur_courriel,
      utilisateur_mdp            = SHA2(:utilisateur_mdp, 512),
      utilisateur_renouveler_mdp = "oui",
      utilisateur_profil         = :utilisateur_profil';
    return $this->CUDLigne($champs);
  }

  /**
   * Créer un compte utilisateur dans le frontend
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */
  public function creerCompteUtilisateur($champs)
  {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => 0]
    );
    if ($utilisateur !== false)
      return ['utilisateur_courriel' => Utilisateur::ERR_COURRIEL_EXISTANT];
    unset($champs['nouveau_mdp_bis']);
    $this->sql = '
      INSERT INTO utilisateur SET
      utilisateur_nom            = :utilisateur_nom,
      utilisateur_prenom         = :utilisateur_prenom,
      utilisateur_courriel       = :utilisateur_courriel,
      utilisateur_mdp            = SHA2(:nouveau_mdp, 512),
      utilisateur_renouveler_mdp = "non",
      utilisateur_profil         = "' . Utilisateur::PROFIL_MEMBRE . '"';
    return $this->CUDLigne($champs);
  }


  /**
   * Modifier un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean|string true si modifié, message d'erreur sinon
   */
  public function modifierUtilisateur($champs)
  {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => $champs['utilisateur_id']]
    );
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      UPDATE utilisateur SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_profil   = :utilisateur_profil
      WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier le mot de passe d'un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean true si modifié, false sinon
   */
  public function modifierUtilisateurMdpGenere($champs)
  {
    $this->sql = '
      UPDATE utilisateur SET
      utilisateur_mdp            = SHA2(:utilisateur_mdp, 512),
      utilisateur_renouveler_mdp = "oui"
      WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier le mot de passe saisi d'un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean true si modifié, false sinon
   */
  public function modifierUtilisateurMdpSaisi($champs)
  {
    $this->sql = '
      UPDATE utilisateur SET
      utilisateur_mdp            = SHA2(:utilisateur_mdp, 512), 
      utilisateur_renouveler_mdp = "non"
      WHERE utilisateur_id = :utilisateur_id
      AND utilisateur_id > 1'; // ne pas modifier le premier utilisateur admin du jeu d'essai
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer un utilisateur
   * @param int $utilisateur_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */
  public function supprimerUtilisateur($utilisateur_id)
  {
    $this->sql = '
      DELETE FROM utilisateur WHERE utilisateur_id = :utilisateur_id
      AND utilisateur_id > 1'; // ne pas modifier le premier utilisateur admin du jeu d'essai
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }

  /* GESTION DES ENCHERES DE TIMBRES ================= */

  /**
   * Récupération de toutes les enchères de timbres :
   * - pour l'administrateur
   * - pour un utilisateur dans son espace membre
   * - du catalogue complet
   * - catalogue des enchères actives
   * - catalogue des enchères archivées
   * - des nouveautés
   * - des coups de coeur du Lord
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */
  public function getEncheresTimbres($critere = 'public')
  {
    $oAujourdhui = new DateTime();
    $dateDerniereChance = $oAujourdhui->modify('-1 day');
    $dateNouveaute = $oAujourdhui->modify('-7 day');


    // SELECT POUR TOUS
    $this->sql = "SELECT timbre_titre, image_nom_fichier, enchere_date_fin, enchere_id";

    // SELECT POUR LES MEMBRES ET ADMINS
    if (str_contains($critere, 'membre')) {
      $this->sql .= ", timbre_statut, enchere_date_debut";

      // alimenter les colonnes id et utilisateur pour l'administrateur
      if ($critere === 'membre_admin') {
        $this->sql .= ", utilisateur_nom, utilisateur_prenom";
      }

      // SELECT POUR LE FRONTEND
      // alimenter les colonnes mises, temps et prix pour le frontend
    } else if (str_contains($critere, 'public')) {
      $this->sql .= ", COUNT(mise_id) AS nb_mise";
    }


    // FROM ET JOIN POUR TOUS
    $this->sql .= " FROM timbre INNER JOIN enchere ON timbre_enchere_id = enchere_id INNER JOIN image ON timbre_id = image_timbre_id";


    // FROM ET JOIN POUR MEMBRES ET ADMIN
    // alimenter les colonnes id et utilisateur pour l'administrateur
    if ($critere === 'membre_admin') {
      $this->sql .= " INNER JOIN utilisateur ON enchere_utilisateur_id = utilisateur_id";
    }

    // restriction aux encheres d'un utilisateur
    else if ($critere === 'membre_owner') {
      $this->sql .= " WHERE utilisateur_id = " . $_SESSION['oUtilConn']->utilisateur_id;
    }

    // frontend ajout mise 
    else if (str_contains($critere, 'public')) {
      $this->sql .= " LEFT JOIN mise ON mise_enchere_id = enchere_id WHERE timbre_statut = '1' GROUP BY enchere_id, timbre_titre, image_nom_fichier, enchere_date_fin ORDER BY enchere_date_fin ASC";
    }

    return $this->getLignes();
  }




  /**
   * Récupération d'une enchère de timbre 
   * @param int    $timbre_id 
   * @param string $mode, admin si pour l'interface d'administration
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */


  /**
   * Ajouter une enchère
   * @param array $champs tableau des champs de l'enchère de timbre 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */
  public function ajouterEnchere($champs)
  {
    try {
      $this->debuterTransaction();

      $this->sql = '
      INSERT INTO enchere SET
      enchere_date_debut        = :enchere_date_debut,
      enchere_date_fin          = :enchere_date_fin,
      enchere_prix_plancher     = :enchere_prix_plancher,
      enchere_coups_coeur_lord  = :enchere_coups_coeur_lord,
      enchere_utilisateur_id    = :enchere_utilisateur_id;
      ';

      $enchere_id = $this->CUDLigne($champs);

      $this->validerTransaction();
      return  $enchere_id;
    } catch (Exception $e) {
      $this->annulerTransaction();
      return $e->getMessage();
    }
  }

  /**
   * Ajouter un timbre
   * @param array $champs tableau des champs du timbre 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */
  public function ajouterTimbre($champs)
  {
    try {
      $this->debuterTransaction();

      $this->sql = '
      INSERT INTO timbre SET
      timbre_titre               = :timbre_titre,
      timbre_description         = :timbre_description,
      timbre_annee_publication   = :timbre_annee_publication,
      timbre_condition           = :timbre_condition,
      timbre_pays_id             = :timbre_pays_id,
      timbre_dimensions          = :timbre_dimensions,
      timbre_tirage              = :timbre_tirage,
      timbre_couleur             = :timbre_couleur,
      timbre_certification       = :timbre_certification,
      timbre_utilisateur_id      = :timbre_utilisateur_id,
      timbre_enchere_id          = :timbre_enchere_id;
      ';
      $timbre_id = $this->CUDLigne($champs);

      $this->validerTransaction();
      return  $timbre_id;
    } catch (Exception $e) {
      $this->annulerTransaction();
      return $e->getMessage();
    }
  }

  /**
   * Modifier l'image d'un timbre
   * @param int $timbre_id
   * @return boolean true si téléversement, false sinon
   */
  public function modifierTimbreImage($timbre_id)
  {
    if ($_FILES['image_nom_fichier']['tmp_name'] !== "") {
      $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
      // récupération de l'extension du fichier uploadé
      $extension = substr($_FILES['image_nom_fichier']['type'], strpos($_FILES['image_nom_fichier']['type'], "/") + 1);
      // vérifier si l'extension est autorisée
      if (in_array($extension, $allowTypes)) {
        $this->sql = 'INSERT INTO image SET image_nom_fichier = :image_nom_fichier, image_timbre_id = :timbre_id';
        $champs['timbre_id']    = $timbre_id;
        $champs['image_nom_fichier'] = "medias/images/timbre-$timbre_id-" . ".jpg";
        $this->CUDLigne($champs);
        if (!@move_uploaded_file($_FILES['image_nom_fichier']['tmp_name'], $champs['image_nom_fichier']))
          throw new Exception("Le stockage du fichier image a échoué.");
        return true;
      } else {
        throw new Exception("Seulement les extensions WEBP, JPG, JPEG, PNG et GIF sont autorisées.");
      }
    }
    return false;
  }


  /**
   * Modifier un timbre
   * @param array $champs tableau avec les champs à modifier et la clé timbre_id
   * @return boolean true si modification effectuée, false sinon
   */


  /**
   * Supprimer un timbre
   * @param int $timbre_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



  /* GESTION DES PAYS 
     ================== */

  /**
   * Récupération des pays pour l'interface admin
   * @return array tableau des lignes produites par la select   
   */
  public function getPays()
  {
    $this->sql = "
      SELECT pays_id, pays_nom, nb_timbres
      FROM pays
      LEFT JOIN (SELECT COUNT(*) AS nb_timbres, timbre_pays_id FROM timbre GROUP BY timbre_pays_id) AS FG
          ON timbre_pays_id = pays_id
      ORDER BY pays_id ASC";
    return $this->getLignes();
  }

  /**
   * Récupération d'un pays
   * @param int $pays_id, clé du genre  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getUnPays($pays_id)
  {
    $this->sql = "
      SELECT pays_id, pays_nom
      FROM pays
      WHERE pays_id = :pays_id";
    return $this->getLignes(['pays_id' => $pays_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un pays
   * @param array $champs tableau des champs du pays 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */
  public function ajouterPays($champs)
  {
    $this->sql = '
      INSERT INTO pays SET
      pays_id  = :pays_id,
      pays_nom = :pays_nom
      ON DUPLICATE KEY UPDATE pays_id = :pays_id';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier un pays
   * @param array $champs tableau des champs du genre
   * @return boolean|string true si modifié, message d'erreur sinon
   */
  public function modifierPays($champs)
  {
    $this->sql = '
      UPDATE pays SET
      pays_nom = :pays_nom
      WHERE pays_id = :pays_id';
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer un pays
   * @param int $pays_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */
  public function supprimerPays($pays_id)
  {
    $this->sql = '
      DELETE FROM pays WHERE pays_id = :pays_id
      AND pays_id NOT IN (SELECT DISTINCT timbre_pays_id FROM timbre)';
    return $this->CUDLigne(['pays_id' => $pays_id]);
  }



  /* GESTION DES MISES ================= */
  /**
   * Récupération des mises d'un timbre
   * @param int $timbre_id
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Ajouter une mise
   * @param array $champs tableau des champs d'une mise 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Modifier une mise
   * @param array $champs tableau des champs d'une mise
   * @return boolean|string true si modifié, message d'erreur sinon
   */


  /**
   * Supprimer une mise
   * @param int $mise_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */





  /* GESTION DES IMAGES ================= */

  /**
   * Récupération des images d'un timbre
   * @param int $timbre_id
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Ajouter une image
   * @param array $champs tableau des champs d'une image 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Modifier une image
   * @param array $champs tableau des champs d'une image
   * @return boolean|string true si modifié, message d'erreur sinon
   */


  /**
   * Supprimer une image
   * @param int $image_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */





  /* GESTION DES LIKES ================= */

  /**
   * Récupération des likes d'un timbre
   * @param int $timbre_id
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Ajouter un like
   * @param array $champs tableau des champs d'un like 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Supprimer un like
   * @param int $image_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



  /* GESTION DES COUPS DE COEUR DU LORD ================= */

  /**
   * Récupération des coups de coeur du Lord
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Ajouter un coup de coeur
   * @param array $champs tableau des champs d'un coup de coeur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Supprimer un coup de coeur
   * @param int $image_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */




  /* GESTION DES COMMENTAIRES ================= */

  /**
   * Récupération des commentaires d'un timbre
   * @param int $timbre_id
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Ajouter un commentaire
   * @param array $champs tableau des champs d'un commentaire 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Modifier un commentaire
   * @param array $champs tableau des champs d'un commentaire
   * @return boolean|string true si modifié, message d'erreur sinon
   */


  /**
   * Supprimer un commentaire
   * @param int $commentaire_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */
}
