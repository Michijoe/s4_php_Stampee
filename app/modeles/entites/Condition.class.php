<?php

/**
 * Classe de l'entité Condition
 *
 */
class Condition extends Entite
{
    protected $condition_id;
    protected $condition_nom;

    /**
     * Mutateur de la propriété condition_id 
     * @param int $condition_id
     * @return $this
     */
    public function setCondition_id($condition_id)
    {
        unset($this->erreurs['condition_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $condition_id)) {
            $this->erreurs['condition_id'] = 'Numéro de condition incorrect.';
        }
        $this->condition_id = $condition_id;
        return $this;
    }

    /**
     * Mutateur de la propriété condition_nom
     * @param string $condition_nom
     * @return $this
     */
    public function setCondition_nom($condition_nom)
    {
        unset($this->erreurs['condition_nom']);
        $condition_nom = trim($condition_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $condition_nom)) {
            $this->erreurs['condition_nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->condition_nom = $condition_nom;
        return $this;
    }
}
