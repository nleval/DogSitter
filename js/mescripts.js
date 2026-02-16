/**
 * ============================================
 * DOGSYNERGIE - SCRIPTS CENTRALISÉS
 * ============================================
 * Contient tous les scripts JavaScript du site
 * - Notifications
 * - Vérification candidatures en temps réel
 * - Utilitaires
 */

// ============================================
// NOTIFICATION MANAGER
// ============================================

/**
 * Système de notifications DogSynergie
 * Affiche des notifications avec animation
 */
class NotificationManager {
    constructor(containerId = 'notificationsContainer') {
        this.container = document.getElementById(containerId);
    }

    /**
     * Affiche une notification professionnelle
     * @param {string} title - Titre de la notification
     * @param {string} message - Message de la notification
     * @param {string} type - Type: 'success', 'info', 'error'
     * @param {number} duration - Durée d'affichage en ms (0 = pas d'auto-suppression)
     */
    show(title, message, type = 'info', duration = 5000) {
        if (!this.container) {
            console.error('Notifications container not found');
            return null;
        }

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        let iconClass = 'bi-check-circle';
        if (type === 'info') {
            iconClass = 'bi-info-circle';
        } else if (type === 'error') {
            iconClass = 'bi-exclamation-circle';
        } else if (type === 'success') {
            iconClass = 'bi-check-circle';
        }

        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="bi ${iconClass}"></i>
                </div>
                <div class="notification-text">
                    <p class="notification-title">${this.escapeHtml(title)}</p>
                    <p class="notification-message">${this.escapeHtml(message)}</p>
                </div>
                <button class="notification-close" type="button" aria-label="Fermer">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;

        this.container.appendChild(notification);

        // Bouton fermer
        notification.querySelector('.notification-close').addEventListener('click', () => {
            this.remove(notification);
        });

        // Auto-suppression
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    this.remove(notification);
                }
            }, duration);
        }

        return notification;
    }

    /**
     * Supprime une notification avec animation
     */
    remove(notification) {
        notification.classList.add('removing');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }

    /**
     * Échappe les caractères HTML pour la sécurité
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// ============================================
// NOTIFICATION CHECKER - Détection temps réel
// ============================================

/**
 * Système de détection des nouvelles candidatures pour maîtres
 * Fonctionne sur n'importe quelle page du site
 */
class NotificationChecker {
    constructor() {
        this.seenCandidatures = this.loadSeenCandidatures();
            this.seenNotifications = this.loadSeenNotifications();
            this.checkInterval = 15000; // Vérifier toutes les 15 secondes (réduit de 25)
            this.isRunning = false;
        }

        /**
         * Charge les candidatures déjà vues du localStorage
         */
        loadSeenCandidatures() {
            const stored = localStorage.getItem('seenCandidatures');
            return stored ? JSON.parse(stored) : [];
        }

        /**
         * Sauvegarde les candidatures vues
         */
        saveSeenCandidatures() {
            localStorage.setItem('seenCandidatures', JSON.stringify(this.seenCandidatures));
        }

        /**
         * Charge les notifications déjà vues du localStorage
         */
        loadSeenNotifications() {
            const stored = localStorage.getItem('seenNotifications');
            return stored ? JSON.parse(stored) : [];
        }

        /**
         * Sauvegarde les notifications vues
         */
        saveSeenNotifications() {
            localStorage.setItem('seenNotifications', JSON.stringify(this.seenNotifications));
        }

        /**
         * Démarre la vérification des candidatures
         */
        start() {
            if (this.isRunning) return;
            this.isRunning = true;

            // Vérifier immédiatement au démarrage (rapidement)
            setTimeout(() => this.check(), 500);

            // Puis vérifier régulièrement
            this.intervalId = setInterval(() => this.check(), this.checkInterval);
        }

