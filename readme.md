# STAMPEE, la plateforme d'enchères de timbres

## BD
La base de données Stampee est disponible dans sql/structureStampee.sql et contient la structure de toutes les tables.
Un jeu de données de base est fourni dans sql/jeuDonnees.sql et contient quelques données de base :
- 3 utilisateurs
- 20 timbres
- 20 encheres
- 20 images
- 5 conditions
- 5 couleurs
- 2 profils
- 4 tirages
- 10 mises

---

## LOGIN
3 comptes par défaut :
- Profil Administrateur : 
    courriel = admin@stampee.com
    mdp = Stampee1234!
- Profil Membre :
    courriel = membre@stampee.com
    mdp = Stampee1234!
- Profil Membre :
    courriel = testeur@stampee.com
    mdp = Stampee1234!

---

## DATETIME
Les environnements de développement DEV et WEBDEV sont définis à la date du 20 juillet 2023 20h00.
Il est possible de modifier cette date ou de passer en mode PROD pour revenir à la date du jour dans le fichier app/includes/config.php