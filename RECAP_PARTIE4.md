# 📋 Récapitulatif Partie 4 - Transporter les données du formulaire

## ✅ Travail réalisé

### 🎯 Objectif

Créer un objet **DTO (Data Transfer Object)** nommé `SoumettreCopieDTO` pour transporter et valider les données brutes du formulaire avant qu'elles ne soient traitées par les classes métier.

### 📁 Fichiers créés

#### 1. **`src/DTO/SoumettreCopieDTO.php`** ⭐

Le cœur de la Partie 4 - Le DTO qui :

- ✅ Reçoit les données brutes du formulaire (`$_POST`)
- ✅ Convertit les chaînes en types appropriés (float, DateTime)
- ✅ Valide que les données sont cohérentes
- ✅ Fournit des getters typés garantissant les types
- ✅ Détecte si une copie a été déposée en retard

**Responsabilités** :

- Conversion string → float (pour la note)
- Conversion string → DateTime (pour les dates)
- Validation de plage (0 ≤ note ≤ 20)
- Détection de champs obligatoires manquants
- Gestion des erreurs avec `InvalidArgumentException`

**Non-responsabilités** :

- ❌ N'enregistre pas en BDD
- ❌ Ne calcule pas la note finale
- ❌ Ne produit pas de HTML

#### 2. **`templates/config/formulaire-copie.html.php`**

Un formulaire HTML pour tester le DTO :

- Input pour la note (0-20, step 0.5)
- Datetime-local pour date de dépôt
- Datetime-local pour date limite
- Styles modernes et responsifs
- Valeurs pré-remplies avec dates par défaut

#### 3. **`src/Controllers/CopieExamenController.php`**

Contrôleur pour utiliser le DTO :

- `afficherFormulaire()` - Affiche le formulaire
- `soumettreCopie(array $postData)` - Traite la soumission :
  - Convertit via DTO
  - Crée l'entité CopieExamen
  - Enregistre en BDD
  - Retourne un tableau de résultat

#### 4. **`run-tests.php`**

Script exécutant 8 tests unitaires :

- ✓ Conversion valide
- ✓ Note manquante
- ✓ Date manquante
- ✓ Note invalide
- ✓ Note trop grande
- ✓ Note trop petite
- ✓ Détection de retard
- ✓ Conversion en tableau

#### 5. **`PARTIE4_RESUME.md`**

Documentation complète avec :

- Architecture du flux de traitement
- Explications détaillées des conversions
- Tableau des validations
- Réponses à toutes les questions
- Comparaisons DTO vs Entité

### 🧪 Tests

```bash
cd /home/malang-kiya-ciss/Bureau/Devoir2
php run-tests.php
```

**Résultat** : ✅ **8/8 tests réussis**

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

---

## 🔀 Git - Branche et Tags

### Branche créée

```
partie/partie-04
```

### Commits créés (3)

1. **`c79ccf7`** - `feat: transporter les données de soumission`
   - Création du DTO `SoumettreCopieDTO.php`
   - Logique de conversion et validation

2. **`65d8f36`** - `feat: convertir les dates du formulaire`
   - Formulaire HTML
   - Script de tests

3. **`65a52cf`** - `feat: valider les données reçues`
   - Contrôleur pour utiliser le DTO
   - Tests unitaires
   - Documentation

### Tag créé

```
v0.4.0 - "Partie 4 - Transporter les données du formulaire"
```

---

## 📚 Réponses aux questions

### 1️⃣ Pourquoi créer un DTO supplémentaire ?

| Raison | Explication |
| -------- | ------------- |
| **Typage** | Convertit `$_POST` (toutes chaînes) en types spécifiques |
| **Validation** | Valide avant que les données atteignent les classes métier |
| **Séparation** | Le contrôleur ne fait pas de conversion/validation |
| **Maintenabilité** | Un seul endroit pour modifier la logique |
| **Testabilité** | Facile de tester indépendamment du formulaire |
| **Sécurité** | Empêche les données invalides d'atteindre le métier |

### 2️⃣ Différence entre DTO et Entité

| Aspect | DTO | Entité |
| -------- | ----- | -------- |
| **Rôle** | Transport/conversion | Métier/logique |
| **ID** | ❌ Non | ✅ Oui |
| **Persistance** | ❌ Non (éphémère) | ✅ Oui (BDD) |
| **Calculs** | ❌ Non | ✅ Oui |
| **Cycle de vie** | Requête HTTP | Persiste |

### 3️⃣ Le DTO doit-il avoir un ID ?

