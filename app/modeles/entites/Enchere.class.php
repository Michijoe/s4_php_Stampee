<?php

/**
 * Classe de l'entité Timbre
 *
 */
class Enchere extends Entite
{
    protected $enchere_id;
    protected $enchere_date_debut;
    protected $enchere_date_fin;
    protected $enchere_prix_plancher;
    protected $enchere_coups_coeur_lord;

    /**
     * Mutateur de la propriété enchere_id 
     * @param int $enchere_id
     * @return $this
     */
    public function setEnchere_id($enchere_id)
    {
        unset($this->erreurs['enchere_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $enchere_id)) {
            $this->erreurs['enchere_id'] = 'Numéro d\'enchère incorrect.';
        }
        $this->enchere_id = $enchere_id;
        return $this;
    }


    /**
     * Mutateur de la propriété enchere_date_debut
     * @param string $enchere_date_debut
     * @return $this
     */
    public function setEnchere_date_debut($enchere_date_debut)
    {
        unset($this->erreurs['enchere_date_debut']);
        if (
            $enchere_date_debut < date("Y")
        ) {
            $this->erreurs['enchere_date_debut'] = "Doit être supérieur à la date du jour.";
        }
        $this->enchere_date_debut = $enchere_date_debut;
        return $this;
    }

    /**
     * Mutateur de la propriété enchere_date_fin
     * @param string $enchere_date_fin
     * @return $this
     */
    public function setEnchere_date_fin($enchere_date_fin)
    {
        unset($this->erreurs['enchere_date_fin']);
        if (
            $enchere_date_fin < $this->enchere_date_debut
        ) {
            $this->erreurs['enchere_date_fin'] = "Doit être supérieur à la date de début.";
        }
        $this->enchere_date_fin = $enchere_date_fin;
        return $this;
    }

    /**
     * Mutateur de la propriété enchere_prix_plancher 
     * @param int $enchere_prix_plancher
     * @return $this
     */
    public function setEnchere_prix_plancher($enchere_prix_plancher)
    {
        unset($this->erreurs['enchere_prix_plancher']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $enchere_prix_plancher)) {
            $this->erreurs['enchere_prix_plancher'] = 'Entrez un nombre entier.';
        }
        $this->enchere_prix_plancher = $enchere_prix_plancher;
        return $this;
    }

    /**
     * Mutateur de la propriété enchere_coups_coeur_lord
     * @param int $enchere_coups_coeur_lord
     * @return $this
     */
    public function setEnchere_coups_coeur_lord($enchere_coups_coeur_lord)
    {
        unset($this->erreurs['enchere_coups_coeur_lord']);
        if (
            $enchere_coups_coeur_lord != 'Oui' &&
            $enchere_coups_coeur_lord != 'Non'
        ) {
            $this->erreurs['enchere_coups_coeur_lord'] = 'Entrée incorrecte.';
        }
        $this->enchere_coups_coeur_lord = $enchere_coups_coeur_lord;
        return $this;
    }
}
