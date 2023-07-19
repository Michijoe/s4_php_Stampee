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
             utilisateur_renouveler_mdp, utilisateur_profil_id
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
             utilisateur_renouveler_mdp, utilisateur_profil_id
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
             utilisateur_courriel, utilisateur_renouveler_mdp, utilisateur_profil_id
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
      utilisateur_profil_id      = :utilisateur_profil_id';
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
      utilisateur_profil_id      = "' . Utilisateur::PROFIL_MEMBRE . '"';
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
      utilisateur_profil_id = :utilisateur_profil_id
      WHERE utilisateur_id = :utilisateur_id
      AND utilisateur_id > 2'; // ne pas modifier les 2 premiers utilisateurs du jeu d'essai
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
      WHERE utilisateur_id = :utilisateur_id
      AND utilisateur_id > 2'; // ne pas modifier les 2 premiers utilisateurs du jeu d'essai
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
      AND utilisateur_id > 2'; // ne pas modifier les 2 premiers utilisateurs du jeu d'essai
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
      AND utilisateur_id > 2'; // ne pas modifier les 2 premiers utilisateurs du jeu d'essai
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }


  /* GESTION DES ENCHERES DE TIMBRES ================= */

  /**
   * Récupération de toutes les enchères de timbres avec les informations des mises :
   * - pour les membres (admin-mise | admin-enchere | membre_owner | membre-miseur)
   * - pour le catalogue des enchères actives (public)
   * - pour le catalogue des enchères archivées
   * - pour le catalogue complet de toutes les enchères
   * - pour la strip des nouveautés (public-nouveaute)
   * - pour la strip des coups de coeur du Lord
   * @param  string $critere = 'admin-mise' |'admin-enchere | 'membre_owner' | 'membre-miseur' | 'public'
   * @return array tableau des lignes produites par la select   
   */

  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  public function getEncheresMises($critere = null, $champs = null)
  {
    $this->debug_to_console("je suis dans getenchere");

    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    $aujourdhui = $oAujourdhui->format('Y-m-d H:i:s');
    $nouveaute = $oAujourdhui->modify('-7 day')->format('Y-m-d H:i:s');

    $this->debug_to_console("je suis apres date aujourdhui");


    $this->sql = "
       SELECT
       e.enchere_id,
       t.timbre_titre,
       t.timbre_statut,
       e.enchere_date_fin,
       e.enchere_date_debut,
       i.image_nom_fichier,
       COUNT(m.mise_id) AS nb_mise,
       TIMESTAMPDIFF(HOUR, '$aujourdhui', e.enchere_date_fin) AS heures_restant,
       (
         SELECT mise_prix
         FROM mise
         WHERE mise_enchere_id = e.enchere_id
         ORDER BY mise_prix DESC
         LIMIT 1
       ) AS mise_actuelle,
       (
         SELECT mise_utilisateur_id
         FROM mise
         WHERE mise_enchere_id = e.enchere_id
         ORDER BY mise_prix DESC
         LIMIT 1
       ) AS mise_actuelle_utilisateur_id";

    $this->debug_to_console("je suis ligne 244");




    // if ((str_contains($critere, 'admin'))) $this->sql .=
    //   ", u.utilisateur_prenom, u.utilisateur_nom";

    $this->debug_to_console("je suis ligne 250");


    if ($critere === 'membre-miseur') $this->sql .= ", MAX(CASE WHEN m.mise_utilisateur_id = " . $_SESSION['oUtilConn']->utilisateur_id . " THEN m.mise_prix END) AS mise_max_utilisateur_actif";

    $this->debug_to_console("je suis ligne 255");


    $this->sql .= " 
       FROM enchere e
       JOIN timbre t ON e.enchere_id = t.timbre_enchere_id
       JOIN image i ON i.image_timbre_id = t.timbre_id
       LEFT JOIN mise m ON e.enchere_id = m.mise_enchere_id";

    $this->debug_to_console("je suis ligne 264");


    if ((str_contains($critere, 'admin'))) $this->sql .= " 
     JOIN utilisateur u ON enchere_utilisateur_id = utilisateur_id";

    if ($critere === 'membre-owner') $this->sql .= " WHERE timbre_utilisateur_id = " . $_SESSION['oUtilConn']->utilisateur_id;


    // catalogue public montre les enchères validées
    if (str_contains($critere, 'public')) {

      $this->sql .= " WHERE timbre_statut = '1'";

      // catalogue public des enchères actives montrent les enchères ayant débutées et pas encore finies
      if ($critere === 'public-actif') $this->sql .= " AND TIMESTAMPDIFF(SECOND, enchere_date_fin, '$aujourdhui') <= 0 AND TIMESTAMPDIFF(SECOND, '$aujourdhui', enchere_date_debut) < 0";

      // catalogue public des nouveautés montrent les enchères ayant débutées depuis moins d'1 semaine
      else if ($critere === 'public-nouveaute') $this->sql .= " AND TIMESTAMPDIFF(SECOND, enchere_date_fin, '$aujourdhui') <= 0 AND TIMESTAMPDIFF(SECOND, '$aujourdhui', enchere_date_debut) < 0 AND TIMESTAMPDIFF(DAY, '$nouveaute', enchere_date_debut) > 0";

      // catalogue public des enchères archivées motnrent les enchères dont la date de fin est dépassée
      else if ($critere === 'public-archive') $this->sql .= " AND TIMESTAMPDIFF(SECOND, enchere_date_fin, '$aujourdhui') > 0";

      // catalogue public des enchères futures montrent les enchères dont la date de début n'a pas commencé
      else if ($critere === 'public-futur') $this->sql .= " AND TIMESTAMPDIFF(SECOND, '$aujourdhui', enchere_date_debut) > 0";

      // catalogue public des enchères coups de coeur montrent les enchères actives Coup de coeur du Lord

      $this->debug_to_console("je suis ligne 292");

      // critères de recherche de l'utilisateur
      if ($champs) {

        $criteres = "";
        if (isset($champs["timbre_pays_id"])) $criteres .= " AND timbre_pays_id = :timbre_pays_id";
        if (isset($champs["timbre_annee_publication"])) {
          switch ($champs["timbre_annee_publication"]) {
            case '1900':
              $criteres .= " AND timbre_annee_publication < :timbre_annee_publication";
              break;
            case '1950':
              $criteres .= " AND timbre_annee_publication < :timbre_annee_publication";
              break;
            case '2000':
              $criteres .= " AND timbre_annee_publication < :timbre_annee_publication";
              break;
            case 'min2000':
              $criteres .= " AND timbre_annee_publication >= :timbre_annee_publication";
              break;
          }
        }
        if (isset($champs["enchere_date_fin"])) $criteres .= " AND enchere_date_fin <= :enchere_date_fin";
        if (isset($champs["prix_mini"])) $criteres .= " AND (
          SELECT mise_prix
          FROM mise
          WHERE mise_enchere_id = e.enchere_id
          ORDER BY mise_prix DESC
          LIMIT 1
        ) >= :prix_mini";
        if (isset($champs["prix_maxi"])) $criteres .= " AND (
          SELECT mise_prix
          FROM mise
          WHERE mise_enchere_id = e.enchere_id
          ORDER BY mise_prix DESC
          LIMIT 1
        ) <= :prix_maxi";
        if (isset($champs["timbre_condition_id"])) $criteres .= " AND timbre_condition_id = :timbre_condition_id";
        if (isset($champs["timbre_couleur_id"])) $criteres .= " AND timbre_couleur_id = :timbre_couleur_id";
        if (isset($champs["timbre_tirage_id"])) $criteres .= " AND timbre_tirage_id = :timbre_tirage_id";

        if (isset($champs["recherche"])) $criteres .= " AND timbre_titre LIKE CONCAT('%',:recherche,'%')";

        $this->debug_to_console("je suis ligne 336");

        $this->sql .= $criteres;
      }
    }

    $this->sql .= " GROUP BY e.enchere_id, t.timbre_titre, t.timbre_statut, e.enchere_date_fin, e.enchere_date_debut, i.image_nom_fichier";

    if ($critere === 'membre-miseur'  || $critere === 'admin-mise') $this->sql .= " 
     HAVING nb_mise > 0";

    $this->debug_to_console("je suis ligne 347");


    // mise pour l'utilisateur actif seulement
    if ($critere === 'membre-miseur') $this->sql .= " AND mise_max_utilisateur_actif > 0";

    $this->sql .= " ORDER BY e.enchere_date_fin ASC;";

    $this->debug_to_console("je suis apres getenchere");

    $this->debug_to_console("je suis ligne 357");

    return $this->getLignes($champs ?? []);
  }


  /**
   * Récupération d'un timbre :
   * @param  int $timbre_id, clé du timbre
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne   
   */
  public function getTimbre($timbre_id)
  {
    $this->sql = "SELECT timbre_id, timbre_titre, timbre_description, timbre_annee_publication, condition_nom, timbre_dimensions, tirage_nom, couleur_nom, timbre_certification, image_nom_fichier, pays_nom FROM timbre INNER JOIN `image` ON timbre_id = image_timbre_id INNER JOIN `condition` ON timbre_condition_id = condition_id INNER JOIN couleur ON timbre_couleur_id = couleur_id INNER JOIN tirage ON timbre_tirage_id = tirage_id INNER JOIN pays ON timbre_pays_id = pays_id WHERE timbre_id = :timbre_id";

    return $this->getLignes(['timbre_id' => $timbre_id], RequetesPDO::UNE_SEULE_LIGNE);
  }


  /**
   * Récupération d'une enchère :
   * @param  int $timbre_id, clé du timbre
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne   
   */
  public function getEnchere($enchere_id)
  {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    $aujourdhui = $oAujourdhui->format('Y-m-d H:i:s');

    $this->sql = "SELECT 
    enchere_id, 
    enchere_date_debut, 
    enchere_date_fin,
    TIMESTAMPDIFF(HOUR, '$aujourdhui', enchere_date_fin) AS heures_restant,
    enchere_utilisateur_id, 
    enchere_prix_reserve, 
    enchere_coup_coeur, 
    (SELECT COUNT(mise_id) FROM mise WHERE mise_enchere_id = enchere_id) AS nb_mise, 
    MAX(mise_prix) AS mise_actuelle,
    mise_utilisateur_id
    FROM 
        Enchere
    LEFT JOIN 
        Mise ON mise_enchere_id = enchere_id
    WHERE 
        enchere_id = :enchere_id
    GROUP BY 
        enchere_id, mise_utilisateur_id
    ORDER BY MAX(mise_prix) DESC
    LIMIT 1;";

    return $this->getLignes(['enchere_id' => $enchere_id], RequetesPDO::UNE_SEULE_LIGNE);
  }


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
      enchere_prix_reserve      = :enchere_prix_reserve,
      enchere_coup_coeur        = :enchere_coup_coeur,
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
      timbre_condition_id        = :timbre_condition_id,
      timbre_pays_id             = :timbre_pays_id,
      timbre_dimensions          = :timbre_dimensions,
      timbre_tirage_id           = :timbre_tirage_id,
      timbre_couleur_id          = :timbre_couleur_id,
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
   * Modifier une enchère
   * @param array $champs tableau des champs de l'enchère de timbre 
   * @return boolean true si modification effectuée, false sinon
   */
  public function modifierEnchere($champs)
  {
    try {
      $this->debuterTransaction();

      $this->sql = '
      UPDATE enchere SET
      enchere_date_debut        = :enchere_date_debut,
      enchere_date_fin          = :enchere_date_fin,
      enchere_prix_reserve      = :enchere_prix_reserve,
      enchere_coup_coeur        = :enchere_coup_coeur,
      WHERE enchere_id          = :enchere_id;
      ';
      $retour = $this->CUDLigne($champs);

      $this->validerTransaction();
      return  $retour;
    } catch (Exception $e) {
      $this->annulerTransaction();
      return $e->getMessage();
    }
  }

  /**
   * Modifier un timbre
   * @param array $champs tableau des champs du timbre 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */
  public function modifierTimbre($champs)
  {
    try {
      $this->debuterTransaction();

      $this->sql = '
      UPDATE timbre SET
      timbre_titre               = :timbre_titre,
      timbre_description         = :timbre_description,
      timbre_annee_publication   = :timbre_annee_publication,
      timbre_condition_id        = :timbre_condition_id,
      timbre_pays_id             = :timbre_pays_id,
      timbre_dimensions          = :timbre_dimensions,
      timbre_tirage_id           = :timbre_tirage_id,
      timbre_couleur_id          = :timbre_couleur_id,
      timbre_certification       = :timbre_certification,
      WHERE timbre_id            = :timbre_id;
      ';
      $retour = $this->CUDLigne($champs);

      $this->validerTransaction();
      return $retour;
    } catch (Exception $e) {
      $this->annulerTransaction();
      return $e->getMessage();
    }
  }

  /**
   * Supprimer un timbre
   * @param int $timbre_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */

  public function supprimerTimbre($timbre_id)
  {
    try {
      $this->debuterTransaction();
      $this->sql = '
        DELETE timbre, enchere, image FROM timbre INNER JOIN enchere ON timbre_enchere_id = enchere_id INNER JOIN image ON image_timbre_id = timbre_id WHERE timbre_id = :timbre_id';
      if (!$this->CUDLigne(['timbre_id' => $timbre_id]))
        throw new Exception("");
      foreach (glob("medias/images/timbre-$timbre_id-*") as $fichier) {
        if (!@unlink($fichier))
          throw new Exception("Erreur dans la suppression du fichier image.");
      }
      $this->validerTransaction();
      return true;
    } catch (Exception $e) {
      $this->annulerTransaction();
      return $e->getMessage();
    }
  }



  /* GESTION DES IMAGES ================= */

  /**
   * Récupération des images d'un timbre
   * @param int $timbre_id
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Modifier l'image d'un timbre
   * @param int $timbre_id
   * @return boolean true si téléversement, false sinon
   */
  public function modifierTimbreImage($timbre_id)
  {
    try {
      $this->debuterTransaction();

      $this->sql = 'INSERT INTO image (image_nom_fichier, image_timbre_id)
                    VALUES (:image_nom_fichier, :timbre_id)
                    ON DUPLICATE KEY UPDATE image_nom_fichier = :image_nom_fichier;';

      // récupération de l'extension du fichier uploadé
      $extension = substr($_FILES['image_nom_fichier']['type'], strpos($_FILES['image_nom_fichier']['type'], "/") + 1);

      $champs['timbre_id'] = $timbre_id;
      $champs['image_nom_fichier'] = "medias/images/timbre-$timbre_id" . "." . $extension;
      $image_id = $this->CUDLigne($champs);

      // suppression de l'ancienne image si elle existe
      foreach (glob("medias/images/timbre-$timbre_id-*") as $fichier) {
        if (!@unlink($fichier))
          throw new Exception("Erreur dans la suppression de l'ancienne image.");
      }

      // ajout de la nouvelle image
      if (!@move_uploaded_file($_FILES['image_nom_fichier']['tmp_name'], $champs['image_nom_fichier'])) throw new Exception("Le stockage du fichier image a échoué.");

      $this->validerTransaction();
      return  $image_id;
    } catch (Exception $e) {
      $this->annulerTransaction();
      return $e->getMessage();
    }
  }

  /**
   * Supprimer une image
   * @param int $image_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



  /* GESTION DES MISES ================= */

  /**
   * Ajouter une mise
   * @param array $champs tableau des champs d'une mise 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */

  public function miser($champs)
  {
    // $miseActuelle = $this->getMiseActuelle($champs['mise_enchere_id']);
    // if ($miseActuelle > $champs['mise_prix'])
    //   throw new Exception("Veuillez entrer une mise supérieure au prix actuel de l'enchère.");
    $this->sql = '
      INSERT INTO mise SET
      mise_prix            = :mise_prix,
      mise_utilisateur_id  = :mise_utilisateur_id,
      mise_enchere_id      = :mise_enchere_id';
    return $this->CUDLigne($champs);
  }



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



  /* GESTION DES LIKES ================= */

  /**
   * Récupération des likes d'une enchere
   * @param int $enchere_id
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */


  /**
   * Ajouter un like
   * @param array $champs tableau des champs d'un like 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */


  /**
   * Supprimer un like
   * @param int $enchere_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



  /* GESTION DES COMMENTAIRES ================= */

  /**
   * Récupération des commentaires d'une enchere
   * @param int $enchere_id
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


  /* GESTION DES PROFILS 
     ================== */

  /**
   * Récupération des profils pour l'interface admin
   * @return array tableau des lignes produites par la select   
   */
  public function getProfils()
  {
    $this->sql = "
      SELECT profil_id, profil_nom, nb_utilisateurs
      FROM profil
      LEFT JOIN (SELECT COUNT(*) AS nb_utilisateurs, utilisateur_profil_id FROM utilisateur GROUP BY utilisateur_profil_id) AS FG ON utilisateur_profil_id = profil_id
      ORDER BY profil_id ASC";
    return $this->getLignes();
  }

  /**
   * Récupération d'un profil
   * @param int $profil_id, clé du genre  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getProfil($profil_id)
  {
    $this->sql = "
      SELECT profil_id, profil_nom
      FROM profil
      WHERE profil_id = :profil_id";
    return $this->getLignes(['profil_id' => $profil_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un profil
   * @param array $champs tableau des champs du profil 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */

  /**
   * Supprimer un profil
   * @param int $profil_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



  /* GESTION DES CONDITIONS 
     ================== */

  /**
   * Récupération des conditions pour l'interface admin
   * @return array tableau des lignes produites par la select   
   */
  public function getConditions()
  {
    $this->sql = "
      SELECT condition_id, condition_nom, nb_timbres
      FROM `condition`
      LEFT JOIN (SELECT COUNT(*) AS nb_timbres, timbre_condition_id FROM timbre GROUP BY timbre_condition_id) AS FG ON timbre_condition_id = condition_id
      ORDER BY condition_id ASC";
    return $this->getLignes();
  }

  /**
   * Récupération d'une condition
   * @param int $condition_id, clé du genre  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getCondition($condition_id)
  {
    $this->sql = "
      SELECT condition_id, condition_nom
      FROM `condition`
      WHERE condition_id = :condition_id";
    return $this->getLignes(['condition_id' => $condition_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter une condition
   * @param array $champs tableau des champs du pays 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */

  /**
   * Modifier une condition
   * @param array $champs tableau des champs du genre
   * @return boolean|string true si modifié, message d'erreur sinon
   */

  /**
   * Supprimer une condition
   * @param int $condition_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */



  /* GESTION DES COULEURS 
     ================== */

  /**
   * Récupération des couleurs pour l'interface admin
   * @return array tableau des lignes produites par la select   
   */
  public function getCouleurs()
  {
    $this->sql = "
      SELECT couleur_id, couleur_nom, nb_timbres
      FROM couleur
      LEFT JOIN (SELECT COUNT(*) AS nb_timbres, timbre_couleur_id FROM timbre GROUP BY timbre_couleur_id) AS FG ON timbre_couleur_id = couleur_id
      ORDER BY couleur_id ASC";
    return $this->getLignes();
  }

  /**
   * Récupération d'une couleur
   * @param int $couleur_id, clé du genre  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getCouleur($couleur_id)
  {
    $this->sql = "
      SELECT couleur_id, couleur_nom
      FROM couleur
      WHERE couleur_id = :couleur_id";
    return $this->getLignes(['couleur_id' => $couleur_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter une couleur
   * @param array $champs tableau des champs du pays 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */

  /**
   * Modifier une couleur
   * @param array $champs tableau des champs du genre
   * @return boolean|string true si modifié, message d'erreur sinon
   */

  /**
   * Supprimer une couleur
   * @param int $condition_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */

  /* GESTION DES TIRAGES 
     ================== */

  /**
   * Récupération des tirages pour l'interface admin
   * @return array tableau des lignes produites par la select   
   */
  public function getTirages()
  {
    $this->sql = "
      SELECT tirage_id, tirage_nom, nb_timbres
      FROM tirage
      LEFT JOIN (SELECT COUNT(*) AS nb_timbres, timbre_tirage_id FROM timbre GROUP BY timbre_tirage_id) AS FG ON timbre_tirage_id = tirage_id
      ORDER BY tirage_id ASC";
    return $this->getLignes();
  }

  /**
   * Récupération d'un tirage
   * @param int $tirage_id, clé du genre  
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getTirage($tirage_id)
  {
    $this->sql = "
      SELECT tirage_id, tirage_nom
      FROM tirage
      WHERE tirage_id = :tirage_id";
    return $this->getLignes(['tirage_id' => $tirage_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un tirage
   * @param array $champs tableau des champs du pays 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */

  /**
   * Modifier un tirage
   * @param array $champs tableau des champs du genre
   * @return boolean|string true si modifié, message d'erreur sinon
   */

  /**
   * Supprimer un tirage
   * @param int $condition_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */
}
