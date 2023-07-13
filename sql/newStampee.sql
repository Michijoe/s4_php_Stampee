DROP SCHEMA IF EXISTS `stampee`;

CREATE SCHEMA `stampee` DEFAULT CHARACTER SET utf8 ;

USE `stampee` ;

-- ---

-- Globals

-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- SET FOREIGN_KEY_CHECKS=0;

-- ---

-- Table 'Timbre'

--

-- ---

DROP TABLE IF EXISTS `Timbre`;

CREATE TABLE
    `Timbre` (
        `timbre_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `timbre_titre` VARCHAR(255) NOT NULL,
        `timbre_description` VARCHAR(500) NULL,
        `timbre_annee_publication` SMALLINT(5) NOT NULL,
        `timbre_condition_id` TINYINT(3) NOT NULL,
        `timbre_pays_id` TINYINT(3) NOT NULL,
        `timbre_dimensions` VARCHAR(255) NULL,
        `timbre_tirage_id` TINYINT(3) NOT NULL,
        `timbre_couleur_id` TINYINT(3) NOT NULL,
        `timbre_certification` CHAR(3) NULL,
        `timbre_statut` TINYINT(1) NOT NULL DEFAULT 1,
        `timbre_utilisateur_id` INTEGER(10) NOT NULL,
        `timbre_enchere_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`timbre_id`)
    );

-- ---

-- Table 'Enchere'

--

-- ---

DROP TABLE IF EXISTS `Enchere`;

CREATE TABLE
    `Enchere` (
        `enchere_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `enchere_date_debut` DATETIME NOT NULL,
        `enchere_date_fin` DATETIME NOT NULL,
        `enchere_utilisateur_id` INTEGER(10) NOT NULL,
        `enchere_prix_reserve` DECIMAL NOT NULL,
        `enchere_coup_coeur` CHAR(3) NOT NULL DEFAULT 'non',
        PRIMARY KEY (`enchere_id`)
    );

-- ---

-- Table 'Pays'

-- Pays d''origine du timbre

-- ---

DROP TABLE IF EXISTS `Pays`;

CREATE TABLE
    `Pays` (
        `pays_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `pays_nom` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`pays_id`)
    ) COMMENT 'Pays d''origine du timbre';

-- ---

-- Table 'Image'

--

-- ---

DROP TABLE IF EXISTS `Image`;

