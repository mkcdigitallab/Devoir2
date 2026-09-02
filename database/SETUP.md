# Configuration de la Base de Données

## Étapes d'installation

### 1. Configurer les paramètres de connexion

Éditer `config/Database.php` et adapter les paramètres dans le constructeur :

```php
private function __construct()
{
    $this->host = 'localhost';          // ← Votre hôte (localhost ou IP)
    $this->dbName = 'notation_universitaire';  // ← Votre base de données
    $this->username = 'postgres';       // ← Votre utilisateur (root pour MySQL)
    $this->password = '';               // ← Votre mot de passe
    $this->port = 5432;                 // ← Port (5432 PostgreSQL, 3306 MySQL)
}
```

### 2. Créer la base de données

```bash
mysql -u root -p -e "CREATE DATABASE notation_universitaire CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Exécuter le script de schéma

```bash
mysql -u root -p notation_universitaire < database/schema.sql
```

### 4. Ajouter une ligne de test

```bash
mysql -u root -p notation_universitaire -e "
INSERT INTO copie_examen (date_depot, note_brute, note_finale, penalite_appliquee, date_limite)
VALUES ('2024-01-15 10:30:00', 15.5, 15.5, FALSE, '2024-01-20 23:59:59');
"
```

### 5. Consulter les données

```bash
mysql -u root -p notation_universitaire -e "SELECT * FROM copie_examen;"
```

## Utilisation en PHP

```php
use App\Config\Database;

// Obtenir la connexion
$pdo = Database::getConnection();

// Effectuer une requête
$stmt = $pdo->prepare('SELECT * FROM copie_examen WHERE id = ?');
$stmt->execute([1]);
$copie = $stmt->fetch();
```

## Avantages de cette approche

- ✅ Identifiants sensibles séparés du code source (`.env.local` dans `.gitignore`)
- ✅ Connexion unique réutilisée (Pattern Singleton)
- ✅ Utilisation de PDO pour la sécurité (préparation des requêtes)
- ✅ Configuration flexible selon l'environnement (local, staging, production)
- ✅ Gestion centralisée de la connexion
