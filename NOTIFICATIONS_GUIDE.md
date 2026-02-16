# � Système Complet de Notifications DogSynergie v2.0

## 🎯 Vue d'ensemble

Un système complet de notifications en temps réel a été implémenté pour gérer toutes les interactions entre maîtres et promeneurs :

- ✅ Notifications pour candidatures soumises/reçues/acceptées/refusées
- 💬 Création automatique de conversations lors d'acceptation
- 🔔 Badge de notifications dans le header
- 📬 Page dédiée pour voir toutes les notifications
- ⏱️ Vérification automatique en temps réel (toutes les 15 secondes)

## 🚀 Nouvelles Fonctionnalités (v2.0)

### 1. **Conversations Automatiques**
- Quand un maître **accepte une candidature**, une conversation est créée automatiquement
- Les deux utilisateurs (maître + promeneur) sont ajoutés à la conversation
- Ils peuvent immédiatement commencer à discuter via les messages
- **Plus besoin de création manuelle de conversation !**

### 2. **Page Notifications Dédiée**
- Nouvelle page accessible via 🔔 dans le header
- Voir **toutes les notifications** avec historique complet
- **Filtrer par type** :
  - Toutes les notifications
  - Candidatures soumises
  - Candidatures reçues
  - Candidatures acceptées
  - Candidatures refusées
- Supprimer les notifications de l'historique

### 3. **Badge de Notifications**
- Affiche le nombre de notifications **non-lues**
- Apparaît automatiquement dans le header (🔔)
- Mène à la page des notifications
- Se met à jour en temps réel

### 4. **Types de Notifications Complets**
| Type | Emoji | Destinataire | Action |
|------|-------|--------------|--------|
| Candidature Soumise | ✅ | Promeneur | Après soumission |
| Candidature Reçue | 🔔 | Maître | Lors de postulation |
| Candidature Acceptée | ✨ | Promeneur | Maître accepte |
| Candidature Refusée | ❌ | Promeneur | Maître refuse |

## 🔄 Flux Complet de Candidature

```
1. PROMENEUR SOUMET CANDIDATURE
   ├─ ✅ Notif: "Candidature Soumise" (promeneur)
   └─ 🔔 Notif: "Nouvelle Candidature Reçue" (maître) + Badge

2. MAÎTRE ACCEPTE CANDIDATURE
   ├─ 💬 CONVERSATION CRÉÉE AUTOMATIQUEMENT
   ├─ ✨ Notif: "Candidature Acceptée" (promeneur)
   ├─ ✅ Confirmation (maître)
   └─ 📨 Les deux peuvent discuter immédiatement

3. OU MAÎTRE REFUSE CANDIDATURE
   └─ ❌ Notif: "Candidature Refusée" (promeneur)
```

## 📱 Accès aux Notifications

### Depuis le Header
- **🔔 Cloche** : Voir toutes les notifications
- Badge rouge affiche le nombre non-lues
- Mis à jour en temps réel

### Page Notifications Complète
- URL: `index.php?controleur=annonce&methode=afficherNotifications`
- Voir tout l'historique
- Filtrer par type
- Supprimer des notifications

### Pop-ups Automatiques
- S'affichent automatiquement en haut à droite
- Disparaissent après ~7 secondes
- Peuvent être fermées manuellement
- Continuent à s'afficher en naviguant

## 🏗️ Architecture Technique

### Backend (PHP)

#### ConversationDAO (nouveau)
```php
createConversation(int $userMain, int $userSecond)
  - Crée une conversation entre deux utilisateurs
  - Vérifie si elle n'existe pas déjà
  - Ajoute automatiquement les deux participants
  - Retourne l'ID de la conversation
```

#### Controller Annonce (amélioré)
```php
accepterCandidature()
  - Accepte la candidature
  - ✅ CRÉE UNE CONVERSATION AUTOMATIQUE
  - Génère les notifications
  - Retourne conversation_id

getAllNotifications()
  - Retourne l'historique complet des notifications
  - JSON API pour le frontend

afficherNotifications()
  - Affiche la page des notifications
  - Permet le filtrage et la suppression
```

#### NotificationDAO
```php
creerNotification(...)      // Crée une notification
getNotifications(...)        // Récupère l'historique
compterNonLues(...)         // Compte les non-lues
marquerCommeLue(...)        // Marque comme lue
supprimerNotification(...)  // Supprime une notification
```

### Frontend (JavaScript)

#### NotificationChecker
```javascript
checkNotifications()
  - Récupère les notifications non-lues
  - Affiche les nouvelles automatiquement
  - Met à jour le badge
  - Marque comme lues après affichage

updateNotificationBadge(count)
  - Met à jour le badge dans le header
  - Affiche "9+" si plus de 9 notifications
```

#### Notifications Template
```html
notifications.html.twig
  - Page dédiée aux notifications
  - Filtrage par type
  - Suppression des notifications
  - Design responsive
```

