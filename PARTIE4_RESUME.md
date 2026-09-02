# Partie 4 — Transporter les données du formulaire

## 📋 Résumé

Cette partie traite du problème suivant : le navigateur transmet les données sous forme de chaînes de caractères, alors que les traitements métier attendent des types spécifiques (float pour les notes, DateTime pour les dates).

**Principe fondamental** : Il est interdit de transmettre directement `$_POST` aux classes métier.

## 🎯 Solution : Le DTO

Un **DTO (Data Transfer Object)** nommé `SoumettreCopieDTO` a été créé pour :

### Responsabilités ✅

- ✅ Recevoir les valeurs brutes du formulaire (chaînes de caractères)
- ✅ Convertir les données aux types appropriés
- ✅ Valider que les données sont cohérentes
- ✅ Signaler les valeurs absentes ou invalides
- ✅ Fournir des getters typés pour le contrôleur

### Non-responsabilités ❌

- ❌ N'enregistre pas la copie en base de données
- ❌ Ne calcule pas la note finale
- ❌ Ne produit pas du HTML

## 📐 Architecture

### Flux de traitement

```
Formulaire HTML
      ↓
   $_POST (chaînes)
      ↓
SoumettreCopieDTO::fromFormData()
   ↓
   Conversion & Validation
      ↓
   DTO (données typées)
      ↓
   CopieExamenController
      ↓
CopieExamen::create()
      ↓
  Repository::save()
      ↓
   Base de données
```

## 🔧 Implémentation

### Structure du DTO

```php
class SoumettreCopieDTO
{
    private float $noteBrute;      // Note entre 0 et 20
    private DateTime $dateDepot;   // Quand la copie a été soumise
    private DateTime $dateLimite;  // Date limite de soumission

    // Factory method
    public static function fromFormData(array $data): self

    // Getters typés
    public function getNoteBrute(): float
    public function getDateDepot(): DateTime
    public function getDateLimite(): DateTime
    
    // Logique métier
    public function estEnRetard(): bool
    public function toArray(): array
}
```

### Conversions effectuées

#### 1️⃣ Conversion de note brute (string → float)

```php
private static function convertToFloat(mixed $value): float
{
    if (is_numeric($value)) {
        $converted = (float)$value;
        if (!is_finite($converted)) {
            throw new InvalidArgumentException("Pas un nombre valide");
        }
        return $converted;
    }
    throw new InvalidArgumentException("Doit être numérique");
}
```

**Exemple** :

```php
"15.5" → 15.5 (float)
```

#### 2️⃣ Conversion de dates (string → DateTime)

```php
private static function convertToDateTime(mixed $value): DateTime
{
    if ($value instanceof DateTime) {
        return $value;
    }

    if (is_string($value)) {
        return new DateTime($value);
    }

    throw new InvalidArgumentException("String ou DateTime attendu");
}
```

**Exemples** :

```php
"2025-01-20 14:30:00" → DateTime object
"2025-01-20" → DateTime object (minuit)
```

### Validations effectuées

