# Guide de Gestion des Candidatures - DogSynergie

## Vue d'ensemble

Le système de gestion des candidatures permet aux **maîtres** de recevoir et gérer les candidatures pour leurs annonces de promenades, et permet aux **promeneurs** de soumettre des candidatures et suivre leur statut.

## Architecture et Contrôle d'Accès par Rôle

### Rôles Utilisateurs

Le système utilise deux rôles booléens pour chaque utilisateur :
- **`estMaitre`**: Propriétaire de chien qui publie des annonces
- **`estPromeneur`**: Personne qui promène les chiens et répond aux annonces

Un utilisateur peut avoir les deux rôles simultanément.

### Contrôle d'Accès Implémenté

#### Pour les Maîtres

1. **URL**: `index.php?controleur=annonce&methode=voirCandidatures`
2. **Vérification**: Vérifie que `userConnecte.estMaitre === true`
3. **Accès refusé**: Affiche un message d'erreur 403 si l'utilisateur n'est pas maître
4. **Fonctionnalités**:
   - Visualiser toutes les candidatures reçues pour ses annonces
   - Groupées par annonce
   - Affiche informations du candidat (nom, prénom, email)
   - Boutons pour accepter ou refuser les candidatures

#### Pour les Promeneurs

1. **URL**: `index.php?controleur=annonce&methode=verMesCandidatures`
2. **Vérification**: Vérifie que `userConnecte.estPromeneur === true`
3. **Accès refusé**: Affiche un message d'erreur 403 si l'utilisateur n'est pas promeneur
4. **Fonctionnalités**:
   - Visualiser toutes les candidatures soumises
   - Filtrer par annonce, date, tarif
   - Annuler une candidature si nécessaire
   - Voir les détails du maître (nom, email) et de l'annonce

## Fichiers et Modifications

### Fichiers Créés

#### Templates
- `templates/mes_candidatures.html.twig` - Vue pour les promeneurs de leurs candidatures soumises
- `templates/candidatures.html.twig` - Vue améliorée pour les maîtres (remplacement)

### Fichiers Modifiés

#### Contrôleurs
- `controllers/controller_annonce.class.php`
  - Ajout de `verMesCandidatures()` - Affiche les candidatures d'un promeneur
  - Ajout de `accepterCandidature()` - Accepte une candidature
  - Ajout de `refuserCandidature()` - Refuse une candidature
  - Ajout de `annulerCandidature()` - Annule une candidature soumise

#### DAOs
- `modeles/Annonce.dao.php`
  - Ajout de `getCandidaturesBySubmittedBy()` - Récupère candidatures soumises par un utilisateur
  - Ajout de `accepterCandidature()` - Accepte une candidature
  - Ajout de `refuserCandidature()` - Refuse une candidature
  - Ajout de `supprimerCandidature()` - Annule une candidature

#### Profil Utilisateur
- `templates/utilisateur.html.twig` - Ajout de boutons rapides pour accéder aux candidatures

## Flux de Travail

### Pour un Maître

```
1. Vue du profil utilisateur
   ↓
2. Clic sur "Candidatures reçues" (si estMaitre = true)
   ↓
3. Vérification: estMaitre = true? ✓
   ↓
4. Affichage des candidatures groupées par annonce
   ↓
5. Options: Accepter ou Refuser chaque candidature
   ↓
6. Appel AJAX vers accepterCandidature() ou refuserCandidature()
   ↓
7. Mise à jour de l'interface (couleur, badge)
```

### Pour un Promeneur

```
1. Vue du profil utilisateur
   ↓
2. Clic sur "Mes candidatures" (si estPromeneur = true)
   ↓
3. Vérification: estPromeneur = true? ✓
   ↓
4. Affichage des candidatures soumises
   ↓
5. Options: Voir l'annonce ou Annuler la candidature
   ↓
6. Appel via POST vers annulerCandidature()
   ↓
7. Redirection avec message de confirmation
```

## Endpoints API

### Pour les Maîtres

**GET** `index.php?controleur=annonce&methode=voirCandidatures`
- Affiche la page des candidatures reçues
- Requête HEAD: Vérifie que l'utilisateur est authentifié et maître

