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

DROP TABLE IF EXISTS `timbre`;

CREATE TABLE
    `timbre` (
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

DROP TABLE IF EXISTS `enchere`;

CREATE TABLE
    `enchere` (
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

DROP TABLE IF EXISTS `pays`;

CREATE TABLE
    `pays` (
        `pays_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `pays_nom` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`pays_id`)
    ) COMMENT 'Pays d''origine du timbre';

-- ---

-- Table 'Image'

--

-- ---

DROP TABLE IF EXISTS `image`;

CREATE TABLE
    `image` (
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

DROP TABLE IF EXISTS `mise`;

CREATE TABLE
    `mise` (
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

DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE
    `utilisateur` (
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

DROP TABLE IF EXISTS `favoris`;

CREATE TABLE
    `favoris` (
        `favoris_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `favoris_utilisateur_id` INTEGER(10) NOT NULL,
        `favoris_enchere_id` INTEGER(10) NOT NULL,
        PRIMARY KEY (`favoris_id`)
    );

-- ---

-- Table 'Commentaire'

--

-- ---

DROP TABLE IF EXISTS `commentaire`;

CREATE TABLE
    `commentaire` (
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

DROP TABLE IF EXISTS `condition`;

CREATE TABLE
    `condition` (
        `condition_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `condition_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`condition_id`)
    );

-- ---

-- Table 'Tirage'

--

-- ---

DROP TABLE IF EXISTS `tirage`;

CREATE TABLE
    `tirage` (
        `tirage_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `tirage_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`tirage_id`)
    );

-- ---

-- Table 'Couleur'

--

-- ---

DROP TABLE IF EXISTS `couleur`;

CREATE TABLE
    `couleur` (
        `couleur_id` TINYINT(3) NOT NULL AUTO_INCREMENT,
        `couleur_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`couleur_id`)
    );

-- ---

-- Table 'Profil'

--

-- ---

DROP TABLE IF EXISTS `profil`;

CREATE TABLE
    `profil` (
        `profil_id` INTEGER(10) NOT NULL AUTO_INCREMENT,
        `profil_nom` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`profil_id`)
    );

-- ---

-- Foreign Keys

-- ---

ALTER TABLE `timbre`
ADD
    FOREIGN KEY (timbre_condition_id) REFERENCES `condition` (`condition_id`);

ALTER TABLE `timbre`
ADD
    FOREIGN KEY (timbre_pays_id) REFERENCES `pays` (`pays_id`);

ALTER TABLE `timbre`
ADD
    FOREIGN KEY (timbre_tirage_id) REFERENCES `tirage` (`tirage_id`);

ALTER TABLE `timbre`
ADD
    FOREIGN KEY (timbre_couleur_id) REFERENCES `couleur` (`couleur_id`);

ALTER TABLE `timbre`
ADD
    FOREIGN KEY (timbre_utilisateur_id) REFERENCES `utilisateur` (`utilisateur_id`);

ALTER TABLE `timbre`
ADD
    FOREIGN KEY (timbre_enchere_id) REFERENCES `enchere` (`enchere_id`);

ALTER TABLE `enchere`
ADD
    FOREIGN KEY (enchere_utilisateur_id) REFERENCES `utilisateur` (`utilisateur_id`);

ALTER TABLE `image`
ADD
    FOREIGN KEY (image_timbre_id) REFERENCES `timbre` (`timbre_id`) ON DELETE CASCADE;

ALTER TABLE `mise`
ADD
    FOREIGN KEY (mise_utilisateur_id) REFERENCES `utilisateur` (`utilisateur_id`);

ALTER TABLE `mise`
ADD
    FOREIGN KEY (mise_enchere_id) REFERENCES `enchere` (`enchere_id`);

ALTER TABLE `utilisateur`
ADD
    FOREIGN KEY (utilisateur_profil_id) REFERENCES `profil` (`profil_id`);

ALTER TABLE `favoris`
ADD
    FOREIGN KEY (favoris_utilisateur_id) REFERENCES `utilisateur` (`utilisateur_id`);

ALTER TABLE `favoris`
ADD
    FOREIGN KEY (favoris_enchere_id) REFERENCES `enchere` (`enchere_id`);

ALTER TABLE `commentaire`
ADD
    FOREIGN KEY (commentaire_utilisateur_id) REFERENCES `utilisateur` (`utilisateur_id`);

ALTER TABLE `commentaire`
ADD
    FOREIGN KEY (commentaire_enchere_id) REFERENCES `enchere` (`enchere_id`);

-- ---

-- Table Properties

-- ---

-- ALTER TABLE `timbre` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `enchere` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `pays` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `image` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `mise` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `utilisateur` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `favoris` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `commentaire` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `condition` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `tirage` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `couleur` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `profil` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---