<?php

/**
 * Classe de l'entité Couleur
 *
 */
class Couleur extends Entite
{
    protected $couleur_id;
    protected $couleur_nom;

    /**
     * Mutateur de la propriété couleur_id 
     * @param int $couleur_id
     * @return $this
     */
    public function setCouleur_id($couleur_id)
    {
        unset($this->erreurs['couleur_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $couleur_id)) {
            $this->erreurs['couleur_id'] = 'Numéro de couleur incorrect.';
        }
        $this->couleur_id = $couleur_id;
        return $this;
    }

    /**
     * Mutateur de la propriété couleur_nom
     * @param string $couleur_nom
     * @return $this
     */
    public function setCouleur_nom($couleur_nom)
    {
        unset($this->erreurs['couleur_nom']);
        $couleur_nom = trim($couleur_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $couleur_nom)) {
            $this->erreurs['couleur_nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->couleur_nom = $couleur_nom;
        return $this;
    }
}