        /**
         * Arrête la vérification
         */
        stop() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
            }
            this.isRunning = false;
        }

        /**
         * Vérifie s'il y a des candidatures nouvelles
         */
        check() {
            // Vérifier les candidatures si maître
            if (window.userIsMaitre) {
                this.checkCandidatures();
            }

            // Vérifier les notifications pour tous les utilisateurs
            this.checkNotifications();
        }

        /**
         * Vérifie les nouvelles candidatures
         */
        checkCandidatures() {
            if (!window.userIsMaitre) return;

            fetch('index.php?controleur=annonce&methode=checkNewCandidatures')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.candidatures) {
                        // Identifier les nouvelles candidatures
                        const newIds = [];
                        
                        data.candidatures.forEach(candidature => {
                            const id = candidature.id_annonce + '_' + candidature.id_candidat;
                            if (!this.seenCandidatures.includes(id)) {
                                newIds.push(id);
                            }
                            // Marquer comme vu
                            if (!this.seenCandidatures.includes(id)) {
                                this.seenCandidatures.push(id);
                            }
                        });

                        // Sauvegarder les mises à jour
                        this.saveSeenCandidatures();

                        // Afficher notification pour les nouvelles candidatures
                        if (newIds.length > 0) {
                            const message = newIds.length === 1 
                                ? 'Vous avez reçu une nouvelle candidature.'
                                : `Vous avez reçu ${newIds.length} nouvelles candidatures.`;
                            
                            notificationManager.show(
                                'Nouvelle candidature',
                                message,
                                'info',
                                6000
                            );
                        }
                    }
                })
                .catch(error => {
                    console.log('Erreur vérification candidatures:', error);
                });
        }

        /**
         * Vérifie les notifications de candidature
         */
        checkNotifications() {
            if (!window.userIsConnected) return;

            fetch('index.php?controleur=annonce&methode=getNotifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.notifications && data.notifications.length > 0) {
                        console.log('📬 Notifications reçues:', data.notifications.length);
                        
                        // Mettre à jour le badge de notifications
                        this.updateNotificationBadge(data.notifications.length);
                        
                        data.notifications.forEach(notification => {
                            const notifId = 'notif_' + notification.id_notification;
                            
                            if (!this.seenNotifications.includes(notifId)) {
                                // Nouvelle notification non vue
                                this.seenNotifications.push(notifId);
                                
                                console.log('📣 Affichage notification:', notification.titre);
                                
                                // Afficher la notification avec durée appropriée
                                const duration = notification.type && notification.type.includes('refusée') ? 6000 : 5000;
                                
                                notificationManager.show(
                                    notification.titre,
                                    notification.message,
                                    'success',
                                    duration
                                );

                                // Marquer comme lue après affichage
                                setTimeout(() => {
                                    const formData = new FormData();
                                    formData.append('id_notification', notification.id_notification);
                                    fetch('index.php?controleur=annonce&methode=markNotificationAsRead', {
                                        method: 'POST',
                                        body: formData
                                    }).catch(err => console.log('Erreur marquage lu:', err));
                                }, duration);
                            }
                        });

                        this.saveSeenNotifications();
                    }
                })
                .catch(error => {
                    console.log('❌ Erreur vérification notifications:', error);
                });
        }

        /**
         * Met à jour le badge de notifications dans le header
         */
        updateNotificationBadge(count) {
            const badge = document.getElementById('notificationBadge');
            const countSpan = document.getElementById('notificationCount');
            
            if (badge && countSpan) {
                if (count > 0) {
                    countSpan.textContent = count > 9 ? '9+' : count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
}

// Initialiser le gestionnaire de notifications global
let notificationManager;
let candidatureChecker;

// Initialiser au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    notificationManager = new NotificationManager();
    candidatureChecker = new NotificationChecker();
    
    console.log('✓ Notification manager initialized');
    console.log('✓ User connected:', window.userIsConnected);
    console.log('✓ User is maitre:', window.userIsMaitre);
    
    // Charger le nombre initial de notifications
    if (window.userIsConnected) {
        loadNotificationCount();
    }
    
    // Vérifier si une notification de candidature soumise doit être affichée
    if (window.showCandidatureSuccess) {
        console.log('✓ Displaying candidature success notification');
        setTimeout(() => {
            notificationManager.show(
                'Candidature soumise',
                'Votre candidature a été enregistrée avec succès. Le maître sera notifié et examinera votre candidature.',
                'success',
                6000
            );
        }, 300);
        window.showCandidatureSuccess = false;
    }
    
    // Démarrer le checker automatiquement pour tous les utilisateurs connectés
    if (window.userIsConnected) {
        console.log('✓ Starting notification checker');
        candidatureChecker.start();
    }
});

/**
 * Charge le nombre de notifications non-lues
 */
