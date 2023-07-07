<?php

/**
 * Classe de l'entité Image
 *
 */
class Image extends Entite
{
    protected $image_id;
    protected $image_nom_fichier;

    /**
     * Mutateur de la propriété image_id 
     * @param int $image_id
     * @return $this
     */
    public function setImage_id($image_id)
    {
        unset($this->erreurs['image_id']);
        $regExp = '/^[1-9]\d*$/';
        if (!preg_match($regExp, $image_id)) {
            $this->erreurs['image_id'] = 'Numéro d\'image incorrect.';
        }
        $this->image_id = $image_id;
        return $this;
    }


    /**
     * Mutateur de la propriété image_nom_fichier
     * @param string $image_nom_fichier
     * @return $this
     */
    public function setImage_nom_fichier($image_nom_fichier)
    {
        unset($this->erreurs['image_nom_fichier']);
        $image_nom_fichier = trim($image_nom_fichier);
        $regExp = '/^.+\.jpg$/';
        if (!preg_match($regExp, $image_nom_fichier)) {
            $this->erreurs['image_nom_fichier'] = "Vous devez téléverser un fichier de type jpg.";
        }
        $this->image_nom_fichier = $image_nom_fichier;
        return $this;
    }
}
