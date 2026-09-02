# Questions d'Architecture - Partie 3 : Persistance

## ❓ 1. Quelle classe doit être responsable de la connexion ?

**Réponse:**

La classe `App\Config\Database` est responsable de la gestion de la connexion.

**Justification:**

- **Responsabilité unique**: Une seule classe gère la connexion (Pattern Singleton)
- **Centralisation**: Toutes les requêtes passent par la même connexion
- **Isolation**: Les détails de configuration PDO sont encapsulés
- **Testabilité**: Facile de mocker ou remplacer en tests

## Pattern utilisé : Singleton

```php
// Toujours la même instance
$pdo1 = Database::getConnection();
$pdo2 = Database::getConnection();
// $pdo1 === $pdo2  (même objet)
```

---

## ❓ 2. Faut-il créer une nouvelle connexion pour chaque requête SQL ?

**Réponse:**

**NON**, il faut réutiliser la même connexion pour toutes les requêtes.

**Pourquoi?**

| Aspect | Nouvelle connexion | Réutiliser la connexion |
| --- | --- | --- |
| **Performance** | ❌ Très lent (3-5ms par connexion) | ✅ Instantané |
| **Ressources** | ❌ Saturée (limite de connexions MySQL) | ✅ Optimisé |
| **Transactions** | ❌ Impossible (chaque connexion = contexte) | ✅ Possible et atomique |
| **Overhead** | ❌ Énorme | ✅ Minimal |

**Exemple problématique :**

```php
// ❌ MAUVAIS - Crée une connexion pour chaque requête
$pdo1 = new PDO(...);  // Connexion 1
$pdo1->query('INSERT INTO ...');

$pdo2 = new PDO(...);  // Connexion 2
$pdo2->query('UPDATE ...');
// Ressources gaspillées, très lent
```

**Exemple correct :**

```php
// ✅ BON - Réutilise la même connexion
$pdo = Database::getConnection();
$pdo->query('INSERT INTO ...');
$pdo->query('UPDATE ...');
// Efficace et performant
```

---

## ❓ 3. Où placer les identifiants de connexion ?

**Réponse:**

Les paramètres de connexion sont définis directement dans la classe `Database.php`.

**Structure simplifiée:**

```text
Devoir2/
├── composer.json         ← Configuration Composer
├── config/
│   └── Database.php      ← Classe de connexion (avec paramètres)
├── vendor/
│   └── autoload.php      ← Autoloader PSR-4 (généré par Composer)
├── src/
│   ├── Entity/
│   ├── Repository/
│   └── Router/
└── public/
    └── index.php         ← Charge l'autoloader Composer
```

**Paramètres de connexion:**

| Paramètre | Valeur | Modifier dans |
| --------- | ------ | ------------- |
| `host` | `localhost` | config/Database.php line 20 |
| `dbName` | `notation_universitaire` | config/Database.php line 21 |
| `username` | `postgres` | config/Database.php line 22 |
| `password` | `` (vide) | config/Database.php line 23 |
| `port` | `5432` | config/Database.php line 24 |

**Approche simplifiée ✅:**

```php
class Database {
    private function __construct()
    {
        // Paramètres de connexion à la base de données
        $this->host = 'localhost';
        $this->dbName = 'notation_universitaire';
        $this->username = 'postgres';
        $this->password = '';
        $this->port = 5432;
    }
}
```

**Pour modifier les identifiants:** Éditer directement `config/Database.php`

---

## ❓ 4. Pourquoi utiliser PDO ?

**Réponse:**

PDO (PHP Data Objects) offre plusieurs avantages critiques pour la sécurité et la flexibilité.

### Avantage 1: **Protection contre les injections SQL**

```php
// ❌ DANGEREUX - Vulnérable aux injections
$id = $_GET['id'];
$result = $pdo->query("SELECT * FROM copie_examen WHERE id = $id");

// ✅ SÉCURISÉ - Utilise des requêtes préparées
$stmt = $pdo->prepare("SELECT * FROM copie_examen WHERE id = ?");
$stmt->execute([$id]);
$result = $stmt->fetch();
```

Avec requête préparée :

- Le SQL est séparé des données
- Les variables sont automatiquement échappées
- L'attaquant ne peut pas modifier la structure de la requête

### Avantage 2: **Support de plusieurs BD**

```php
// PDO fonctionne avec MySQL, PostgreSQL, SQLite, etc.
// Changement de BD = modification d'une seule ligne

// MySQL
$dsn = 'mysql:host=localhost;dbname=db';

// PostgreSQL
$dsn = 'pgsql:host=localhost;dbname=db';

// SQLite
$dsn = 'sqlite:/path/to/db.sqlite';
```

### Avantage 3: **Configuration centralisée**

```php
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Exceptions au lieu de silence
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Résultats en tableau
    PDO::ATTR_EMULATE_PREPARES => false,  // Force les requêtes préparées
]);
```

### Avantage 4: **Gestion des erreurs**

```php
try {
    $pdo->beginTransaction();
    // Plusieurs opérations
    $pdo->commit();  // Tout ou rien (ACID)
} catch (PDOException $e) {
    $pdo->rollBack();  // Annule tout en cas d'erreur
    throw $e;
}
```

### Comparaison PDO vs mysqli vs requêtes directes

| Critère | Requête directe | mysqli | PDO |
| --- | --- | --- | --- |
| **Sécurité** | ❌ Injections SQL | ⚠️ Manuel | ✅ Automatique |
| **Flexibilité BD** | ❌ MySQL only | ❌ MySQL only | ✅ Multi-BD |
| **Transactions** | ❌ Non | ⚠️ Basique | ✅ Complètes |
| **Configuration** | ❌ Décentralisée | ⚠️ Manuelle | ✅ Centralisée |
| **Préparation** | ❌ Pas disponible | ⚠️ Optionnel | ✅ Forcée |
| **Moderne** | ❌ Obsolète | ⚠️ OK | ✅ Standard actuel |

---

## Résumé d'Architecture

```text
public/index.php
    ↓ Charge l'autoloader Composer
config/Database.php (Singleton)
    ↓ Définit les paramètres de connexion
    ↓ Crée une PDO avec requêtes préparées
App/Repository/*
    ↓ Utilise Database::getConnection()
Requêtes sécurisées et performantes
```

**Principes appliqués:**

1. ✅ Responsabilité unique (Database = une classe)
2. ✅ Réutilisation de ressources (Singleton)
3. ✅ Sécurité (PDO + requêtes préparées)
4. ✅ Secrets externalisés (.env.local)
5. ✅ Configuration centralisée
