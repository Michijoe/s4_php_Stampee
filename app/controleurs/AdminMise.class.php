<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Mise de l'application admin
 */

class AdminMise extends Admin
{
    protected $methodes = [
        'l' => ['nom' => 'listerMises',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]]
    ];


    /**
     * Constructeur qui initialise des propriétés à partir du query string
     * et la propriété oRequetesSQL déclarée dans la classe Routeur
     * 
     */
    public function __construct()
    {
        $this->oRequetesSQL = new RequetesSQL;
    }


    /**
     * Lister les encheres des timbres
     */
    public function listerMises()
    {
        if (self::$oUtilConn->utilisateur_profil_id == Utilisateur::PROFIL_ADMINISTRATEUR) {
            $encheresMises = $this->oRequetesSQL->getEncheresMises('admin-mise');
            $titre = 'Toutes les mises';
        } else {
            $encheresMises = $this->oRequetesSQL->getEncheresMises('membre-miseur');
            $titre = 'Mes mises';
        }

        (new Vue)->generer(
            'vAdminMises',
            [
                'oUtilConn'           => self::$oUtilConn,
                'titre'               => $titre,
                'encheresMises'       => $encheresMises,
                'classRetour'         => $this->classRetour,
                'messageRetourAction' => $this->messageRetourAction,
                'entite'              => self::$entite
            ],
            'gabarit-admin'
        );
    }
}
