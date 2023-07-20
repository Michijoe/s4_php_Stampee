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
  protected $timbre_condition_id;
  protected $timbre_pays_id;
  protected $timbre_dimensions;
  protected $timbre_tirage_id;
  protected $timbre_couleur_id;
  protected $timbre_certification;
  protected $timbre_statut;
  protected $timbre_enchere_id;

  const ANNEE_PREMIER_TIMBRE = '1847';

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
    $regExp = '/^.{4,}$/';
    if (!preg_match($regExp, $timbre_titre)) {
      $this->erreurs['timbre_titre'] = 'Au moins 4 caractères.';
    }
    $this->timbre_titre = $timbre_titre;
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
  public function setTimbre_condition_id($timbre_condition_id)
  {
    unset($this->erreurs['timbre_condition_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_condition_id)) {
      $this->erreurs['timbre_condition_id'] = 'Veuillez sélectionner une condition dans la liste.';
    }
    $this->timbre_condition_id = $timbre_condition_id;
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
      $this->erreurs['timbre_pays_id'] = 'Veuillez sélectionner un pays dans la liste.';
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
    $timbre_dimensions = trim($timbre_dimensions);
    $this->timbre_dimensions = $timbre_dimensions;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_tirage
   * @param int $timbre_tirage
   * @return $this
   */
  public function setTimbre_tirage_id($timbre_tirage_id)
  {
    unset($this->erreurs['timbre_tirage_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_tirage_id)) {
      $this->erreurs['timbre_tirage_id'] = 'Veuillez sélectionner un tirage dans la liste.';
    }
    $this->timbre_tirage_id = $timbre_tirage_id;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_couleur_dominante
   * @param int $timbre_couleur_dominante
   * @return $this
   */
  public function setTimbre_couleur_id($timbre_couleur_id)
  {
    unset($this->erreurs['timbre_couleur_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_couleur_id)) {
      $this->erreurs['timbre_couleur_id'] = 'Veuillez sélectionner une couleur dans la liste.';
    }
    $this->timbre_couleur_id = $timbre_couleur_id;
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

  /**
   * Mutateur de la propriété timbre_enchere_id 
   * @param int $timbre_enchere_id
   * @return $this
   */
  public function setTimbre_enchere_id($timbre_enchere_id)
  {
    unset($this->erreurs['timbre_enchere_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_enchere_id)) {
      $this->erreurs['timbre_enchere_id'] = 'Numéro incorrect.';
    }
    $this->timbre_enchere_id = $timbre_enchere_id;
    return $this;
  }
}
