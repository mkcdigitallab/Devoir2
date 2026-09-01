# Système de Notation Universitaire

## Questions Importantes sur Git et les Bonnes Pratiques

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
- Exemple : `git commit -m "Ajout de la fonctionnalité de connexion"`

#### **Tag**
- C'est une **référence nommée** pointant vers un commit spécifique.
- Généralement utilisé pour marquer les **versions importantes** du projet (ex: v1.0.0, v2.1.0).
- Immobile par défaut : pointe toujours au même commit (sauf suppression/modification).
- Peut contenir une **annotation** (auteur, date, message détaillé) ou être léger.
- Exemple : `git tag v1.0.0` ou `git tag -a v1.0.0 -m "Version stable 1.0"`

**Résumé** : Les commits suivent les changements du code, tandis que les tags marquent les jalons importants.

---

### 3. Pourquoi la branche main doit-elle rester stable ?

La branche `main` (ou `master`) est la **branche de production** et doit toujours être stable pour plusieurs raisons :

- **Confiance des utilisateurs** : Elle représente le code prêt à être déployé. Les utilisateurs doivent pouvoir se fier à cette branche.
- **Déploiement en production** : C'est généralement depuis `main` qu'on déploie le code en production. Un code instable causera des bugs en production.
- **Point de référence** : C'est la base sur laquelle reposent toutes les autres branches. Un code instable ici affecte tout le projet.
- **Collaboration** : Les équipes savent que `main` est fiable et à jour. Cela évite les conflits et la confusion.
- **Processus de contrôle qualité** : En maintenant `main` stable, on force les développeurs à tester et réviser leur code avant de le fusionner.

**Bonnes pratiques** :
- Utiliser des **branches de développement** (dev, develop) pour les changements en cours.
- Faire des **pull requests** et obtenir des **approbations** avant de fusionner dans main.
- Exécuter des **tests automatisés** avant toute fusion.
- Utiliser le **semantic versioning** avec les tags pour marquer les versions stables.