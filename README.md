# Boutique Symfony

Une plateforme e-commerce développée avec Symfony 7.4, dédiée à la vente de produits en ligne. Elle prend en charge tout le parcours d’achat.

## Fonctionnalités

* Catalogue de produits avec catégories et mise en avant sur la page d’accueil
* Panier d’achat (ajout, diminution, suppression d’articles)
* Processus de commande en plusieurs étapes 
* Intégration du paiement Stripe avec confirmation basée sur les sessions
* Génération de factures PDF 
* Inscription utilisateur, connexion et réinitialisation de mot de passe (emails via Mailjet)
* Tableau de bord utilisateur : historique des commandes, carnet d’adresses, changement de mot de passe, liste de souhaits
* Suivi des états des commandes
* Back-office administrateur (EasyAdmin) pour la gestion des produits, catégories, commandes, transporteurs, utilisateurs et bannières de la page d’accueil



## Stack Technique

| Couche          | Technologie                         |
| --------------- | ----------------------------------- |
| Framework       | Symfony 7.4                         |
| Langage         | PHP 8.2+                            |
| ORM             | Doctrine ORM + Migrations           |
| Templates       | Twig                                |
| Frontend        | Bootstrap 5, CSS/JS personnalisés   |
| Paiement        | Stripe (stripe/stripe-php ^13)      |
| Email           | Mailjet (mailjet/mailjet-apiv3-php) |
| PDF             | dompdf ^2.0                         |
| Administration  | EasyAdmin                      |
| Base de données | MySQL / MariaDB                     |



## Prérequis

* PHP >= 8.2 avec les extensions : `pdo_mysql`, `intl`, `mbstring`, `xml`
* Composer
* MySQL ou MariaDB
* Un compte [Stripe](https://stripe.com?utm_source=chatgpt.com) (clés API)
* Un compte [Mailjet](https://www.mailjet.com?utm_source=chatgpt.com) (clés API)



## Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/Rokaya-mk/symfony-7-store.git
cd la-boutique-francaise

# 2. Installer les dépendances PHP
composer install

# 3. Copier et configurer les variables d’environnement
cp .env .env.local
# Modifier .env.local avec vos valeurs (voir section ci-dessous)

# 4. Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5. (Optionnel) Charger les fixtures si disponibles
php bin/console doctrine:fixtures:load

# 6. Lancer le serveur de développement
symfony server:start
# ou
php -S localhost:8000 -t public/
```

---

## Variables d’Environnement

Modifiez le fichier `.env.local` et définissez les variables suivantes :

```dotenv
# Application
APP_ENV=dev
APP_SECRET=your_app_secret_here

# Base de données
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/symonystore?serverVersion=8.0"

# Stripe
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLIC_KEY=pk_test_...

# Mailjet (utilisé comme transport Symfony Mailer)
MAILER_DSN=mailjet+api://YOUR_API_KEY:YOUR_SECRET_KEY@default?from=noreply@yourdomain.com
```


## Base de Données

Le schéma est géré via Doctrine Migrations situé dans le dossier `migrations/`.

```bash
# Appliquer toutes les migrations en attente
php bin/console doctrine:migrations:migrate

# Vérifier le statut des migrations
php bin/console doctrine:migrations:status
```

**Tables principales :** `user`, `address`, `category`, `product`, `carrier`, `order`, `order_detail`, `header`, `user_product` (liste de souhaits)



## Rôles Utilisateurs

| Rôle         | Accès                                                 |
| ------------ | ----------------------------------------------------- |
| `ROLE_USER`  | Espace client (`/compte/*`), commande (`/commande/*`) |
| `ROLE_ADMIN` | Back-office administrateur (`/admin`)                 |

Pour créer un administrateur, inscrivez un compte classique puis modifiez manuellement la colonne `roles` dans la table `user` :

```sql
UPDATE `user` SET roles = '["ROLE_ADMIN"]' WHERE email = 'admin@example.com';
```



