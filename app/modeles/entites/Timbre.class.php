<?php

/**
 * Classe de l'entité Timbre
 *
 */
class Timbre extends Entite
{
  protected $timbre_id;
  protected $timbre_titre;
  protected $timbre_description;
  protected $timbre_annee_publication;
  protected $timbre_condition;
  protected $timbre_pays_id;
  protected $timbre_dimensions;
  protected $timbre_tirage;
  protected $timbre_couleur;
  protected $timbre_certification;
  protected $timbre_utilisateur_id;
  protected $timbre_statut;
  protected $timbre_image;

  const ANNEE_PREMIER_TIMBRE = '1847';

  const TIRAGE_MAX100   = 0;
  const TIRAGE_MAX500   = 1;
  const TIRAGE_MAX1000  = 2;
  const TIRAGE_MAX5000  = 3;

  const CONDITION_ENDOMMAGE    = 0;
  const CONDITION_MOYENNE      = 1;
  const CONDITION_BONNE        = 2;
  const CONDITION_EXCELLENTE   = 3;
  const CONDITION_PARFAITE     = 4;

  const COULEUR_ROUGE   = 0;
  const COULEUR_JAUNE   = 1;
  const COULEUR_BLEU    = 2;
  const COULEUR_VERT    = 3;
  const COULEUR_BLANC   = 4;
  const COULEUR_NOIR    = 5;

  const STATUT_INVISIBLE = 0;
  const STATUT_VISIBLE   = 1;
  const STATUT_ARCHIVE   = 2;