**Réponse : Non**

- L'ID n'existe que quand la copie est enregistrée en BDD
- Le DTO représente des données **en transit**, pas persistées
- Le DTO disparaît après traitement de la requête

### 4️⃣ Où la conversion doit-elle avoir lieu ?

**Réponse : Dans le DTO** via `fromFormData()`

✅ Correct :

```php
$dto = SoumettreCopieDTO::fromFormData($_POST);
$copie = CopieExamen::create(
    $dto->getDateDepot(),   // DateTime prêt
    $dto->getNoteBrute(),   // float prêt
    $dto->getDateLimite()   // DateTime prêt
);
```

❌ Incorrect (dans le contrôleur) :

```php
$dateDepot = new DateTime($_POST['date_depot']);
// Logique métier dispersée!
```

---

## 🔍 Architecture

### Flux complet

```
Utilisateur remplit le formulaire
                ↓
        Clique "Soumettre"
                ↓
        POST /soumission
                ↓
    CopieExamenController
                ↓
  SoumettreCopieDTO::fromFormData($_POST)
                ↓
    ✓ Conversion string → float/DateTime
    ✓ Validation des données
                ↓
   CopieExamen::create()
                ↓
   CopieExamenRepository::save()
                ↓
        Base de Données
                ↓
        Réponse à l'utilisateur
```

### Classes impliquées

```
src/DTO/
    └── SoumettreCopieDTO.php
        - fromFormData(array) : self
        - convertToFloat(mixed) : float
        - convertToDateTime(mixed) : DateTime
        - validateNoteBrute(float) : void
        - getNoteBrute() : float
        - getDateDepot() : DateTime
        - getDateLimite() : DateTime
        - estEnRetard() : bool
        - toArray() : array

src/Controllers/
    └── CopieExamenController.php
        - soumettreCopie(array) : array

src/Entity/
    └── CopieExamen.php (existant)
        - create(...) utilise le DTO
```

---

## 📊 Métriques

| Métrique | Valeur |
| ---------- | -------- |
| **Fichiers créés** | 5 |
| **Lignes de code** | ~700 |
| **Tests** | 8 (100% réussite) |
| **Commits** | 3 |
| **Tags** | 1 (v0.4.0) |
| **Documentation** | 320+ lignes |

---

## 🎓 Concepts clés appris

### 1. DTO Pattern

- Objet dédié au transport de données
- Distinct de l'entité métier
- Responsable de la validation à l'entrée

### 2. Conversion de types

```php
string "15.5" → float 15.5
string "2025-01-20" → DateTime object
```

### 3. Validation au point d'entrée

- Valider au plus tôt (au point d'entrée du DTO)
- Éviter de laisser des données invalides progresser

### 4. Séparation des responsabilités

- Transport des données (DTO)
- Logique métier (Entité)
- Persistance (Repository)

---

## 🚀 Prochaine étape : Partie 5

La Partie 5 probablement traitera :

- Intégration du formulaire avec le routeur
- Affichage des erreurs de validation
- Redirection après succès
- Sessions et messages flash

---

## 📝 Comment utiliser

### Soumettre une copie via le formulaire

```bash
# 1. Afficher le formulaire
GET /submit

# 2. L'utilisateur remplit et soumet
POST /submit
Content-Type: application/x-www-form-urlencoded

note_brute=15.5&date_depot=2025-01-20T14:30&date_limite=2025-01-25T23:59
```

### Utiliser le DTO directement (pour les tests)

```php
$data = [
    'note_brute' => '15.5',
    'date_depot' => '2025-01-20 14:30:00',
    'date_limite' => '2025-01-25 23:59:59',
];

$dto = SoumettreCopieDTO::fromFormData($data);

echo $dto->getNoteBrute();      // 15.5 (float)
echo $dto->getDateDepot();      // DateTime object
echo $dto->estEnRetard();       // false
```

---

## ✨ Points clés

- ✅ DTO ≠ Entité (transport ≠ métier)
- ✅ Conversion unique (un seul endroit)
- ✅ Validation stricte (à l'entrée)
- ✅ Typage fort (getters typés)
- ✅ Testabilité (tests en isolation)
- ✅ Séparation (responsibilities claires)

---

## 📞 Support

Voir [PARTIE4_RESUME.md](PARTIE4_RESUME.md) pour la documentation détaillée.

---

**Branche** : `partie/partie-04`  
**Tag** : `v0.4.0`  
**Status** : ✅ Complétée