**POST** `index.php?controleur=annonce&methode=accepterCandidature`
- Body: `{id_annonce: number, id_candidat: number}`
- Réponse: JSON `{success: boolean, message: string}`
- Requête HEAD: Session active, estMaitre = true

**POST** `index.php?controleur=annonce&methode=refuserCandidature`
- Body: `{id_annonce: number, id_candidat: number}`
- Réponse: JSON `{success: boolean, message: string}`
- Requête HEAD: Session active, estMaitre = true

### Pour les Promeneurs

**GET** `index.php?controleur=annonce&methode=verMesCandidatures`
- Affiche la page des candidatures soumises
- Requête HEAD: Vérifie que l'utilisateur est authentifié et promeneur

**POST** `index.php?controleur=annonce&methode=annulerCandidature`
- Body/GET: `{id_annonce: number}`
- Réponse: Redirection vers verMesCandidatures
- Requête HEAD: Session active, estPromeneur = true

## Structure de la Base de Données

### Table `dog_Répond`

```sql
CREATE TABLE `dog_Répond` (
  `id_annonce` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  PRIMARY KEY (`id_annonce`, `id_utilisateur`)
);
```

**Future amélioration** : Ajouter une colonne `statut` (ENUM: 'en_attente', 'acceptée', 'refusée') pour un meilleur suivi des candidatures.

## Améliorations Futures

### Phase 1 - À Implémenter
1. **Colonne Statut** : Ajouter colonne `statut` dans `dog_Répond`
   - Permettra de savoir si candidature est acceptée/refusée
   - Affichera le statut pour les promeneurs

2. **Notifications** : Système de notification pour les candidatures
   - Email au promeneur quand candidature acceptée/refusée
   - Email au maître quand nouvelle candidature

3. **Avis Post-Promenade** : Système d'évaluation
   - Lier les candidatures acceptées aux avis générés

### Phase 2 - Optimisations
1. **Filtrage Avancé** : Filtrer candidatures par statut, date, tarif
2. **Pagination** : Paginer les listes de candidatures longues
3. **Export** : Exporter les candidatures en CSV/PDF
4. **Historique** : Conserver l'historique des candidatures

## Sécurité

### Validations Implémentées

✅ Vérification de session active
✅ Vérification du rôle utilisateur
✅ Vérification que l'annonce appartient à l'utilisateur (pour maîtres)
✅ Vérification que l'utilisateur a soumis la candidature (pour promeneurs)
✅ Protection contre les requêtes non authentifiées
✅ Gestion des erreurs 403 et 404

### Headers de Sécurité Appliqués

- HTTP Status 403 : Accès refusé (rôle insuffisant)
- HTTP Status 404 : Ressource non trouvée
- HTTP Status 400 : Requête invalide

## Intégration dans le Menu Utilisateur

Les boutons de navigation vers les candidatures sont affichés dans la **page du profil utilisateur** :

- Si `estMaitre = true` : Bouton "📋 Candidatures reçues"
- Si `estPromeneur = true` : Bouton "👍 Mes candidatures"

Les deux boutons peuvent s'afficher si l'utilisateur a les deux rôles.

## Tests Recommandés

### Test 1 : Accès Maître
1. Se connecter avec un compte maître
2. Aller sur `/profil` puis "Candidatures reçues"
3. Vérifier l'affichage des candidatures
4. Tester les boutons Accepter/Refuser
5. Vérifier les messages de confirmation

### Test 2 : Accès Promeneur
1. Se connecter avec un compte promeneur
2. Aller sur `/profil` puis "Mes candidatures"
3. Vérifier l'affichage des candidatures soumises
4. Tester le bouton Annuler
5. Vérifier la redirection et le message

### Test 3 : Contrôle d'Accès
1. Utilisateur non authentifié → Redirection login
2. Promeneur accède à "Candidatures reçues" → Erreur 403
3. Maître accède à "Mes candidatures" → Erreur 403
4. Utilisateur accède à annonces d'un autre → Erreur 403

## Support et Maintenance

Pour toute question ou bug report, consulter les logs :
- `logs/errors.log` - Erreurs PHP
- `logs/access.log` - Accès utilisateurs

---

**Version** : 1.0  
**Date** : 15 Février 2026  
**Auteur** : DogSynergie Development Team
