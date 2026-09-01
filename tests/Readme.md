# Système de Notation Universitaire

## Questions Importantes sur Git et les Bonnes Pratiques


## Questions Bonus sur Git et les Bonnes Pratiques

### 1. Pourquoi le dossier /vendor ne doit-il pas être versionné ?

Le dossier `/vendor` contient toutes les dépendances du projet (librairies externes, packages). Il ne doit pas être versionné pour plusieurs raisons :

- Le dossier vendor peut être très volumineux, ce qui ralentirait énormément le clone et le fonctionnement du dépôt Git.
- Chaque développeur peut régénérer le dossier vendor en exécutant `composer install`, assurant la cohérence et évitant les conflits de versions.
- Cela économise considérablement l'espace sur le serveur et chez chaque contributeur.

### 2. Quelle différence existe entre un commit et un tag ?

#### **Commit**

- C'est une **snapshot du code** à un moment donné, contenant des modifications spécifiques.
- Possède un **SHA-1 unique** et un message descriptif.
- Crée un **historique linéaire** (ou avec branchement) du projet.
- Permet de **revenir à un état antérieur** du code.

#### **Tag**

- C'est une **référence nommée** pointant vers un commit spécifique.
- Généralement utilisé pour marquer les **versions importantes** du projet (ex: v1.0.0, v2.1.0).
- Immobile par défaut : pointe toujours au même commit.
- Peut contenir une **annotation** (auteur, date, message détaillé).

### 3. Pourquoi la branche main doit-elle rester stable ?

La branche `main` (ou `master`) est la **branche de production** et doit toujours être stable :

- Elle représente le code prêt à être déployé
- C'est la base sur laquelle reposent toutes les autres branches
- Utiliser des **branches de développement** (`dev`, `develop`) pour les changements
- Faire des **pull requests** et obtenir des **approbations** avant de fusionner
- Exécuter des **tests automatisés** avant toute fusion



## Partie 1 — Préparation de l'Application

### Architecture de l'Application

L'application suit une architecture en couches (MVC) pour respecter la séparation des responsabilités. Toutes les requêtes passent par un unique point d'entrée (`public/index.php`), protégeant ainsi les fichiers sensibles.

### Structure des Dossiers

```
.
├── composer.json
├── config
│   └── config.php
├── database
├── public
│   └── index.php
├── Readme.md
├── src
│   ├── Controllers
│   ├── Entity
│   ├── Repositories
│   ├── Router
│   │   └── Router.php
│   ├── Services
│   └── Views
├── templates
└── vendor
    ├── autoload.php
    └── composer
        ├── autoload_classmap.php
        ├── autoload_namespaces.php
        ├── autoload_psr4.php
        ├── autoload_real.php
        ├── autoload_static.php
        ├── ClassLoader.php
        └── LICENSE

```

---

## Répartition des Responsabilités

### `public/` — Point d'Entrée Unique

>Seul dossier accessible au navigateur
>Contenu**: `index.php` et assets statiques (`css/`, `js/`, `images/`)
>Initialiser l'application et diriger vers le routeur
>Protège les fichiers sensibles en les plaçant hors du web root


### `src/Controllers/` — Réception des Requêtes

- raiter les requêtes HTTP
- Classes contrôleurs (`HomeController`, `UserController`, etc.)
- **Rôle**:
  - Recevoir et valider les paramètres de la requête
  - Appeler les services appropriés
  - Retourner une réponse (HTML, JSON, redirection)



### `src/Services/` — Traitements Applicatifs

- Logique métier de l'application
- Classes services (`UserService`, `ProductService`, etc.)
- **Rôle**:
  - Validation des données
  - Calculs complexes
  - Orchestration entre modèles et repositories
  - Gestion des transactions

### `src/Repositories/` — Accès aux Données

- Abstraction de la source de données
- Classes repositories (`UserRepository`, `ProductRepository`, etc.)
- **Rôle**:
  - Accéder à la base de données
  - Fournir des méthodes CRUD (`find()`, `save()`, `delete()`)
  - Isoler la logique d'accès aux données

### `src/Router/` — Résolution des URLs

