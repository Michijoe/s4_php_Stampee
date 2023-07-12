# BD

La base de données Stampee est disponible dans sql/stampee.sql et contient la structure de toutes les tables et quelques données de base :
- 2 utilisateurs
- 2o timbres
- 2o encheres
- 20 images

---

# LOGIN

Deux comptes par défaut :
- Profil Administrateur : 
    courriel = admin@stampee.com
    mdp = Stampee1234!
- Profil Membre :
    courriel = membre@stampee.com
    mdp = Stampee1234!

Lorsqu'un utilisateur est ajouté via l'interface admin, un mot de passe temporaire est envoyé à l'adresse courriel renseigné (en mode DEV, dans le dossier MOCKS). La première fois que l'utilisateur se connecte avec ce mdp temporaire, on lui demande de rentrer un nouveau mdp valide.

Lorsqu'un utilisateur se créé un compte directement via l'interface frontend, un mot de passe valide lui est directement demandé.

Il est impossible de modifier les deux premiers utilisateurs (membre et admin).

---

# DATETIME

L'environnement de développement est défini à la date du 14 juillet 2023 20h00.

Il est possible de modifier cette date ou de passer en mode PROD pour revenir à la date du jour via le fichier app/includes/config.php
