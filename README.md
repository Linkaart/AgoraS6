# 🎮 AgoraS6

Back-office de l'application **AGORA**, une boutique de jeux vidéo : gestion du catalogue, des tournois et des membres. Projet réalisé en formation avec **Symfony 7.1**.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-7.1-000000?logo=symfony&logoColor=white)
![Twig](https://img.shields.io/badge/Twig-3-bacf29)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM_3-fc6a31)

## Fonctionnalités

- **Catalogue** : ajout, modification et suppression des jeux, genres, marques, plateformes et classifications PEGI
- **Tournois** : liste, consultation et création de tournois, rattachés à une catégorie
- **Membres** : CRUD complet avec formulaire Symfony
- **Authentification** : connexion des membres via Symfony Security (authenticator personnalisé)
- **Données de démonstration** : fixtures Doctrine avec Faker

## Stack technique

| Couche | Technologies |
|---|---|
| Back-end | PHP 8.2, Symfony 7.1 (Security, Form, Validator) |
| Vues | Twig |
| Données | Doctrine ORM et Migrations (membres, tournois) · PDO via la classe `PdoAgora` (catalogue AGORA, MySQL) |
| Environnement | Docker Compose (PostgreSQL), Composer |

## Structure

```
src/
├── Controller/        # un contrôleur par ressource (Jeux, Genres, Marques, Pegis, Plateformes, Tournois, Membres…)
│   └── modele/        # PdoAgora : accès PDO à la base du catalogue
├── Entity/            # Membre, Tournois, CatTournois
├── Repository/
├── Form/              # MembreType
├── Security/          # LoginFormAuthenticator
└── DataFixtures/
templates/             # vues Twig
migrations/            # migrations Doctrine
```

## Installation

Prérequis : PHP 8.2+, Composer, Docker (ou PostgreSQL et MySQL installés localement).

```bash
git clone https://github.com/Linkaart/AgoraS6.git
cd AgoraS6
composer install
```

Créez un fichier `.env.local` pour vos valeurs locales :

```dotenv
APP_SECRET=une_chaine_aleatoire
DATABASE_URL="postgresql://app:motdepasse@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
AGORA_DSN="mysql:host=127.0.0.1;dbname=agora;charset=utf8"
AGORA_DB_USER=root
AGORA_DB_PWD=
```

Puis lancez la base, les migrations et le serveur :

```bash
docker compose up -d                       # PostgreSQL
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load     # données de démo (optionnel)
symfony server:start                       # ou : php -S localhost:8000 -t public
```

> Le catalogue (jeux, genres, marques, PEGI, plateformes) est lu dans la base MySQL **agora** via PDO. Son script de création n'est pas inclus dans ce dépôt.