CREATE TABLE
    `Image` (
        `image_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `image_timbre_id` INTEGER(10) NOT NULL,
        `image_nom_fichier` VARCHAR(255) NOT NULL,
        `image_principale` CHAR(3) NOT NULL DEFAULT 'oui',
        PRIMARY KEY (`image_id`),
        UNIQUE KEY (
            `image_principale`,
            `image_timbre_id`
        )
    );

-- ---

-- Table 'Mise'

--

-- ---

DROP TABLE IF EXISTS `Mise`;

CREATE TABLE
    `Mise` (
        `mise_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `mise_prix` DECIMAL NOT NULL,
        `mise_utilisateur_id` INTEGER(10) NOT NULL,
        `mise_enchere_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`mise_id`)
    );

-- ---

-- Table 'Utilisateur'

--

-- ---

DROP TABLE IF EXISTS `Utilisateur`;

CREATE TABLE
    `Utilisateur` (
        `utilisateur_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `utilisateur_nom` VARCHAR(255) NOT NULL,
        `utilisateur_prenom` VARCHAR(255) NOT NULL,
        `utilisateur_courriel` VARCHAR(255) NOT NULL,
        `utilisateur_mdp` VARCHAR(255) NOT NULL,
        `utilisateur_renouveler_mdp` CHAR(3) NOT NULL,
        `utilisateur_profil_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`utilisateur_id`),
        UNIQUE KEY (`utilisateur_courriel`)
    );

-- ---

-- Table 'Favoris'

--

-- ---

DROP TABLE IF EXISTS `Favoris`;

CREATE TABLE
    `Favoris` (
        `favoris_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `favoris_utilisateur_id` INTEGER(10) NOT NULL,
        `favoris_enchere_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`favoris_id`)
    );

-- ---

-- Table 'Commentaire'

--

-- ---

DROP TABLE IF EXISTS `Commentaire`;

CREATE TABLE
    `Commentaire` (
        `commentaire_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `commentaire_texte` VARCHAR(500) NOT NULL,
        `commentaire_date_ajout` DATETIME NOT NULL,
        `commentaire_utilisateur_id` INTEGER(10) NOT NULL,
        `commentaire_enchere_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`commentaire_id`)
    );

-- ---

-- Table 'Condition'

--

-- ---

DROP TABLE IF EXISTS `Condition`;

CREATE TABLE
    `Condition` (
        `condition_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `condition_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`condition_id`)
    );

-- ---

-- Table 'Tirage'

--

-- ---

DROP TABLE IF EXISTS `Tirage`;

CREATE TABLE
    `Tirage` (
        `tirage_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `tirage_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`tirage_id`)
    );

-- ---

-- Table 'Couleur'

--

-- ---

DROP TABLE IF EXISTS `Couleur`;

CREATE TABLE
    `Couleur` (
        `couleur_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `couleur_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`couleur_id`)
    );

-- ---

-- Table 'Profil'

--

-- ---

DROP TABLE IF EXISTS `Profil`;

CREATE TABLE
    `Profil` (
        `profil_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `profil_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`profil_id`)
    );

-- ---

-- Foreign Keys

-- ---

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (timbre_condition_id) REFERENCES `Condition` (`condition_id`);

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (timbre_pays_id) REFERENCES `Pays` (`pays_id`);

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (timbre_tirage_id) REFERENCES `Tirage` (`tirage_id`);

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (timbre_couleur_id) REFERENCES `Couleur` (`couleur_id`);

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (timbre_utilisateur_id) REFERENCES `Utilisateur` (`utilisateur_id`);

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (timbre_enchere_id) REFERENCES `Enchere` (`enchere_id`);

ALTER TABLE `Enchere`
ADD
    FOREIGN KEY (enchere_utilisateur_id) REFERENCES `Utilisateur` (`utilisateur_id`);

ALTER TABLE `Image`
ADD
    FOREIGN KEY (image_timbre_id) REFERENCES `Timbre` (`timbre_id`);

ALTER TABLE `Mise`
ADD
    FOREIGN KEY (mise_utilisateur_id) REFERENCES `Utilisateur` (`utilisateur_id`);

ALTER TABLE `Mise`
ADD
    FOREIGN KEY (mise_enchere_id) REFERENCES `Enchere` (`enchere_id`);

ALTER TABLE `Utilisateur`
ADD
    FOREIGN KEY (utilisateur_profil_id) REFERENCES `Profil` (`profil_id`);

ALTER TABLE `Favoris`
ADD
    FOREIGN KEY (favoris_utilisateur_id) REFERENCES `Utilisateur` (`utilisateur_id`);

ALTER TABLE `Favoris`
ADD
    FOREIGN KEY (favoris_enchere_id) REFERENCES `Enchere` (`enchere_id`);

ALTER TABLE `Commentaire`
ADD
    FOREIGN KEY (commentaire_utilisateur_id) REFERENCES `Utilisateur` (`utilisateur_id`);

ALTER TABLE `Commentaire`
ADD
    FOREIGN KEY (commentaire_enchere_id) REFERENCES `Enchere` (`enchere_id`);

-- ---

-- Table Properties

-- ---

-- ALTER TABLE `Timbre` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Enchere` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Pays` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Image` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Mise` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Utilisateur` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Favoris` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Commentaire` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Condition` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Tirage` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Couleur` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Profil` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---

-- Test Data

-- ---

-- INSERT INTO `Timbre` (`timbre_id`,`timbre_titre`,`timbre_description`,`timbre_annee_publication`,`timbre_condition_id`,`timbre_pays_id`,`timbre_dimensions`,`timbre_tirage_id`,`timbre_couleur_id`,`timbre_certification`,`timbre_statut`,`timbre_utilisateur_id`,`timbre_enchere_id`) VALUES

-- ('','','','','','','','','','','','','');

-- INSERT INTO `Enchere` (`enchere_id`,`enchere_date_debut`,`enchere_date_fin`,`enchere_utilisateur_id`,`enchere_prix_reserve`,`enchere_coup_coeur`) VALUES

-- ('','','','','','');

-- INSERT INTO `Pays` (`pays_id`,`pays_nom`) VALUES

-- ('','');

-- INSERT INTO `Image` (`image_id`,`image_timbre_id`,`image_nom_fichier`,`image_principale`) VALUES

-- ('','','','');

-- INSERT INTO `Mise` (`mise_id`,`mise_prix`,`mise_utilisateur_id`,`mise_enchere_id`) VALUES

-- ('','','','');

-- INSERT INTO `Utilisateur` (`utilisateur_id`,`utilisateur_nom`,`utilisateur_prenom`,`utilisateur_courriel`,`utilisateur_mdp`,`utilisateur_renouveler_mdp`,`utilisateur_profil_id`) VALUES

-- ('','','','','','','');

-- INSERT INTO `Favoris` (`favoris_id`,`favoris_utilisateur_id`,`favoris_enchere_id`) VALUES

-- ('','','');

-- INSERT INTO `Commentaire` (`commentaire_id`,`commentaire_texte`,`commentaire_date_ajout`,`commentaire_utilisateur_id`,`commentaire_enchere_id`) VALUES

-- ('','','','','');

-- INSERT INTO `Condition` (`condition_id`,`condition_nom`) VALUES

-- ('','');

-- INSERT INTO `Tirage` (`tirage_id`,`tirage_nom`) VALUES

-- ('','');

-- INSERT INTO `Couleur` (`couleur_id`,`couleur_nom`) VALUES

-- ('','');

-- INSERT INTO `Profil` (`profil_id`,`profil_nom`) VALUES

-- ('','');