| Validation | Règle | Exception |
| ----------- | ------- | ----------- |
| **Note brute** | Entre 0 et 20 | `InvalidArgumentException` |
| **Champ obligatoire** | Non vide | `InvalidArgumentException` |
| **Format date** | Valide pour `new DateTime()` | `InvalidArgumentException` |
| **Retard** | `dateDepot > dateLimite` | Calculé (pas d'exception) |

## 📝 Fichiers créés

### 1. `src/DTO/SoumettreCopieDTO.php`

Le DTO contenant la logique de conversion et validation.

**Taille** : ~180 lignes
**Dépendances** : `DateTime`, `InvalidArgumentException`

### 2. `templates/config/formulaire-copie.html.php`

Formulaire HTML pour tester le DTO.

**Contient** :

- Champ de note (0-20, step 0.5)
- Champs datetime-local pour les dates
- Validation HTML5
- Styles modernes

### 3. `src/Controllers/CopieExamenController.php`

Contrôleur pour gérer les soumissions.

**Méthode principale** :

```php
public function soumettreCopie(array $postData): array
{
    // 1. Convertir via DTO
    $dto = SoumettreCopieDTO::fromFormData($postData);
    
    // 2. Créer l'entité
    $copie = CopieExamen::create(
        $dto->getDateDepot(),
        $dto->getNoteBrute(),
        $dto->getDateLimite()
    );
    
    // 3. Enregistrer
    return ['success' => true, 'id' => $repository->save($copie)];
}
```

### 4. `run-tests.php`

Script pour exécuter les tests unitaires.

**Exécution** :

```bash
php run-tests.php
```

## 🧪 Tests

Tous les tests passent ✅

```
✓ Conversion valide
✓ Note manquante
✓ Date manquante
✓ Note invalide
✓ Note trop grande
✓ Note trop petite
✓ Détection de retard
✓ Conversion en tableau
```

### Comment exécuter les tests

```bash
cd /home/malang-kiya-ciss/Bureau/Devoir2
php run-tests.php
```

## 📚 Réponses aux questions

### 1️⃣ Pourquoi créer un objet supplémentaire alors que `$_POST` contient déjà les données ?

**Réponse** :

- **Typage** : `$_POST` ne contient que des chaînes. Le DTO garantit des types spécifiques (float, DateTime).
- **Validation** : Le DTO valide les données avant qu'elles ne soient utilisées par les classes métier.
- **Séparation des responsabilités** : Le contrôleur n'a pas à faire de conversion/validation.
- **Maintenabilité** : Un seul endroit pour modifier la logique de conversion.
- **Testabilité** : Facile de tester les conversions indépendamment du formulaire HTML.
- **Sécurité** : Prévient que des données invalides n'atteignent les entités métier.

**Analogie** : Le DTO est comme un portail d'entrée qui effectue les vérifications douanières.

### 2️⃣ Quelle différence observez-vous entre cet objet et `CopieExamen` ?

| Aspect | `SoumettreCopieDTO` | `CopieExamen` |
| -------- | ------------------- | --------------- |
| **Rôle** | Transport de données | Entité métier |
| **ID** | ❌ Non | ✅ Oui (optionnel) |
| **Persistance** | ❌ Non | ✅ Oui (se sauvegarde) |
| **Calculs** | ❌ Non | ✅ Oui (note finale) |
| **Responsabilités** | Conversion, validation | Logique métier complète |
| **Durée de vie** | Éphémère (requête) | Persistante (BD) |
| **Exemple** | Données brutes du formulaire | Copie enregistrée en BD |

**Analogie** :

- `SoumettreCopieDTO` = Un ordre de passage (papier temporaire)
- `CopieExamen` = Un document officiel (enregistré, signé)

### 3️⃣ Cet objet doit-il posséder un identifiant de base de données ?

**Réponse** : **Non**, le DTO n'a pas d'ID.

**Raisons** :

- L'ID n'existe que quand la copie est enregistrée en BD
- Le DTO représente des données **en transit**, pas encore persistées
- Si on voulait travailler avec une copie existante, on utiliserait `CopieExamen::fromDatabase()`

**Exception** : Si nous devions implémenter une modification de copie existante, nous pourrions créer un `ModifierCopieDTO` avec un ID.

### 4️⃣ Où la conversion des chaînes de dates doit-elle avoir lieu ?

**Réponse** : **Dans le DTO**, lors de l'appel à `fromFormData()`.

**Justification** :

✅ **Correct** (dans le DTO) :

```php
$dto = SoumettreCopieDTO::fromFormData($_POST); // Conversion ici
$copie = CopieExamen::create(
    $dto->getDateDepot(),     // DateTime prêt
    $dto->getNoteBrute(),     // float prêt
    $dto->getDateLimite()     // DateTime prêt
);
```

❌ **Incorrect** (dans le contrôleur) :

```php
$dateDepot = new DateTime($_POST['date_depot']); // Logique métier dispersée
$copie = CopieExamen::create(...);
```

❌ **Incorrect** (dans l'entité) :

```php
$copie = CopieExamen::create(
    $_POST['date_depot'],     // Chaîne brute dans l'entité!
    ...
);
```

**Principe** : Les conversions doivent être aussi **proches que possible de la source des données**.

## 🔗 Relations avec d'autres parties

### Dépendances

- **Partie 1** : Architecture et point d'entrée unique
- **Partie 2** : Entité `CopieExamen` et `AbstractDocument`
- **Partie 3** : Repository pour la persistance

### Utilisé par

- **Partie 5** : Intégration avec le formulaire et l'affichage des erreurs
- **Partie 6** : Calcul des notes avec pénalité

## 📊 Résumé des changements

| Fichier | Action | Lignes |
| --------- | -------- | -------- |
| `src/DTO/SoumettreCopieDTO.php` | ✨ Créé | 180 |
| `templates/config/formulaire-copie.html.php` | ✨ Créé | 200 |
| `src/Controllers/CopieExamenController.php` | ✨ Créé | 80 |
| `run-tests.php` | ✨ Créé | 250 |

## ✨ Points clés à retenir

1. **DTO ≠ Entité** : Le DTO ne persiste pas, l'entité oui
2. **Conversion unique** : Un seul endroit pour convertir les données
3. **Validation stricte** : Les données sont validées à l'entrée
4. **Typage fort** : Les getters garantissent les types
5. **Testabilité** : Facile de tester les conversions en isolation