function loadNotificationCount() {
    if (!window.userIsConnected) return;
    
    fetch('index.php?controleur=annonce&methode=getNotifications')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.notifications) {
                const unreadCount = data.notifications.length;
                if (candidatureChecker) {
                    candidatureChecker.updateNotificationBadge(unreadCount);
                }
            }
        })
        .catch(error => console.log('Error loading notification count:', error));
}

// ============================================
// FONCTIONS UTILITAIRES POUR CANDIDATURES
// ============================================

/**
 * Accepter une candidature (utilisé dans candidatures.html.twig)
 */
function accepterCandidature(idAnnonce, idCandidat, button) {
    const formData = new FormData();
    formData.append('id_annonce', idAnnonce);
    formData.append('id_candidat', idCandidat);

    fetch('index.php?controleur=annonce&methode=accepterCandidature', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP Error: ' + response.status);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                // Afficher notification de succès
                notificationManager.show(
                    'Succès',
                    'Le promeneur sera informé de votre réponse.',
                    'success',
                    3000
                );
                
                // Effacer la carte de candidature
                const card = button.closest('.list-group-item');
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }, 500);
                
                // Force la vérification des notifications
                setTimeout(() => {
                    if (window.candidatureChecker) {
                        window.candidatureChecker.check();
                    }
                }, 1000);
            } else {
                notificationManager.show(
                    'Erreur',
                    data.message || 'Une erreur est survenue.',
                    'error',
                    4000
                );
            }
        } catch (e) {
            console.error('JSON Parse error:', e, text);
            notificationManager.show(
                'Erreur',
                'Erreur lors du traitement de la réponse.',
                'error',
                4000
            );
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        notificationManager.show(
            'Erreur réseau',
            'Impossible de traiter votre demande.',
            'error',
            4000
        );
    });
}

/**
 * Refuser une candidature (utilisé dans candidatures.html.twig)
 */
function refuserCandidature(idAnnonce, idCandidat, button) {
    const formData = new FormData();
    formData.append('id_annonce', idAnnonce);
    formData.append('id_candidat', idCandidat);

    fetch('index.php?controleur=annonce&methode=refuserCandidature', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP Error: ' + response.status);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                // Afficher notification de succès
                notificationManager.show(
                    'Succès',
                    'Le promeneur sera informé de votre réponse.',
                    'info',
                    3000
                );
                
                // Effacer la carte de candidature
                const card = button.closest('.list-group-item');
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }, 500);
                
                // Force la vérification des notifications
                setTimeout(() => {
                    if (window.candidatureChecker) {
                        window.candidatureChecker.check();
                    }
                }, 1000);
            } else {
                notificationManager.show(
                    'Erreur',
                    data.message || 'Une erreur est survenue.',
                    'error',
                    4000
                );
            }
        } catch (e) {
            console.error('JSON Parse error:', e, text);
            notificationManager.show(
                'Erreur',
                'Erreur lors du traitement de la réponse.',
                'error',
                4000
            );
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        notificationManager.show(
            'Erreur réseau',
            'Impossible de traiter votre demande.',
            'error',
            4000
        );
    });
}

/**
 * Annuler une candidature (utilisé dans mes_candidatures.html.twig)
 */
function annulerCandidature(idAnnonce, button) {
    if (confirm('Confirmez-vous l\'annulation de cette candidature ?')) {
        const formData = new FormData();
        formData.append('id_annonce', idAnnonce);

        fetch('index.php?controleur=annonce&methode=annulerCandidature', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                const card = button.closest('.card');
                const annonceTitle = card.querySelector('h5')?.textContent || 'l\'annonce';
                
                // Animation de suppression
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                card.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    card.remove();
                    
                    // Afficher la notification
                    notificationManager.show(
                        'Candidature annulée',
                        'Votre candidature a été annulée avec succès.',
                        'info',
                        3000
                    );
                }, 300);
            } else {
                notificationManager.show(
                    'Erreur',
                    'Une erreur est survenue lors de l\'annulation.',
                    'error',
                    3000
                );
            }
        })
        .catch(error => {
            notificationManager.show(
                'Erreur réseau',
                'Impossible de traiter votre demande.',
                'error',
                3000
            );
        });
    }
}

/**
 * Force la vérification immédiate des notifications
 * Utile après une action utilisateur
 */
function forceCheckNotifications() {
    if (window.candidatureChecker) {
        console.log('🔄 Force checking notifications...');
        window.candidatureChecker.checkNotifications();
    }
}

