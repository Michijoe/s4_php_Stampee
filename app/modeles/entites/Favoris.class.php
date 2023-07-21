<?php

/**
 * Classe de l'entité Favoris
 *
 */
class Favoris extends Entite
{
    protected $favoris_id = 0;
    protected $favoris_utilisateur_id;
    protected $favoris_enchere_id;
    protected $favoris_etat;

    // Getters explicites nécessaires au moteur de templates TWIG
    public function getFavoris_id()
    {
        return $this->favoris_id;
    }
    public function getFavoris_utilisateur_id()
    {
        return $this->favoris_utilisateur_id;
    }
    public function getFavoris_enchere_id()
    {
        return $this->favoris_enchere_id;
    }
    public function getFavoris_etat()
    {
        return $this->favoris_etat;
    }


    /**
     * Mutateur de la propriété favoris_id 
     * @param int $favoris_id
     * @return $this
     */
    public function setFavoris_id($favoris_id)
    {
        unset($this->erreurs['favoris_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $favoris_id)) {
            $this->erreurs['favoris_id'] = 'Numéro incorrect.';
        }
        $this->favoris_id = $favoris_id;
        return $this;
    }


    /**
     * Mutateur de la propriété favoris_utilisateur_id
     * @param string $favoris_utilisateur_id
     * @return $this
     */
    public function setFavoris_utilisateur_id($favoris_utilisateur_id)
    {
        unset($this->erreurs['favoris_utilisateur_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $favoris_utilisateur_id)) {
            $this->erreurs['favoris_utilisateur_id'] = 'Numéro incorrect.';
        }
        $this->favoris_utilisateur_id = $favoris_utilisateur_id;
        return $this;
    }


    /**
     * Mutateur de la propriété favoris_id_enchere_id
     * @param string $favoris_id_enchere_id
     * @return $this
     */
    public function setFavoris_id_enchere_id($favoris_id_enchere_id)
    {
        unset($this->erreurs['favoris_id_enchere_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $favoris_id_enchere_id)) {
            $this->erreurs['favoris_id_enchere_id'] = 'Numéro incorrect.';
        }
        $this->favoris_id_enchere_id = $favoris_id_enchere_id;
        return $this;
    }


    /**
     * Mutateur de la propriété favoris_etat
     * @param string $favoris_etat
     * @return $this
     */
    public function setFavoris_etat($favoris_etat)
    {
        unset($this->erreurs['favoris_etat']);
        if ($favoris_etat != 'oui' || $favoris_etat != 'non') {
            $this->erreurs['favoris_etat'] = 'État incorrect.';
        }
        $this->favoris_etat = $favoris_etat;
        return $this;
    }
}
