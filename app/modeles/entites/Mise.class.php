<?php

/**
 * Classe de l'entité Mise
 *
 */
class Mise extends Entite
{
    protected $mise_id = 0;
    protected $mise_prix;
    protected $mise_utilisateur_id;
    protected $mise_enchere_id;

    // Getters explicites nécessaires au moteur de templates TWIG
    public function getMise_id()
    {
        return $this->mise_id;
    }
    public function getMise_prix()
    {
        return $this->mise_prix;
    }
    public function getMise_utilisateur_id()
    {
        return $this->mise_utilisateur_id;
    }
    public function getMise_enchere_id()
    {
        return $this->mise_enchere_id;
    }


    /**
     * Mutateur de la propriété mise_id 
     * @param int $mise_id
     * @return $this
     */
    public function setMise_id($mise_id)
    {
        unset($this->erreurs['mise_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $mise_id)) {
            $this->erreurs['mise_id'] = 'Numéro incorrect.';
        }
        $this->mise_id = $mise_id;
        return $this;
    }


    /**
     * Mutateur de la propriété mise_prix 
     * @param string $mise_prix
     * @return $this
     */
    public function setMise_prix($mise_prix)
    {
        unset($this->erreurs['mise_prix']);
        $mise_prix = trim($mise_prix);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $mise_prix)) {
            $this->erreurs['mise_prix'] = 'Entrez un nombre entier.';
        }
        $this->mise_prix = $mise_prix;
        return $this;
    }


    /**
     * Mutateur de la propriété mise_utilisateur_id
     * @param string $mise_utilisateur_id
     * @return $this
     */
    public function setMise_utilisateur_id($mise_utilisateur_id)
    {
        unset($this->erreurs['mise_utilisateur_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $mise_utilisateur_id)) {
            $this->erreurs['mise_utilisateur_id'] = 'Numéro incorrect.';
        }
        $this->mise_utilisateur_id = $mise_utilisateur_id;
        return $this;
    }


    /**
     * Mutateur de la propriété mise_enchere_id
     * @param string $mise_enchere_id
     * @return $this
     */
    public function setMise_enchere_id($mise_enchere_id)
    {
        unset($this->erreurs['mise_enchere_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $mise_enchere_id)) {
            $this->erreurs['mise_enchere_id'] = 'Numéro incorrect.';
        }
        $this->mise_enchere_id = $mise_enchere_id;
        return $this;
    }
}