## 🔄 Système de Suivi en Temps Réel

### Vérification Automatique
- Toutes les **15 secondes** (optimisé)
- Fonctionne sur **toutes les pages**
- Utilise **localStorage** pour éviter les doublons
- **Pas d'impact sur les performances**

### Pour les Maîtres
```
┌─ Candidate Jean postule
├─ Système détecte: Nouvelle candidature
├─ 🔔 Badge "+1"
├─ Pop-up: "Nouvelle Candidature Reçue"
└─ Maître clique Accepter
   ├─ Conversation créée ✓
   ├─ Jean notifié ✓
   └─ Prêts à discuter ✓
```

### Pour les Promeneurs
```
┌─ Soumet candidature
├─ ✅ Pop-up: "Candidature Soumise"
├─ Attend réponse maître...
└─ Maître accepte
   ├─ ✨ Pop-up: "Candidature Acceptée!"
   ├─ Badge: "Aller aux messages"
   └─ Conversation prête ✓
```

### Backend (PHP)
```php
controller_annonce.php
- checkNewCandidatures() // Retourne les candidatures actuelles en JSON

Données retournées:
{
  "success": true,
  "candidatures": [
    {
      "id_annonce": 123,
      "id_candidat": 456,
      "pseudo": "john_doe",
      "titre": "Promenade samedi"
    }
  ]
}
```

### Stockage Local
- **localStorage** : `seenCandidatures` - Array des IDs vus pour éviter les notifications dupliquées

## 📂 Fichiers Modifiés

### Templates Twig
1. **templates/base_template.twig**
   - Ajout du conteneur notifications
   - CSS des notifications
   - JavaScript NotificationManager et NotificationChecker

2. **templates/candidatures.html.twig**
   - Notifications acceptation/refus
   - Démarrage du checker pour maîtres

3. **templates/mes_candidatures.html.twig**
   - Notifications annulation candidature
   - Animation de suppression

4. **templates/annonce.html.twig**
   - Notification soumission candidature

### Contrôleurs PHP
1. **controllers/controller_annonce.class.php**
   - Nouvelle méthode `checkNewCandidatures()` (AJAX)

## 🚀 Utilisation

### Pour les Utilisateurs
Aucune action requise ! Le système fonctionne automatiquement.

### Pour les Développeurs

#### Afficher une notification personnalisée
```javascript
notificationManager.show(
  'Titre',
  'Message de description',
  'success', // ou 'info'
  5000       // durée en ms, 0 pour pas d'auto-fermeture
);
```

#### Démarrer le vérificateur de candidatures
```javascript
if (window.userIsMaitre) {
  candidatureChecker.start();
}
```

#### Arrêter le vérificateur
```javascript
candidatureChecker.stop();
```

## 🎨 Personnalisation

### Modifier les couleurs
Éditez dans **templates/base_template.twig** :
```css
.notification.success .notification-icon {
  background: rgba(154, 173, 90, 0.2);  /* Vert accent */
  color: #9AAD5A;
}
```

### Modifier l'intervalle de vérification
Dans **templates/base_template.twig**, classe `NotificationChecker` :
```javascript
this.checkInterval = 25000; // millisecondes
```

### Modifier la durée d'affichage
Lors de l'appel à `notificationManager.show()`, dernière paramètre :
```javascript
notificationManager.show(title, message, type, 8000); // 8 secondes
```

## 🔒 Sécurité

- ✅ Échappement HTML pour prévenir les XSS
- ✅ Vérification session/autorisation maître
- ✅ Pas d'exposition de données sensibles
- ✅ CORS-safe (fetch avec Content-Type JSON)

## 📊 Performance

- Vérification toutes les **25 secondes** (configurable)
- Payload JSON minimal
- LocalStorage peu de données
- Pas de rechargement page
- Smooth animations à 60fps

## 🐛 Troubleshooting

### Les notifications n'apparaissent pas ?
1. Vérifier la console (F12 > Console)
2. S'assurer que `notificationManager` est initialisé
3. Vérifier que le conteneur `#notificationsContainer` existe

### Les candidatures ne se notifient pas ?
1. Vérifier les logs du serveur
2. S'assurer que `userIsMaitre = true`
3. Vérifier l'URL AJAX : `index.php?controleur=annonce&methode=checkNewCandidatures`

### Les doublons de notifications ?
- LocalStorage corrompu ? Vider et relancer le navegateur
- Vérifier que `seenCandidatures` est bien stocké

## 📝 Notes

- Le système respecte la charte graphique établie
- Compatible avec Bootstrap 5.3.2
- Pas de dépendances externes (jQuery, etc.)
- Fonctionne dans tous les navigateurs modernes
- Design responsive

---

**Version:** 1.0  
**Date:** 16 Février 2026  
**Status:** ✅ Production Ready
