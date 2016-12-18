# Auth Admin PHP
------------------------

## What's this
Projet d'un espace administration en PHP (mode spaghetti).
Ce projet n'utilise pas d'orienté objet, ni de MVC

## Fonctionnalités
le script contient les fonctionnalités suivantes :
- Une partie inscription, avec confirmation par email des comptes utilisateurs.
- Une partie connexion / déconnexion, avec une option Se souvenir de moi basée sur l'utilisation des cookies.
- Une option rappel du mot de passe pour les utilisateurs un petit peu tête en l'air.
- Une partie réservé aux membres avec la possibilités de changer son mot de passe.

## Comment l'installer
Pour cela, il suffit de :
- Copier le repository
- mettre en place la BDD

## Configuration
- Pour configuer l'accès à la base de données : ```inc/db.php```
- Afin de configurer la base de données vous pouvez :
    - Importer la BDD ```admin_basic.sql``` se trouvant dans le dossier courant
