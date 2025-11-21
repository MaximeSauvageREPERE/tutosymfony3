# Fixtures Symfony

## Description
Ce projet utilise DoctrineFixturesBundle pour générer des données de test.

## Contenu des fixtures
- **2 utilisateurs** :
  - **User** : username = `user`, password = `user`, rôle = `ROLE_USER`
  - **Admin** : username = `admin`, password = `admin`, rôle = `ROLE_ADMIN`
- **8 catégories** : Électronique, Vêtements, Alimentation, Maison, Sport, Livres, Jouets, Beauté
- **10 tags** : Nouveauté, Promo, Best-seller, Écologique, Premium, Tendance, Exclusif, Soldes, Recommandé, Bio
- **50 produits** réalistes avec descriptions, prix, catégories et tags

## Commandes

### Charger les fixtures (remplace toutes les données)
```bash
php bin/console doctrine:fixtures:load
```

### Charger les fixtures sans confirmation
```bash
php bin/console doctrine:fixtures:load --no-interaction
```

### Ajouter les fixtures sans purger la base
```bash
php bin/console doctrine:fixtures:load --append
```

## ⚠️ Attention
La commande `doctrine:fixtures:load` **supprime toutes les données existantes** dans la base de données avant de charger les fixtures. Utilisez l'option `--append` pour conserver les données existantes.

## Personnalisation
Pour modifier les données de test, éditez le fichier :
`src/DataFixtures/AppFixtures.php`
