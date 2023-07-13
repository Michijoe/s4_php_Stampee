<?php

/**
 * Classe de l'entité Tirage
 *
 */
class Tirage extends Entite
{
    protected $tirage_id;
    protected $tirage_nom;

    /**
     * Mutateur de la propriété tirage_id 
     * @param int $tirage_id
     * @return $this
     */
    public function setTirage_id($tirage_id)
    {
        unset($this->erreurs['tirage_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $tirage_id)) {
            $this->erreurs['tirage_id'] = 'Numéro de tirage incorrect.';
        }
        $this->tirage_id = $tirage_id;
        return $this;
    }

    /**
     * Mutateur de la propriété tirage_nom
     * @param string $tirage_nom
     * @return $this
     */
    public function setTirage_nom($tirage_nom)
    {
        unset($this->erreurs['tirage_nom']);
        $tirage_nom = trim($tirage_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $tirage_nom)) {
            $this->erreurs['tirage_nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->tirage_nom = $tirage_nom;
        return $this;
    }
}
