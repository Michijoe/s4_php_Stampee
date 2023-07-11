-- phpMyAdmin SQL Dump

-- version 5.1.1

-- https://www.phpmyadmin.net/

--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

--

--

-- Base de données : `stampee`

--

DROP SCHEMA IF EXISTS `stampee`;

CREATE SCHEMA `stampee` DEFAULT CHARACTER SET utf8 ;

USE `stampee` ;

--

-- --------------------------------------------------------

--

-- Structure de la table `Commentaire`

--

CREATE TABLE
    `Commentaire` (
        `commentaire_id` int(10) NOT NULL,
        `commentaire_texte` varchar(500) NOT NULL,
        `commentaire_date_ajout` datetime NOT NULL,
        `commentaire_enchere_id` int(10) NOT NULL,
        `commentaire_utilisateur_id` int(10) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------

--

-- Structure de la table `Enchere`

--

CREATE TABLE
    `Enchere` (
        `enchere_id` int(10) NOT NULL,
        `enchere_date_debut` datetime NOT NULL,
        `enchere_date_fin` datetime NOT NULL,
        `enchere_utilisateur_id` int(10) NOT NULL,
        `enchere_prix_plancher` decimal(10, 0) NOT NULL,
        `enchere_coups_coeur_lord` varchar(3) NOT NULL DEFAULT 'Non'
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

--

-- Déchargement des données de la table `Enchere`

--

INSERT INTO
    `Enchere` (
        `enchere_id`,
        `enchere_date_debut`,
        `enchere_date_fin`,
        `enchere_utilisateur_id`,
        `enchere_prix_plancher`,
        `enchere_coups_coeur_lord`
    )
VALUES (
        1,
        '2023-06-14 08:00:00',
        '2023-08-14 20:00:00',
        1,
        '15',
        'Non'
    ), (
        2,
        '2023-06-10 08:00:00',
        '2023-08-10 20:00:00',
        1,
        '50',
        'Oui'
    ), (
        3,
        '2023-09-14 08:00:00',
        '2023-10-14 08:00:00',
        2,
        '100',
        'Non'
    ), (
        4,
        '2023-06-14 08:00:00',
        '2023-10-14 08:00:00',
        2,
        '80',
        'Non'
    ), (
        5,
        '2023-10-12 12:00:00',
        '2023-12-12 12:00:00',
        1,
        '200',
        'Oui'
    ), (
        6,
        '2023-06-12 12:00:00',
        '2023-07-12 12:00:00',
        2,
        '400',
        'Oui'
    ), (
        7,
        '2023-06-01 12:00:00',
        '2023-07-01 12:00:00',
        1,
        '120',
        'Non'
    ), (
        8,
        '2023-05-15 12:00:00',
        '2023-07-15 12:00:00',
        2,
        '15',
        'Oui'
    ), (
        9,
        '2023-07-03 12:00:00',
        '2023-09-03 12:00:00',
        1,
        '45',
        'Non'
    ), (
        10,
        '2023-06-20 12:00:00',
        '2023-09-20 12:00:00',
        2,
        '75',
        'Non'
    ), (
        11,
        '2023-02-12 12:00:00',
        '2023-04-12 12:00:00',
        1,
        '250',
        'Oui'
    ), (
        12,
        '2023-10-12 12:00:00',
        '2023-12-12 12:00:00',
        2,
        '400',
        'Oui'
    ), (
        13,
        '2024-01-01 12:00:00',
        '2024-01-12 12:00:00',
        1,
        '40',
        'Oui'
    ), (
        14,
        '2023-07-01 12:00:00',
        '2023-07-30 12:00:00',
        2,
        '60',
        'Non'
    ), (
        15,
        '2023-06-01 12:00:00',
        '2023-06-30 12:00:00',
        1,
        '100',
        'Non'
    ), (
        16,
        '2023-05-01 12:00:00',
        '2023-05-28 12:00:00',
        2,
        '90',
        'Oui'
    ), (
        17,
        '2023-07-01 12:00:00',
        '2023-08-01 12:00:00',
        1,
        '320',
        'Oui'
    ), (
        18,
        '2023-07-02 12:00:00',
        '2023-08-02 12:00:00',
        1,
        '110',
        'Non'
    ), (
        19,
        '2023-07-01 12:00:00',
        '2023-07-15 12:00:00',
        2,
        '125',
        'Oui'
    ), (
        20,
        '2023-07-10 12:00:00',
        '2023-07-24 12:00:00',
        2,
        '30',
        'Oui'
    );

-- --------------------------------------------------------

--

-- Structure de la table `Favoris`

--

CREATE TABLE
    `Favoris` (
        `favoris_id` int(10) NOT NULL,
        `favoris_enchere_id` int(10) NOT NULL,
        `favoris_utilisateur_id` int(10) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------

--

-- Structure de la table `Image`

--

CREATE TABLE
    `Image` (
        `image_id` int(10) NOT NULL,
        `image_timbre_id` int(10) NOT NULL,
        `image_nom_fichier` varchar(200) NOT NULL,
        `image_principale` varchar(3) NOT NULL DEFAULT 'oui'
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

--

-- Déchargement des données de la table `Image`

--

INSERT INTO
    `Image` (
        `image_id`,
        `image_timbre_id`,
        `image_nom_fichier`,
        `image_principale`
    )
VALUES (
        1,
        1,
        'medias/images/timbre-1.webp',
        'oui'
    ), (
        2,
        2,
        'medias/images/timbre-2.webp',
        'oui'
    ), (
        3,
        3,
        'medias/images/timbre-3.webp',
        'oui'
    ), (
        4,
        4,
        'medias/images/timbre-4.webp',
        'oui'
    ), (
        5,
        5,
        'medias/images/timbre-5.webp',
        'oui'
    ), (
        6,
        6,
        'medias/images/timbre-6.webp',
        'oui'
    ), (
        7,
        7,
        'medias/images/timbre-7.webp',
        'oui'
    ), (
        8,
        8,
        'medias/images/timbre-8.webp',
        'oui'
    ), (
        9,
        9,
        'medias/images/timbre-9.webp',
        'oui'
    ), (
        10,
        10,
        'medias/images/timbre-10.webp',
        'oui'
    ), (
        11,
        11,
        'medias/images/timbre-11.webp',
        'oui'
    ), (
        12,
        12,
        'medias/images/timbre-12.webp',
        'oui'
    ), (
        13,
        13,
        'medias/images/timbre-13.webp',
        'oui'
    ), (
        14,
        14,
        'medias/images/timbre-14.webp',
        'oui'
    ), (
        15,
        15,
        'medias/images/timbre-15.webp',
        'oui'
    ), (
        16,
        16,
        'medias/images/timbre-16.webp',
        'oui'
    ), (
        17,
        17,
        'medias/images/timbre-17.webp',
        'oui'
    ), (
        18,
        18,
        'medias/images/timbre-18.webp',
        'oui'
    ), (
        19,
        19,
        'medias/images/timbre-19.webp',
        'oui'
    ), (
        20,
        20,
        'medias/images/timbre-20.webp',
        'oui'
    );

-- --------------------------------------------------------

--

-- Structure de la table `Mise`

--

CREATE TABLE
    `Mise` (
        `mise_id` int(10) NOT NULL,
        `mise_prix` decimal(10, 0) NOT NULL,
        `mise_utilisateur_id` int(10) NOT NULL,
        `mise_enchere_id` int(10) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

--

-- Déchargement des données de la table `Mise`

--

-- --------------------------------------------------------

--

-- Structure de la table `Pays`

--

CREATE TABLE
    `Pays` (
        `pays_id` tinyint(3) NOT NULL,
        `pays_nom` varchar(255) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = 'Pays d''origine du timbre';

--

-- Déchargement des données de la table `Pays`

--

INSERT INTO
    `Pays` (`pays_id`, `pays_nom`)
VALUES (1, 'Royaume-Uni'), (2, 'États-Unis'), (3, 'Canada'), (4, 'Australie'), (5, 'Europe'), (6, 'Amériques'), (7, 'Afrique'), (8, 'Asie'), (9, 'Océanie'), (10, 'Autres');

-- --------------------------------------------------------

--

-- Structure de la table `Timbre`

--

CREATE TABLE
    `Timbre` (
        `timbre_id` int(10) NOT NULL,
        `timbre_titre` varchar(255) NOT NULL,
        `timbre_description` varchar(500) DEFAULT NULL,
        `timbre_annee_publication` year(4) DEFAULT NULL,
        `timbre_condition` tinyint(1) NOT NULL,
        `timbre_pays_id` tinyint(3) NOT NULL,
        `timbre_dimensions` varchar(255) DEFAULT NULL,
        `timbre_tirage` tinyint(1) DEFAULT NULL,
        `timbre_couleur` tinyint(1) DEFAULT NULL,
        `timbre_certification` tinyint(1) DEFAULT NULL,
        `timbre_statut` tinyint(1) NOT NULL DEFAULT '1',
        `timbre_utilisateur_id` int(10) NOT NULL,
        `timbre_enchere_id` int(10) DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

--

-- Déchargement des données de la table `Timbre`

--

INSERT INTO
    `Timbre` (
        `timbre_id`,
        `timbre_titre`,
        `timbre_description`,
        `timbre_annee_publication`,
        `timbre_condition`,
        `timbre_pays_id`,
        `timbre_dimensions`,
        `timbre_tirage`,
        `timbre_couleur`,
        `timbre_certification`,
        `timbre_statut`,
        `timbre_utilisateur_id`,
        `timbre_enchere_id`
    )
VALUES (
        1,
        'Canada - Artifact Definitives # 917 - 922 M/NH - Set of Six',
        'Low-Value Artifact Definitives',
        1982,
        2,
        3,
        '14 x 13.3',
        2,
        5,
        0,
        1,
        1,
        1
    ), (
        2,
        'Dominica #676-77 (1980 Queen Mother set in sheets of nine) VFMNH CV',
        'This is a set of 2 Singles of Dominica Scott numbers 372-73. Issued in 1973, the stamps are Fine to Very Fine, Mint Never Hinged.',
        1973,
        3,
        6,
        'regular size',
        0,
        5,
        1,
        1,
        1,
        2
    ), (
        3,
        'Vatican. Mi # 1 pages mostly used',
        '1847 5c black on bluish, double transfer (\"big shift\"), pos.2, margins all around, slight toning and minor bends, cancelled by red \"Paid\" handstamp on FL used in 1846 to Norwich, Ct, with additional \"Paid\" and red \"New York 9 Dec 5Cts\" cds alongside, fine, with 2013 PFC',
        1947,
        3,
        2,
        'regular',
        3,
        1,
        0,
        1,
        2,
        3
    ), (
        4,
        'CANADA 1851 #1, CASTOR 3D ROUGE, TIMBRE B /O.',
        'Le premier timbre Canadien, 3 pence Castor de 1851 imperforé et oblitéré. Certificat de "AIEP" (Association Internationale des Experts en Philatelie). Lire SVP. Papier vergé avec une très belle oblitération six anneaux. Le timbre possède 3 marges Unitrade 2021 catalogue $800.00.',
        1851,
        4,
        8,
        '12x23',
        0,
        2,
        1,
        1,
        2,
        4
    ), (
        5,
        'CANADA 1851 #2, 6D VIOLET ARDOISE PRINCE ALBERT B-TB /O.',
        '1851 Prince Albert 6d violet ardoise oblitéré. Le timbre possède 4 marges. Unitrade 2021 catalogue $2000.00.',
        1851,
        3,
        9,
        '5x4',
        3,
        2,
        0,
        1,
        1,
        5
    ), (
        6,
        'CANADA 1852 #4, TIMBRE B /O.',
        'Le 3 pence Castor imperforé de 1952 oblitéré. Très belle oblitération 4 anneaux avec chiffre 2 au centre. Le timbre a un bord de feuille sur le bas et une marge claire à gauche. Unitrade 2021 catalogue $150.00.',
        1852,
        0,
        3,
        '12 cm',
        1,
        3,
        1,
        1,
        2,
        6
    ), (
        7,
        'CANADA 1855 #5, TIMBRE B-TB /O.',
        'Le 6 pence Prince Albert de 1855 oblitéré avec une oblitération 7 anneaux. Le timbre possède 4 marges claires. Unitrade 2021 catalogue $800.00.',
        1855,
        3,
        9,
        '2 cm',
        3,
        0,
        0,
        1,
        1,
        7
    ), (
        8,
        'FRANCE 1923 # 197, TIMBRE TB *.',
        'Exposition philatélique de Bordeaux, le timbre est neuf avec charnière. Scott CV 600,00$ CAD.',
        1923,
        1,
        2,
        '12x32cm',
        2,
        4,
        0,
        1,
        2,
        8
    ), (
        9,
        'FRANCE 1937 # 329 B/F B-TB **.',
        'Exposition Philatélique de Paris, bloc-feuillet neuf sans charnière. Quelques infimes plis de gomme en haut et en bas à gauche. Scott CV 930,00$ CAD.',
        1937,
        1,
        4,
        '12 cm',
        0,
        5,
        0,
        1,
        1,
        9
    ), (
        10,
        'FRANCE 1917-19 SÉRIE # B3-10, TIMBRES F *',
        'Première série des Orphelins, ensemble complet de 8 timbres neufs avec charnière. A examiner, ensemble rare ! Scott CV 3350,00$ CAD.',
        1917,
        4,
        8,
        '12 cm',
        3,
        5,
        1,
        0,
        2,
        10
    ), (
        11,
        'COMMONWEALTH BRITANNIQUE 1947-1948 SÉRIE OMNIBUS, TIMBRES TB **.',
        'Série omnibus de 1948 pour les noces d\'argent du roi Georges VI et de la reine Élisabeth. Presque complète: ne manque que 5 pays (Seiyun, Cayman, Gambie, Kenya, Iles Solomon). + visite royale de 1947 + olympique de1948 (Grande Bretagne) + Bahamas 65/69. Catalogue Scott 2022: 3097.00$ CAD.',
        1947,
        1,
        4,
        '12 cm',
        2,
        4,
        0,
        1,
        1,
        11
    ), (
        12,
        'CAP DE BONNE ESPERANCE 1855-1904 SÉLECTION, TIMBRES B-TB /O.',
        'Collection de 64 timbres oblitérés sur pages d\'album maison, 2 entier postaux postés et 2 maxi-cartes récentes. Très propre. A noter: # 3a, 4d, 4-6, 13, 25, 71. A examiner avec soin. Catalogue du propriétaire: 2700.00$ CAD.',
        1855,
        4,
        4,
        '12 cm',
        2,
        3,
        0,
        1,
        2,
        12
    ), (
        13,
        'CAP VERT 1986 BLOCS FEUILLETS # 488-490 TB **.',
        'Les 3 Feuillets-Souvenir de 1986 émis pour l\' oeuvre "Vapor de Hundertwasser".Catalogue: 585.00 $ CAD.',
        1986,
        3,
        7,
        ' 12 cm ',
        1,
        1,
        0,
        1,
        1,
        13
    ), (
        14,
        'CEYLAN 1857-1911, COLLECTION, TIMBRES B-TB */O.',
        'Collection de 65 timbres et 4 entiers postaux non postés. La plupart des timbres sont oblitérés. J\'ai noté: #13 /o (voir photo), 38 /o, 62 /o, 66 *, 85*, 98 /o. Catalogue du propriétaire: 2717.00$ CAD.',
        1857,
        3,
        7,
        '12 cm',
        2,
        2,
        1,
        1,
        2,
        14
    ), (
        15,
        'Timbre du Canada #1837 - Dragon et symbole chinois (2000)',
        'Impérial Dragon émission de 1885, les timbres sont oblitérés et perforés 12.5 avec filigrane 103 (yin-yang symbole). A vérifier soigneusement. Scott CV 510.00$.',
        1885,
        4,
        2,
        '12 cm',
        2,
        3,
        0,
        1,
        1,
        15
    ), (
        16,
        'RÉPUBLIQUE POPULAIRE DE CHINE 1961 # 566A, B/F TB **.',
        '26e Championnats du monde de Tennis de table, Pékin, bloc-feuillet de 4 timbres neufs sans gomme tel qu\'émis. Condition excellente. Scott CV 1190,00$ CAD.',
        1961,
        1,
        4,
        '12 cm',
        2,
        4,
        0,
        1,
        2,
        16
    ), (
        17,
        'RÉPUBLIQUE POPULAIRE DE CHINE 1967 SÉRIE # 949-56, TIMBRES TB /O.',
        'Vive le président Mao de 1967, série complète de 8 timbres oblitérés (CTO). Scott CV 800.00$ CAD.',
        1967,
        2,
        6,
        '12 cm',
        2,
        3,
        1,
        1,
        1,
        17
    ), (
        18,
        'CUBA 1959-1971 COLLECTION, TIMBRES B-TB **/*/O.',
        'Collection de plusieurs centaines de timbres dans un album La Collection avec boîtier. Les feuillets sont neufs sans charnière. Complet à 90% pour la période. J\'ai noté: # 862a/882a ** (feuilles complètes), 883a**, 916a/926a/o, 1274**, 1289a**, 1307a/1317a**, 1365**, 1373**, 1468a/1478a**, C239**. Catalogue du propriétaire: 1123.00$ CAD.',
        1959,
        4,
        2,
        '12 cm',
        0,
        0,
        1,
        1,
        1,
        18
    ), (
        19,
        'CHYPRE 1880-1910 SELECTION, TIMBRES B */O.',
        'Sélection de 18 timbres et un entier postal non posté. A noter: # 4*, 13-14 /o, 16 /o. Catalogue du propriétaire: 858.00$ CAD.',
        1880,
        3,
        1,
        '12 cm',
        2,
        1,
        0,
        1,
        2,
        19
    ), (
        20,
        'DANEMARK 1875-2015 ACCUMULATION, TIMBRES B-TB /O.',
        'Boite contenant une accumulation de plusieurs centaines de timbres oblitérés dans des cartes 102. A noter: # J4, J21, P2, Antilles danoises #5a. A être examiné. Catalogue du propriétaire: 1944.00$ CAD.',
        1875,
        2,
        1,
        '12 cm',
        3,
        4,
        0,
        1,
        2,
        20
    );

-- --------------------------------------------------------

--

-- Structure de la table `Utilisateur`

--

CREATE TABLE
    `Utilisateur` (
        `utilisateur_id` int(10) NOT NULL,
        `utilisateur_nom` varchar(255) NOT NULL,
        `utilisateur_prenom` varchar(255) NOT NULL,
        `utilisateur_courriel` varchar(255) NOT NULL,
        `utilisateur_mdp` varchar(255) NOT NULL,
        `utilisateur_renouveler_mdp` char(3) NOT NULL DEFAULT 'oui',
        `utilisateur_profil` varchar(255) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

--

-- Déchargement des données de la table `Utilisateur`

--

INSERT INTO
    `Utilisateur` (
        `utilisateur_id`,
        `utilisateur_nom`,
        `utilisateur_prenom`,
        `utilisateur_courriel`,
        `utilisateur_mdp`,
        `utilisateur_renouveler_mdp`,
        `utilisateur_profil`
    )
VALUES (
        1,
        'Redford',
        'Robert',
        'admin@stampee.com',
        SHA2("Stampee1234!", 512),
        'non',
        'Administrateur'
    ), (
        2,
        'Vartan',
        'Sylvie',
        'membre@stampee.com',
        SHA2("Stampee1234!", 512),
        'non',
        'Membre'
    ), (
        3,
        'Testeur',
        'Bob',
        'testeur@stampee.com',
        SHA2("Stampee1234!", 512),
        'non',
        'Membre'
    );

--

-- Index pour les tables déchargées

--

--

-- Index pour la table `Commentaire`

--

ALTER TABLE `Commentaire`
ADD
    PRIMARY KEY (`commentaire_id`),
ADD
    KEY `commentaire_enchere_id` (`commentaire_enchere_id`),
ADD
    KEY `commentaire_utilisateur_id` (`commentaire_utilisateur_id`);

--

-- Index pour la table `Enchere`

--

ALTER TABLE `Enchere`
ADD
    PRIMARY KEY (`enchere_id`),
ADD
    KEY `enchere_utilisateur_id` (`enchere_utilisateur_id`);

--

-- Index pour la table `Favoris`

--

ALTER TABLE `Favoris`
ADD
    PRIMARY KEY (`favoris_id`),
ADD
    KEY `favoris_enchere_id` (`favoris_enchere_id`),
ADD
    KEY `favoris_utilisateur_id` (`favoris_utilisateur_id`);

--

-- Index pour la table `Image`

--

ALTER TABLE `Image`
ADD PRIMARY KEY (`image_id`),
ADD
    UNIQUE KEY `image_principale` (
        `image_principale`,
        `image_timbre_id`
    ),
ADD
    KEY `image_timbre_id` (`image_timbre_id`);

--

-- Index pour la table `Mise`

--

ALTER TABLE `Mise`
ADD PRIMARY KEY (`mise_id`),
ADD
    KEY `mise_utilisateur_id` (`mise_utilisateur_id`),
ADD
    KEY `mise_enchere_id` (`mise_enchere_id`);

--

-- Index pour la table `Pays`

--

ALTER TABLE `Pays` ADD PRIMARY KEY (`pays_id`);

--

-- Index pour la table `Timbre`

--

ALTER TABLE `Timbre`
ADD PRIMARY KEY (`timbre_id`),
ADD
    KEY `timbre_pays_id` (`timbre_pays_id`),
ADD
    KEY `timbre_utilisateur_id` (`timbre_utilisateur_id`),
ADD
    KEY `timbre_enchere_id` (`timbre_enchere_id`);

--

-- Index pour la table `Utilisateur`

--

ALTER TABLE `Utilisateur`
ADD
    PRIMARY KEY (`utilisateur_id`),
ADD
    UNIQUE KEY `utilisateur_courriel` (`utilisateur_courriel`);

--

-- AUTO_INCREMENT pour les tables déchargées

--

--

-- AUTO_INCREMENT pour la table `Commentaire`

--

ALTER TABLE
    `Commentaire` MODIFY `commentaire_id` int(10) NOT NULL AUTO_INCREMENT;

--

-- AUTO_INCREMENT pour la table `Enchere`

--

ALTER TABLE
    `Enchere` MODIFY `enchere_id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 10;

--

-- AUTO_INCREMENT pour la table `Favoris`

--

ALTER TABLE
    `Favoris` MODIFY `favoris_id` int(10) NOT NULL AUTO_INCREMENT;

--

-- AUTO_INCREMENT pour la table `Image`

--

ALTER TABLE
    `Image` MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 8;

--

-- AUTO_INCREMENT pour la table `Mise`

--

ALTER TABLE
    `Mise` MODIFY `mise_id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--

-- AUTO_INCREMENT pour la table `Pays`

--

ALTER TABLE
    `Pays` MODIFY `pays_id` tinyint(3) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 13;

--

-- AUTO_INCREMENT pour la table `Timbre`

--

ALTER TABLE
    `Timbre` MODIFY `timbre_id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 7;

--

-- AUTO_INCREMENT pour la table `Utilisateur`

--

ALTER TABLE
    `Utilisateur` MODIFY `utilisateur_id` int(10) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 9;

--

-- Contraintes pour les tables déchargées

--

--

-- Contraintes pour la table `Commentaire`

--

ALTER TABLE `Commentaire`
ADD
    CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`commentaire_enchere_id`) REFERENCES `Enchere` (`enchere_id`),
ADD
    CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`commentaire_utilisateur_id`) REFERENCES `Utilisateur` (`utilisateur_id`);

--

-- Contraintes pour la table `Enchere`

--

ALTER TABLE `Enchere`
ADD
    CONSTRAINT `enchere_ibfk_1` FOREIGN KEY (`enchere_utilisateur_id`) REFERENCES `Utilisateur` (`utilisateur_id`);

--

-- Contraintes pour la table `Favoris`

--

ALTER TABLE `Favoris`
ADD
    CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`favoris_enchere_id`) REFERENCES `Enchere` (`enchere_id`),
ADD
    CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`favoris_utilisateur_id`) REFERENCES `Utilisateur` (`utilisateur_id`);

--

-- Contraintes pour la table `Image`

--

ALTER TABLE `Image`
ADD
    CONSTRAINT `image_ibfk_1` FOREIGN KEY (`image_timbre_id`) REFERENCES `Timbre` (`timbre_id`);

--

-- Contraintes pour la table `Mise`

--

ALTER TABLE `Mise`
ADD
    CONSTRAINT `mise_ibfk_1` FOREIGN KEY (`mise_utilisateur_id`) REFERENCES `Utilisateur` (`utilisateur_id`),
ADD
    CONSTRAINT `mise_ibfk_2` FOREIGN KEY (`mise_enchere_id`) REFERENCES `Enchere` (`enchere_id`);

--

-- Contraintes pour la table `Timbre`

--

ALTER TABLE `Timbre`
ADD
    CONSTRAINT `timbre_ibfk_1` FOREIGN KEY (`timbre_pays_id`) REFERENCES `Pays` (`pays_id`),
ADD
    CONSTRAINT `timbre_ibfk_2` FOREIGN KEY (`timbre_utilisateur_id`) REFERENCES `Utilisateur` (`utilisateur_id`),
ADD
    CONSTRAINT `timbre_ibfk_3` FOREIGN KEY (`timbre_enchere_id`) REFERENCES `Enchere` (`enchere_id`);

COMMIT;