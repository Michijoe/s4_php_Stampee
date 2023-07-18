# BD

La base de données Stampee est disponible dans sql/stampee.sql et contient la structure de toutes les tables et quelques données de base :
- 3 utilisateurs
- 20 timbres
- 20 encheres
- 20 images
- 5 conditions
- 5 couleurs
- 2 profils
- 4 tirages

---

# LOGIN

Deux comptes par défaut :
- Profil Administrateur : 
    courriel = admin@stampee.com
    mdp = Stampee1234!
- Profil Membre :
    courriel = membre@stampee.com
    mdp = Stampee1234!
- Profil Membre :
    courriel = testeur@stampee.com
    mdp = Stampee1234!

Lorsqu'un utilisateur est ajouté via l'interface admin, un mot de passe temporaire est envoyé à l'adresse courriel renseigné (en mode DEV, dans le dossier MOCKS). La première fois que l'utilisateur se connecte avec ce mdp temporaire, on lui demande de rentrer un nouveau mdp valide.

Lorsqu'un utilisateur se créé un compte directement via l'interface frontend, un mot de passe valide lui est directement demandé.

Il est impossible de modifier les deux premiers utilisateurs (membre et admin).

---

# DATETIME

L'environnement de développement est défini à la date du 14 juillet 2023 20h00.

Il est possible de modifier cette date ou de passer en mode PROD pour revenir à la date du jour via le fichier app/includes/config.php

---

# COMPTE MEMBRE

Mes mises
Le membre a accès à la liste de toutes les enchères auquelles il a participé, actives ou passées.
La liste contient toutes les enchères auquelles il a participé, qu'il les ait remportées ou non.

L'administrateur verra toutes les mises de tous les utilisateurs.
