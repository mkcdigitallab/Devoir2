# Devoir2 — Gestion des copies d'examen

Application PHP orientée objet pour gérer la soumission et la consultation de copies d'examen.

## Architecture

```text
Navigateur
   ↓
public/index.php
   ↓
Router
   ↓
Controller
   ↓
Service applicatif
   ├── Strategy de calcul
   └── Repository
          ↓
         PDO
          ↓
      PostgreSQL
```

## Principes appliqués

- PHP 8.3+ et `declare(strict_types=1)`
- Autoloading PSR-4 via Composer
- DTO pour transporter les données du formulaire
- Strategy pour isoler la règle de calcul de note
- Repository + interface pour isoler la persistance
- Injection de dépendances
- MVC : Controller / Views / Router
- PDO et requêtes préparées
- PostgreSQL
- Secrets hors du code source avec `.env`

## Installation

```bash
composer install
cp .env.example .env
```

Configurer ensuite `.env` avec les paramètres PostgreSQL.

Créer la base puis exécuter :

```bash
psql -U postgres -d notation_universitaire -f database/schema.sql
psql -U postgres -d notation_universitaire -f database/seed.sql
```

## Lancer l'application

```bash
php -S localhost:8000 -t public
```

Puis ouvrir `http://localhost:8000/copies`.

## Routes

| Méthode | Route | Action |
|---|---|---|
| GET | `/copies` | Liste des copies |
| GET | `/copies/create` | Formulaire |
| POST | `/copies` | Soumission |
| GET | `/copies/{id}` | Détail |

## Règle de calcul

Une copie déposée après la date limite reçoit une pénalité de 2 points.
La note finale ne peut jamais être inférieure à 0.

## Tests

Les tests présents dans `tests/` peuvent être lancés individuellement avec PHP :

```bash
php tests/CalculNoteAvecRetardServiceTest.php
php tests/SoumissionCopieServiceTest.php
```