  /**
   * Mutateur de la propriété timbre_id 
   * @param int $timbre_id
   * @return $this
   */
  public function setTimbre_id($timbre_id)
  {
    unset($this->erreurs['timbre_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_id)) {
      $this->erreurs['timbre_id'] = 'Numéro de timbre incorrect.';
    }
    $this->timbre_id = $timbre_id;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_titre 
   * @param string $timbre_titre
   * @return $this
   */
  public function setTimbre_titre($timbre_titre)
  {
    unset($this->erreurs['timbre_titre']);
    $timbre_titre = trim($timbre_titre);
    $regExp = '/^.+$/';
    if (!preg_match($regExp, $timbre_titre)) {
      $this->erreurs['timbre_titre'] = 'Au moins un caractère.';
    }
    $this->timbre_titre = mb_strtoupper($timbre_titre);
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_description
   * @param string $timbre_description
   * @return $this
   */
  public function setTimbre_description($timbre_description)
  {
    $timbre_description = trim($timbre_description);
    $this->timbre_description = $timbre_description;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_annee_publication 
   * @param int $timbre_annee_publication
   * @return $this
   */
  public function setTimbre_annee_publication($timbre_annee_publication)
  {
    unset($this->erreurs['timbre_annee_publication']);
    if (
      !preg_match('/^\d+$/', $timbre_annee_publication) ||
      $timbre_annee_publication < self::ANNEE_PREMIER_TIMBRE  ||
      $timbre_annee_publication > date("Y")
    ) {
      $this->erreurs['timbre_annee_publication'] = "Entre " . self::ANNEE_PREMIER_TIMBRE . " et l'année en cours.";
    }
    $this->timbre_annee_publication = $timbre_annee_publication;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_condition
   * @param int $timbre_condition
   * @return $this
   */
  public function setTimbre_condition($timbre_condition)
  {
    unset($this->erreurs['timbre_condition']);
    if (
      $timbre_condition != Timbre::CONDITION_BONNE &&
      $timbre_condition != Timbre::CONDITION_ENDOMMAGE &&
      $timbre_condition != Timbre::CONDITION_EXCELLENTE &&
      $timbre_condition != Timbre::CONDITION_MOYENNE &&
      $timbre_condition != Timbre::CONDITION_PARFAITE
    ) {
      $this->erreurs['timbre_condition'] = 'Condition incorrecte.';
    }
    $this->timbre_condition = $timbre_condition;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_pays_id 
   * @param int $timbre_pays_id
   * @return $this
   */
  public function setTimbre_pays_id($timbre_pays_id)
  {
    unset($this->erreurs['timbre_pays_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_pays_id)) {
      $this->erreurs['timbre_pays_id'] = 'Numéro de pays incorrect.';
    }
    $this->timbre_pays_id = $timbre_pays_id;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_dimensions
   * @param string $timbre_dimensions
   * @return $this
   */
  public function setTimbre_dimensions($timbre_dimensions)
  {
    unset($this->erreurs['timbre_dimensions']);
    $timbre_dimensions = trim($timbre_dimensions);
    $regExp = '/^\S+(\s+\S+){1,}$/';
    if (!preg_match($regExp, $timbre_dimensions)) {
      $this->erreurs['timbre_dimensions'] = 'Au moins 2 mots.';
    }
    $this->timbre_dimensions = $timbre_dimensions;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_tirage
   * @param int $timbre_tirage
   * @return $this
   */
  public function setTimbre_tirage($timbre_tirage)
  {
    unset($this->erreurs['timbre_tirage']);
    if (
      $timbre_tirage != Timbre::TIRAGE_MAX100 &&
      $timbre_tirage != Timbre::TIRAGE_MAX1000 &&
      $timbre_tirage != Timbre::TIRAGE_MAX500 &&
      $timbre_tirage != Timbre::TIRAGE_MAX5000
    ) {
      $this->erreurs['timbre_tirage'] = 'Tirage incorrect.';
    }
    $this->timbre_tirage = $timbre_tirage;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_couleur_dominante
   * @param int $timbre_couleur_dominante
   * @return $this
   */
  public function setTimbre_couleur($timbre_couleur)
  {
    unset($this->erreurs['timbre_couleur_dominante']);
    if (
      $timbre_couleur != Timbre::COULEUR_BLANC &&
      $timbre_couleur != Timbre::COULEUR_BLEU &&
      $timbre_couleur != Timbre::COULEUR_JAUNE &&
      $timbre_couleur != Timbre::COULEUR_NOIR &&
      $timbre_couleur != Timbre::COULEUR_ROUGE &&
      $timbre_couleur != Timbre::COULEUR_VERT
    ) {
      $this->erreurs['timbre_couleur'] = 'Couleur incorrecte.';
    }
    $this->timbre_couleur = $timbre_couleur;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_certification
   * @param string $timbre_certification
   * @return $this
   */
  public function setTimbre_certification($timbre_certification)
  {
    $this->timbre_certification = $timbre_certification;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_debut_enchere
   * @param string $timbre_debut_enchere
   * @return $this
   */
  public function setTimbre_debut_enchere($timbre_debut_enchere)
  {
    unset($this->erreurs['timbre_debut_enchere']);
    if (
      !preg_match('/^\d+$/', $timbre_debut_enchere) ||
      $timbre_debut_enchere < date("Y")
    ) {
      $this->erreurs['timbre_debut_enchere'] = "Supérieur à la date du jour.";
    }
    $this->timbre_debut_enchere = $timbre_debut_enchere;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_fin_enchere
   * @param string $timbre_fin_enchere
   * @return $this
   */
  public function setTimbre_fin_enchere($timbre_fin_enchere)
  {
    unset($this->erreurs['timbre_fin_enchere']);
    if (
      !preg_match('/^\d+$/', $timbre_fin_enchere) ||
      $timbre_fin_enchere < $this->timbre_debut_enchere + 7
    ) {
      $this->erreurs['timbre_fin_enchere'] = "Supérieur à la date de début + 7 jours.";
    }
    $this->timbre_fin_enchere = $timbre_fin_enchere;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_utilisateur_id 
   * @param int $timbre_utilisateur_id
   * @return $this
   */
  public function setTimbre_utilisateur_id($timbre_utilisateur_id)
  {
    unset($this->erreurs['timbre_utilisateur_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_utilisateur_id)) {
      $this->erreurs['timbre_utilisateur_id'] = 'Numéro d\'utilisateur incorrect.';
    }
    $this->timbre_utilisateur_id = $timbre_utilisateur_id;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_statut
   * @param int $timbre_statut
   * @return $this
   */
  public function setTimbre_statut($timbre_statut)
  {
    unset($this->erreurs['timbre_statut']);
    if (
      $timbre_statut != Timbre::STATUT_INVISIBLE &&
      $timbre_statut != Timbre::STATUT_VISIBLE   &&
      $timbre_statut != Timbre::STATUT_ARCHIVE
    ) {
      $this->erreurs['timbre_statut'] = 'Statut incorrect.';
    }
    $this->timbre_statut = $timbre_statut;
    return $this;
  }
}
