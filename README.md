# IIM-Symfony 🐘

> **ℹ️ N'hésitez pas à me contacter par mail si vous avez un problème avec la configuration du projet :**  
> [enzo.ait-yakoub@edu.devinci.fr](mailto:enzo.ait-yakoub@edu.devinci.fr)

Ce projet Symfony permet à des utilisateurs d'acheter des produits s'ils ont assez de points. Les produits sont créés par les administrateurs qui gèrent également l'ensemble des utilisateurs.

## Prérequis

- PHP >= 8.1
- Composer
- Symfony CLI
- Un serveur de base de données (ex: MySQL)

## Installation

#### Etape 1:

```sh
cd Exo1
composer install
```

#### Etape 2:

Renommer `.env.example` en `.env`

Remplacer `[USER]` et `[PASSWORD]` par les identifants de l'administrateur MySql et remplacer `[DB_NAME]` par la base de données crée.

```py
DATABASE_URL="mysql://[USER]:[PASSWORD]@127.0.0.1:3306/[DB_NAME]"
```

#### Etape 3:

```sh
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```


## Utilisation

- Accédez à l'application via [http://localhost:8000](http://localhost:8000)
- Connectez-vous ou créez un compte utilisateur.

- Pour créer un compte administrateur, vous devez cliquer sur le rôle correspondant au moment de l'inscription d'un utilisateur.

- ![Icône engrenage](./Exo1/public/Images/gear.svg) Le bouton engrenage permet d'accèder aux paramètres. Les paramètres permettent à un utilisateur de modifier son nom et prénom.

> Administrateurs uniquement :

- ![Icône Personne](./Exo1/public/Images/person-fill-gear.svg) Le bouton personne permet d'accèder à l'outil d'adminstration des adminsitrateurs. Cet outil permet aux administrateurs de désactiver ou réactiver des utilisateurs. Il leur permet également de donner 1000 points de facon asynchrone à tous les utilisateurs (avec [Messenger](https://symfony.com/doc/current/messenger.html))

- ![Icône Cloche](./Exo1/public/Images/bell.svg) Le bouton cloche permet aux administrateurs d'accèder à la page des notifications. Cette page affiche les notifications d'achats et de modifications de produits.
