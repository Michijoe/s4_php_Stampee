--

-- Database: `stampee`

--

CREATE DATABASE
    IF NOT EXISTS `stampee` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `stampee`;

-- --------------------------------------------------------

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
        `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `titre` VARCHAR(50) NOT NULL,
        `description` VARCHAR(500) NULL,
        `annee_publication` YEAR NOT NULL,
        `condition` TINYINT(1) NOT NULL,
        `pays_id` INTEGER(10) NOT NULL,
        `dimensions` VARCHAR(255) NULL,
        `tirage` INT(10) NULL,
        `couleur_dominante` TINYINT(1) NULL,
        `certification` TINYINT(1) NULL,
        `debut_enchere` DATETIME NOT NULL,
        `fin_enchere` DATETIME NOT NULL,
        `prix_plancher` DECIMAL NOT NULL,
        `utilisateur_id` INTEGER(10) NOT NULL,
        `statut` TINYINT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`)
    );

-- ---

-- Table 'Pays'

-- Pays d''origine du timbre

-- ---

DROP TABLE IF EXISTS `Pays`;

CREATE TABLE
    `Pays` (
        `id` INTEGER NOT NULL AUTO_INCREMENT,
        `nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`id`)
    ) COMMENT 'Pays d''origine du timbre';

-- ---

-- Table 'Image'

--

-- ---

DROP TABLE IF EXISTS `Image`;

CREATE TABLE
    `Image` (
        `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `timbre_id` INTEGER(10) NOT NULL,
        `nom_fichier` VARCHAR(200) NOT NULL,
        `image_principale` TINYINT(1) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY (
            `image_principale`,
            `timbre_id`
        )
    );

-- ---

-- Table 'Mise'

--

-- ---

DROP TABLE IF EXISTS `Mise`;

CREATE TABLE
    `Mise` (
        `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `prix` DECIMAL NOT NULL,
        `prix_max` DECIMAL NULL,
        `utilisateur_id` INTEGER(10) NOT NULL,
        `timbre_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`id`)
    );

-- ---

-- Table 'Utilisateur'

--

-- ---

DROP TABLE IF EXISTS `Utilisateur`;

CREATE TABLE
    `Utilisateur` (
        `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `nom` VARCHAR(50) NOT NULL,
        `prenom` VARCHAR(50) NOT NULL,
        `pseudo` VARCHAR(50) NOT NULL,
        `courriel` VARCHAR(255) NOT NULL,
        `mdp` VARCHAR(255) NOT NULL,
        `renouveller_mdp` CHAR(3) NOT NULL DEFAULT 'oui',
        `profil` TINYINT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY (`courriel`),
        UNIQUE KEY (`pseudo`)
    );

-- ---

-- Table 'Favoris'

--

-- ---

DROP TABLE IF EXISTS `Favoris`;

CREATE TABLE
    `Favoris` (
        `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `timbre_id` INTEGER(10) NOT NULL,
        `utilisateur_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`id`)
    );

-- ---

-- Table 'Commentaire'

--

-- ---

DROP TABLE IF EXISTS `Commentaire`;

CREATE TABLE
    `Commentaire` (
        `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `texte` VARCHAR(500) NOT NULL,
        `date_ajout` DATETIME NOT NULL,
        `timbre_id` INTEGER(10) NOT NULL,
        `utilisateur_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`id`)
    );

-- ---

-- Table 'Coups_coeur'

--

-- ---

DROP TABLE IF EXISTS `Coups_coeur`;

CREATE TABLE
    `Coups_coeur` (
        `id` INTEGER NOT NULL AUTO_INCREMENT,
        `timbre_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`id`)
    );

-- ---

-- Foreign Keys

-- ---

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (pays_id) REFERENCES `Pays` (`id`);

ALTER TABLE `Timbre`
ADD
    FOREIGN KEY (utilisateur_id) REFERENCES `Utilisateur` (`id`);

ALTER TABLE `Image`
ADD
    FOREIGN KEY (timbre_id) REFERENCES `Timbre` (`id`);

ALTER TABLE `Mise`
ADD
    FOREIGN KEY (utilisateur_id) REFERENCES `Utilisateur` (`id`);

ALTER TABLE `Mise`
ADD
    FOREIGN KEY (timbre_id) REFERENCES `Timbre` (`id`);

ALTER TABLE `Favoris`
ADD
    FOREIGN KEY (timbre_id) REFERENCES `Timbre` (`id`);

ALTER TABLE `Favoris`
ADD
    FOREIGN KEY (utilisateur_id) REFERENCES `Utilisateur` (`id`);

ALTER TABLE `Commentaire`
ADD
    FOREIGN KEY (timbre_id) REFERENCES `Timbre` (`id`);

ALTER TABLE `Commentaire`
ADD
    FOREIGN KEY (utilisateur_id) REFERENCES `Utilisateur` (`id`);

ALTER TABLE `Coups_coeur`
ADD
    FOREIGN KEY (timbre_id) REFERENCES `Timbre` (`id`);

-- ---

-- Table Properties

-- ---

-- ALTER TABLE `Timbre` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Pays` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Image` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Mise` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Utilisateur` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Favoris` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Commentaire` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `Coups_coeur` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---

-- Test Data

-- ---

-- INSERT INTO `Timbre` (`id`,`titre`,`description`,`annee_publication`,`condition`,`pays_id`,`dimensions`,`tirage`,`couleur_dominante`,`certification`,`debut_enchere`,`fin_enchere`,`prix_plancher`,`utilisateur_id`,`statut`) VALUES

-- ('','','','','','','','','','','','','','','');

-- INSERT INTO `Pays` (`id`,`nom`) VALUES

-- ('','');

-- INSERT INTO `Image` (`id`,`timbre_id`,`nom_fichier`,`image_principale`) VALUES

-- ('','','','');

-- INSERT INTO `Mise` (`id`,`prix`,`prix_max`,`utilisateur_id`,`timbre_id`) VALUES

-- ('','','','','');

-- INSERT INTO `Utilisateur` (`id`,`nom`,`prenom`,`pseudo`,`courriel`,`mdp`,`renouveller_mdp`,`profil`) VALUES

-- ('','','','','','','','');

-- INSERT INTO `Favoris` (`id`,`timbre_id`,`utilisateur_id`) VALUES

-- ('','','');

-- INSERT INTO `Commentaire` (`id`,`texte`,`date_ajout`,`timbre_id`,`utilisateur_id`) VALUES

-- ('','','','','');

-- INSERT INTO `Coups_coeur` (`id`,`timbre_id`) VALUES

-- ('','');