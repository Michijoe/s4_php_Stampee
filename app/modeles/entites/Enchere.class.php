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
    protected $enchere_prix_reserve;
    protected $enchere_coup_coeur;

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
            $this->erreurs['enchere_date_debut'] = "Doit être supérieur à la date et à l'heure du jour.";
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
            $this->erreurs['enchere_date_fin'] = "Doit être supérieur à la date et à l'heure du début de l'enchère.";
        }
        $this->enchere_date_fin = $enchere_date_fin;
        return $this;
    }

    /**
     * Mutateur de la propriété enchere_prix_reserve 
     * @param int $enchere_prix_reserve
     * @return $this
     */
    public function setEnchere_prix_reserve($enchere_prix_reserve)
    {
        unset($this->erreurs['enchere_prix_reserve']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $enchere_prix_reserve)) {
            $this->erreurs['enchere_prix_reserve'] = 'Entrez un nombre entier.';
        }
        $this->enchere_prix_reserve = $enchere_prix_reserve;
        return $this;
    }

    /**
     * Mutateur de la propriété enchere_coup_coeur
     * @param int $enchere_coup_coeur
     * @return $this
     */
    public function setEnchere_coup_coeur($enchere_coup_coeur)
    {
        unset($this->erreurs['enchere_coup_coeur']);
        if (
            $enchere_coup_coeur != 'Oui' &&
            $enchere_coup_coeur != 'Non'
        ) {
            $this->erreurs['enchere_coup_coeur'] = 'Entrée incorrecte.';
        }
        $this->enchere_coup_coeur = $enchere_coup_coeur;
        return $this;
    }
}