- Analyser l'URL et diriger la requête
- Classe `Router`
- **Rôle**:
  - Parser l'URL (ex: `/users/123` → `UserController@show` avec id=123)
  - Router la requête vers le bon contrôleur et action
  - Gérer les routes paramétrées

### ⚡ **`src/Autoloader.php`** — Chargement Automatique

- **Responsabilité**: Charger automatiquement les classes
- **Standard**: PSR-4
- **Rôle**: Éviter les `require` manuels grâce aux namespaces


---

## Questions d'Architecture

### 1. Pourquoi placer `index.php` dans un dossier `public` ?

**Réponse:**

- Seul le dossier `public/` est accessible via le navigateur
- Les fichiers sensibles (config, code métier) sont protégés en dehors du web root
- Toutes les requêtes passent par `index.php` pour validation
- Permet de centraliser l'initialisation et la gestion des requêtes
- C'est une bonne pratique en architecture MVC recommandée par les standards PSR
- Si le serveur web est mal configuré, les fichiers sensibles restent inaccessibles

### 2. Pourquoi toutes les requêtes devraient-elles passer par ce fichier ?

**Réponse:**

- **Initialisation centralisée**: L'application s'initialise une seule fois (chargement config, autoloader, etc.)
- **Routage unique**: Un seul endroit pour analyser et dispatcher les requêtes
- **Sécurité**: Chaque requête peut être validée et filtrée avant traitement
- **Gestion d'erreurs**: Erreurs gérées de manière cohérente et sécurisée
- **Sessions et authentification**: Vérification centralisée de l'utilisateur
- **Logs et monitoring**: Suivi de toutes les requêtes au même endroit
- **Performances**: Initialisation partagée (pas de rechargement pour chaque fichier)
- **Cohérence**: Un comportement uniforme et prévisible pour toute l'application

###  3. Quels éléments ne devraient jamais se trouver dans le dossier `public` ?

**Réponse:**

❌ **À ÉVITER ABSOLUMENT** :

- Fichiers de configuration (`config.php`, `.env`, `database.yml`)
- Code métier (`src/`, `Models/`, `Services/`, `Repositories/`)
- Identifiants de base de données
- Fichiers de log d'application
- Dépendances Composer (`vendor/`)
- Dossier `database/` avec scripts de migration
- Documentation technique interne
- Scripts d'installation/maintenance


### 4. Comment avez-vous réparti les responsabilités entre vos dossiers ?

**Réponse:**

#### Flux d'une Requête HTTP

```
1. Requête HTTP reçue (GET /users/123)
                    ↓
2. public/index.php → Initialisation (config, autoloader)
                    ↓
3. Router → Analyse l'URL et identifie le contrôleur
                    ↓
4. UserController → Reçoit la requête, récupère l'ID
                    ↓
5. UserService → Effectue la logique métier (validation, calculs)
                    ↓
6. UserRepository → Accède à la base de données
                    ↓
7. User Model → Récupère l'entité métier
                    ↓
8. View → Affiche le modèle en HTML
                    ↓
9. Réponse HTTP retournée au navigateur
```

#### Tableau de Responsabilités

| Couche | Classe | Responsabilité | Interaction |
| -------- | -------- | ----------------- | ------------ |
| **Router** | `Router` | Analyser URL → Appel contrôleur | Reçoit la requête |
| **Contrôleur** | `*Controller` | Traiter requête → Appeler service | Traite la requête |
| **Service** | `*Service` | Logique métier → Appeler repository | Effectue traitements |
| **Repository** | `*Repository` | Accès données → Retourner modèles | Récupère données |
| **Model** | `*Model` | Représenter entité métier | 📦 Encapsule données |
| **View** | `View` | Afficher données en HTML | 🎨 Affiche résultat |

#### Principes de Conception

**Séparation des Préoccupations (Separation of Concerns):**

- Chaque classe a **UNE** responsabilité unique
- Modification facile et localisée
- Tests simplifiés (chaque couche testable indépendamment)

**Réutilisabilité:**

- Services utilisables par plusieurs Contrôleurs
- Repositories utilisables par plusieurs Services
- Models représentent les données de manière centralisée

**Maintenabilité:**

- Trouver où changer facilement
- Comprendre le code plus rapidement
- Éviter les dépendances circulaires

---
