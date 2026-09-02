# Configuration PostgreSQL - Partie 3

## Étapes d'installation

### 1. Configurer les paramètres de connexion

Éditer `config/Database.php` et adapter les paramètres dans le constructeur :

```php
private function __construct()
{
    $this->host = 'localhost';          // ← Votre hôte PostgreSQL
    $this->dbName = 'notation_universitaire';  // ← Votre base de données
    $this->username = 'postgres';       // ← Votre utilisateur
    $this->password = '';               // ← Votre mot de passe
    $this->port = 5432;                 // ← Port PostgreSQL (par défaut 5432)
}
```

### 2. Créer la base de données

```bash
createdb -U postgres -E UTF8 notation_universitaire
```

Ou avec psql :

```bash
psql -U postgres -c "CREATE DATABASE notation_universitaire ENCODING 'UTF8';"
```

### 3. Exécuter le script de schéma

```bash
psql -U postgres -d notation_universitaire -f database/schema.sql
```

### 4. Ajouter une ligne de test

```bash
psql -U postgres -d notation_universitaire -c "
INSERT INTO copie_examen (date_depot, note_brute, note_finale, penalite_appliquee, date_limite)
VALUES ('2024-01-15 10:30:00', 15.5, 15.5, FALSE, '2024-01-20 23:59:59');
"
```

### 5. Consulter les données

```bash
psql -U postgres -d notation_universitaire -c "SELECT * FROM copie_examen;"
```

## Utilisation en PHP

```php
use App\Config\Database;

// Obtenir la connexion
$pdo = Database::getConnection();

// Effectuer une requête
$stmt = $pdo->prepare('SELECT * FROM copie_examen WHERE id = $1');
$stmt->execute([1]);
$copie = $stmt->fetch();
```

## Différences PostgreSQL vs MySQL

| Aspect | MySQL | PostgreSQL |
| --- | --- | --- |
| **Auto-increment** | `INT AUTO_INCREMENT` | `SERIAL` |
| **Dates** | `DATETIME` | `TIMESTAMP` |
| **Nombres décimaux** | `FLOAT` | `NUMERIC(3,2)` |
| **Index** | Inline dans CREATE TABLE | CREATE INDEX séparé |
| **Charset** | `charset=utf8mb4` | ENCODING au niveau DB |
| **Paramètres liés** | `?` | `$1, $2...` |
| **Port par défaut** | `3306` | `5432` |

## Configuration du fichier .env.local

✅ `.env.local` est dans `.gitignore` et ne sera jamais versionné  
✅ Les variables y sont chargées automatiquement par `Database::loadEnv()`  
✅ Valeurs par défaut utilisées si le fichier n'existe pas
