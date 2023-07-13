<?php

/**
 * Classe de l'entité Profil
 *
 */
class Profil extends Entite
{
    protected $profil_id;
    protected $profil_nom;

    /**
     * Mutateur de la propriété profil_id 
     * @param int $profil_id
     * @return $this
     */
    public function setProfil_id($profil_id)
    {
        unset($this->erreurs['profil_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $profil_id)) {
            $this->erreurs['profil_id'] = 'Numéro de profil incorrect.';
        }
        $this->profil_id = $profil_id;
        return $this;
    }

    /**
     * Mutateur de la propriété profil_nom
     * @param string $profil_nom
     * @return $this
     */
    public function setProfil_nom($profil_nom)
    {
        unset($this->erreurs['profil_nom']);
        $profil_nom = trim($profil_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $profil_nom)) {
            $this->erreurs['profil_nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->profil_nom = $profil_nom;
        return $this;
    }
}
