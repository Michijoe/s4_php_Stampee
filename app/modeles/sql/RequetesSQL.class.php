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
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_pseudo, utilisateur_courriel,
             utilisateur_renouveler_mdp, utilisateur_profil
      FROM utilisateur ORDER BY utilisateur_id DESC";
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
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_pseudo, utilisateur_courriel,
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
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_pseudo, 
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
      utilisateur_pseudo         = :utilisateur_pseudo,
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
  public function creerCompteMembre($champs)
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
      WHERE utilisateur_id = :utilisateur_id';
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
      DELETE FROM utilisateur WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }




  /* GESTION DES TIMBRES ================= */

  /**
   * Récupération des timbres du catalogue complet ou des timbres actifs ou des timbres archivés ou des timbres d'un membre ou des timbres nouveautés, ou des timbres coups de coeurs
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */
  // public function getTimbres($critere = 'complet')
  // {
  // }


  /**
   * Récupération d'un timbre 
   * @param int    $timbre_id 
   * @param string $mode, admin si pour l'interface d'administration
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */


  /**
   * Ajouter un timbre
   * @param array $champs tableau des champs du timbre 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Modifier un timbre
   * @param array $champs tableau avec les champs à modifier et la clé film_id
   * @return boolean true si modification effectuée, false sinon
   */


  /**
   * Supprimer un timbre
   * @param int $timbre_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



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
