/**
 * ============================================
 * DOGSYNERGIE - SCRIPTS CENTRALISÉS
 * ============================================
 * Contient tous les scripts JavaScript du site
 * - Notifications
 * - Vérification candidatures en temps réel
 * - Utilitaires
 */

(function initGlobalWindowContext() {
    const body = document.body;
    if (!body) return;

    const isConnected = body.dataset.userIsConnected === 'true';
    const isMaitre = body.dataset.userIsMaitre === 'true';
    const userIdRaw = body.dataset.userId;

    window.userIsConnected = isConnected;
    window.userIsMaitre = isMaitre;
    window.userId = userIdRaw ? Number(userIdRaw) : null;
})();

function initTarteAuCitron() {
    if (typeof tarteaucitron === 'undefined') return;

    tarteaucitron.init({
        highPrivacy: true,
        AcceptAllCta: true,
        DenyAllCta: true,
        privacyUrl: "",
        orientation: "bottom",
        showAlertSmall: false,
        cookieslist: true
    });

    tarteaucitron.user.gajsUa = 'G-F2W0X2M53T';
    tarteaucitron.user.gajsMore = function () {};
    tarteaucitron.job = tarteaucitron.job || [];
    tarteaucitron.job.push('gajs');
}

function initAnnonceFiltersPage() {
    const searchBtn = document.getElementById('searchBtn');
    const resetBtn = document.getElementById('resetBtn');
    const annoncesContainer = document.getElementById('annoncesContainer');
    const noResultsFiltered = document.getElementById('noResultsFiltered');
    if (!searchBtn || !resetBtn || !annoncesContainer || !noResultsFiltered) return;

    function filterAnnonces() {
        const ville = (document.getElementById('villeCodePostal')?.value || '').toLowerCase().trim();
        const date = document.getElementById('disponibilites')?.value || '';
        const duree = parseInt(document.getElementById('dureePromenade')?.value || '0', 10) || 0;

        const cards = document.querySelectorAll('.annonce-card');
        let visibleCount = 0;

        cards.forEach(card => {
            let match = true;

            if (ville && !(card.dataset.ville || '').includes(ville)) {
                match = false;
            }

            if (date && (card.dataset.date || '') !== date) {
                match = false;
            }

            if (duree > 0 && parseInt(card.dataset.duree || '0', 10) > duree) {
                match = false;
            }

            card.style.display = match ? 'flex' : 'none';
            if (match) visibleCount++;
        });

        if (visibleCount === 0) {
            noResultsFiltered.style.display = 'block';
            annoncesContainer.style.display = 'none';
        } else {
            noResultsFiltered.style.display = 'none';
            annoncesContainer.style.display = 'grid';
        }
    }

    function resetFilters() {
        const villeInput = document.getElementById('villeCodePostal');
        const dateInput = document.getElementById('disponibilites');
        const dureeInput = document.getElementById('dureePromenade');
        if (villeInput) villeInput.value = '';
        if (dateInput) dateInput.value = '';
        if (dureeInput) dureeInput.value = '';

        document.querySelectorAll('.annonce-card').forEach(card => {
            card.style.display = 'flex';
        });

        noResultsFiltered.style.display = 'none';
        annoncesContainer.style.display = 'grid';
    }

    searchBtn.addEventListener('click', filterAnnonces);
    resetBtn.addEventListener('click', resetFilters);
}

function initAjouterAvisStars() {
    const rating = document.querySelector('.rating');
    const input = document.getElementById('note');
    if (!rating || !input) return;

    const stars = rating.querySelectorAll('img[data-star]');

    function renderStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.dataset.star || '0', 10);
            star.src = starValue <= value ? 'images/Nonos2.svg' : 'images/Nonos.svg';
        });
    }

    const initial = parseInt(input.value || rating.dataset.value || '3', 10);
    renderStars(initial);

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.dataset.star || '0', 10);
            input.value = value;
            renderStars(value);
        });
    });
}

function initAjouterChienDropzone() {
    const dropZone = document.getElementById('dropZone');
    const form = document.getElementById('chienForm');
    const fileInput = document.getElementById('photo');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    if (!dropZone || !form || !fileInput || !fileInfo || !fileName) return;

    const allowedTypes = ['image/jpeg', 'image/png'];
    const maxFileSize = 2 * 1024 * 1024;

    function updateFileInfo() {
        if (!fileInput.files || fileInput.files.length === 0) {
            fileInfo.style.display = 'none';
            return;
        }

        const file = fileInput.files[0];
        if (!allowedTypes.includes(file.type)) {
            alert('Format invalide. Utilisez JPEG ou PNG.');
            fileInput.value = '';
            fileInfo.style.display = 'none';
            return;
        }

        if (file.size > maxFileSize) {
            alert('Fichier trop volumineux. Maximum 2MB.');
            fileInput.value = '';
            fileInfo.style.display = 'none';
            return;
        }

        fileName.textContent = `${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
        fileInfo.style.display = 'block';
    }

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', event => {
        event.preventDefault();
    });
    dropZone.addEventListener('dragleave', () => {});
    dropZone.addEventListener('drop', event => {
        event.preventDefault();
        if (event.dataTransfer.files.length > 0) {
            fileInput.files = event.dataTransfer.files;
            updateFileInfo();
        }
    });

    fileInput.addEventListener('change', updateFileInfo);
    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
        }
    });
}

function initMessagesListSwipe() {
    const rows = document.querySelectorAll('.dm-swipe');
    if (!rows.length) return;

    const closeRow = row => {
        const content = row.querySelector('.dm-swipe-content');
        row.classList.remove('open');
        if (content) {
            content.style.transform = '';
        }
    };

    const closeOtherRows = currentRow => {
        rows.forEach(row => {
            if (row !== currentRow) {
                closeRow(row);
            }
        });
    };

    rows.forEach(row => {
        const handle = row.querySelector('.dm-swipe-handle');
        const actions = row.querySelector('.dm-swipe-actions');
        const content = row.querySelector('.dm-swipe-content');
        let startX = 0;
        let currentX = 0;
        let touching = false;

        const getRevealWidth = () => {
            if (!actions) return 110;
            const width = Math.ceil(actions.getBoundingClientRect().width);
            return width > 0 ? width : 110;
        };

        const openRow = () => {
            closeOtherRows(row);
            row.classList.add('open');
            if (content) {
                content.style.transform = `translateX(-${getRevealWidth()}px)`;
            }
        };

        const closeCurrentRow = () => closeRow(row);

        if (handle) {
            handle.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();

                if (row.classList.contains('open')) {
                    closeCurrentRow();
                } else {
                    openRow();
                }
            });
        }

        row.addEventListener('touchstart', event => {
            if (event.touches.length !== 1) return;
            touching = true;
            startX = event.touches[0].clientX;
            currentX = startX;
        });

        row.addEventListener('touchmove', event => {
            if (!touching) return;
            currentX = event.touches[0].clientX;
        });

        row.addEventListener('touchend', () => {
            if (!touching) return;
            const deltaX = currentX - startX;
            if (deltaX < -30) {
                openRow();
            } else if (deltaX > 30) {
                closeCurrentRow();
            }
            touching = false;
        });

        row.addEventListener('click', event => {
            if (row.classList.contains('open') && !event.target.closest('.dm-swipe-actions')) {
                closeCurrentRow();
            }
        });

        window.addEventListener('resize', () => {
            if (row.classList.contains('open')) {
                openRow();
            }
        });
    });
}

function initConversationActivePage() {
    const messagesContainer = document.querySelector('.dm-messages');
    const conversationSheet = document.getElementById('conversationSheet');
    const openConversationSheet = document.getElementById('openConversationSheet');
    const editBar = document.getElementById('editBar');
    const cancelEdit = document.getElementById('cancelEdit');
    const editMessageId = document.getElementById('editMessageId');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    if (!messagesContainer || !conversationSheet || !messageInput || !sendButton) return;

    document.querySelectorAll('.message-content p').forEach(element => {
        const text = element.innerText;
        const urlRegex = /(index\.php\?[^\s]+)/g;
        element.innerHTML = text.replace(urlRegex, url => (
            `<a href="${url}" style="color: #537031; text-decoration: underline; font-weight: 600;">Lien annonce</a>`
        ));
    });

    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    const openSheet = () => conversationSheet.classList.add('active');
    const closeSheet = () => conversationSheet.classList.remove('active');

    if (openConversationSheet) {
        openConversationSheet.addEventListener('click', openSheet);
    }

    document.querySelectorAll('.dm-msg-more').forEach(button => {
        button.addEventListener('click', event => {
            event.stopPropagation();
            const container = button.closest('.dm-msg-actions');
            const popover = container ? container.querySelector('.dm-msg-popover') : null;
            if (!popover) return;

            document.querySelectorAll('.dm-msg-popover').forEach(el => {
                if (el !== popover) {
                    el.style.display = 'none';
                }
            });

            popover.style.display = popover.style.display === 'block' ? 'none' : 'block';
        });
    });

    document.querySelectorAll('.dm-edit-trigger').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();

            if (!editBar || !editMessageId) return;
            editMessageId.value = button.getAttribute('data-message-id') || '';
            messageInput.value = button.getAttribute('data-message-text') || '';
            editBar.style.display = 'flex';
            sendButton.textContent = 'Modifier';
            messageInput.focus();

            const popover = button.closest('.dm-msg-popover');
            if (popover) popover.style.display = 'none';
        });
    });

    if (cancelEdit && editBar && editMessageId) {
        cancelEdit.addEventListener('click', () => {
            editMessageId.value = '';
            messageInput.value = '';
            editBar.style.display = 'none';
            sendButton.textContent = 'Envoyer';
            messageInput.focus();
        });
    }

    document.querySelectorAll('[data-sheet-close]').forEach(el => {
        el.addEventListener('click', closeSheet);
    });

    document.addEventListener('click', event => {
        if (!event.target.closest('.dm-msg-actions')) {
            document.querySelectorAll('.dm-msg-popover').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
}

const NotificationsPage = {
    allNotifications: [],
    currentFilter: 'all',

    init() {
        if (!document.getElementById('notificationsListContainer')) return;
        this.bindFilterButtons();
        this.loadAllNotifications();
    },

    bindFilterButtons() {
        document.querySelectorAll('.filter-btn[data-filter]').forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter || 'all';
                this.filterNotifications(filter);
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    },

    loadAllNotifications() {
        fetch('index.php?controleur=annonce&methode=getAllNotifications')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications) {
                    this.allNotifications = data.notifications;
                    this.renderNotifications(this.allNotifications);
                    this.marquerToutesCommeLues();
                } else {
                    this.showEmptyState();
                }
            })
            .catch(() => {
                this.showEmptyState();
            });
    },

    renderNotifications(notifications) {
        const container = document.getElementById('notificationsListContainer');
        if (!container) return;

        if (!notifications.length) {
            this.showEmptyState();
            return;
        }

        let html = '';
        notifications.forEach(notif => {
            const dateCreation = new Date(notif.date_creation).toLocaleDateString('fr-FR', {
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });

            html += `
                <div class="notification-item ${notif.type} ${notif.lue === 0 ? 'unread-status' : ''}"
                     data-id="${notif.id_notification}"
                     data-read="${notif.lue}"
                     style="cursor: pointer;">
                    <div class="notification-item-header">
                        <div style="flex: 1;">
                            <p class="notification-item-title">${this.escapeHtml(notif.titre)}</p>
                            <p class="notification-item-message">${this.escapeHtml(notif.message)}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="notification-item-date">${dateCreation}</span>
                                <span class="notification-badge ${notif.lue === 0 ? 'unread' : ''}">
                                    ${notif.lue === 0 ? 'Non lue' : 'Lue'}
                                </span>
                            </div>
                        </div>
                        <button class="notify-remove-btn" type="button" data-dismiss-id="${notif.id_notification}" title="Supprimer">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        this.bindNotificationItems();
    },

    bindNotificationItems() {
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('mouseenter', () => {
                const id = item.dataset.id;
                if (id) this.marquerNotificationCommeLue(item, id);
            });
        });

        document.querySelectorAll('.notify-remove-btn[data-dismiss-id]').forEach(button => {
            button.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                this.dismissNotification(button, button.dataset.dismissId);
            });
        });
    },

    showEmptyState() {
        const container = document.getElementById('notificationsListContainer');
        if (!container) return;
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <p class="empty-state-text">Aucune notification pour le moment</p>
            </div>
        `;
    },

    filterNotifications(type) {
        this.currentFilter = type;
        const filtered = type === 'all'
            ? this.allNotifications
            : this.allNotifications.filter(notification => notification.type === type);
        this.renderNotifications(filtered);
    },

    dismissNotification(button, idNotification) {
        const card = button.closest('.notification-item');
        if (!card) return;
        const wasUnread = card.getAttribute('data-read') === '0';

        card.style.opacity = '0';
        card.style.transform = 'translateX(30px)';
        card.style.transition = 'all 0.3s ease';

        setTimeout(() => {
            card.remove();
            this.allNotifications = this.allNotifications.filter(notification => String(notification.id_notification) !== String(idNotification));

            if (wasUnread) {
                this.updateHeaderNotificationBadge();
            }

            const formData = new FormData();
            formData.append('id_notification', idNotification);
            fetch('index.php?controleur=annonce&methode=supprimerNotification', {
                method: 'POST',
                body: formData
            }).catch(() => {});
        }, 300);
    },

    marquerNotificationCommeLue(element, idNotification) {
        if (element.getAttribute('data-read') !== '0') return;

        element.classList.add('marked-as-read');
        const badge = element.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = 'Lue';
            badge.classList.remove('unread');
        }

        element.setAttribute('data-read', '1');

        const notif = this.allNotifications.find(notification => String(notification.id_notification) === String(idNotification));
        if (notif) notif.lue = 1;

        const formData = new FormData();
        formData.append('id_notification', idNotification);
        fetch('index.php?controleur=annonce&methode=marquerNotificationCommeLue', {
            method: 'POST',
            body: formData
        }).then(() => {
            this.updateHeaderNotificationBadge();
        }).catch(() => {});
    },

    updateHeaderNotificationBadge() {
        const unreadCount = this.allNotifications.filter(notification => notification.lue === 0).length;
        const badge = document.getElementById('notificationBadge');
        const countSpan = document.getElementById('notificationCount');

        if (badge && countSpan) {
            if (unreadCount > 0) {
                countSpan.textContent = unreadCount > 9 ? '9+' : unreadCount;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    },

    marquerToutesCommeLues() {
        fetch('index.php?controleur=annonce&methode=marquerToutesNotificationsCommeLues', {
            method: 'POST'
        }).then(response => response.json())
        .then(data => {
            if (!data.success) return;

            this.allNotifications.forEach(notification => {
                notification.lue = 1;
            });

            document.querySelectorAll('.notification-item').forEach(item => {
                item.setAttribute('data-read', '1');
                const badge = item.querySelector('.notification-badge');
                if (badge) {
                    badge.textContent = 'Lue';
                    badge.classList.remove('unread');
                }
                item.classList.remove('unread-status');
            });

            this.updateHeaderNotificationBadge();
        }).catch(() => {});
    },

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

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

            // Vérifier les messages non lus (mécanisme dédié)
            this.checkUnreadMessages();
        }

        /**
         * Vérifie les nouvelles candidatures
         */
        checkCandidatures() {
            if (!window.userIsMaitre) return;

            fetch('index.php?controleur=annonce&methode=verifierNouvellesCandidatures')
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
                    if (data.success && data.notifications) {
                        console.log('📬 Notifications reçues:', data.notifications.length);

                        // Mettre à jour le badge de notifications
                        this.updateNotificationBadge(data.notifications.length);

                        // Mettre à jour AUSSI le badge messages depuis ce flux
                        // (même mécanique que la cloche, mais filtrée sur type message)
                        const unreadMessageCount = data.notifications.filter(notification =>
                            notification && notification.type === 'nouveau_message'
                        ).length;
                        this.updateMessageBadge(unreadMessageCount);

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

                                // Marquer comme lue après affichage (sauf nouveaux messages)
                                // Les notifications de type 'nouveau_message' restent non lues
                                // pour afficher la pastille orange sur l'icône conversation.
                                if (notification.type !== 'nouveau_message') {
                                    setTimeout(() => {
                                        const formData = new FormData();
                                        formData.append('id_notification', notification.id_notification);
                                        fetch('index.php?controleur=annonce&methode=marquerNotificationCommeLue', {
                                            method: 'POST',
                                            body: formData
                                        }).catch(err => console.log('Erreur marquage lu:', err));
                                    }, duration);
                                }
                            }
                        });

                        this.saveSeenNotifications();
                    } else {
                        // Réinitialiser les badges si aucune notification
                        this.updateNotificationBadge(0);
                    }
                })
                .catch(error => {
                    console.log('❌ Erreur vérification notifications:', error);
                });
        }

        /**
         * Vérifie les messages non lus (badge conversation)
         */
        checkUnreadMessages() {
            if (!window.userIsConnected) return;

            fetch('index.php?controleur=message&methode=getUnreadMessageCount')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Endpoint dédié = filet de sécurité / synchronisation
                        this.updateMessageBadge(data.count || 0);
                    }
                })
                .catch(error => {
                    console.log('❌ Erreur vérification messages non lus:', error);
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

        /**
         * Met à jour le badge de messages non lus dans le header
         */
        updateMessageBadge(count) {
            const badge = document.getElementById('messageBadge');
            const countSpan = document.getElementById('messageCount');
            
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
    initTarteAuCitron();
    initAnnonceFiltersPage();
    initAjouterAvisStars();
    initAjouterChienDropzone();
    initMessagesListSwipe();
    initConversationActivePage();
    NotificationsPage.init();

    if (document.getElementById('candidature-success-trigger')) {
        window.showCandidatureSuccess = true;
    }

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
    
    // Charger les notifications générales
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
    
    // Charger et afficher les messages non lus
    updateMessageBadgeNow();
}

/**
 * Met à jour le badge de messages immédiatement
 */
function updateMessageBadgeNow() {
    if (!window.userIsConnected) return;
    
    fetch('index.php?controleur=message&methode=getUnreadMessageCount')
        .then(response => response.json())
        .then(data => {
            console.log('💬 Réponse getUnreadMessageCount:', data);
            if (data.success) {
                console.log('💬 Nombre de messages non lus:', data.count);
                const badge = document.getElementById('messageBadge');
                const countSpan = document.getElementById('messageCount');
                if (badge && countSpan) {
                    if (data.count > 0) {
                        countSpan.textContent = data.count > 9 ? '9+' : data.count;
                        badge.style.display = 'inline-block';
                        console.log('✅ Badge messages AFFICHAGE - count:', data.count);
                    } else {
                        badge.style.display = 'none';
                        console.log('❌ Badge messages MASQUE - count: 0');
                    }
                }
            }
        })
        .catch(error => console.log('❌ Erreur appel getUnreadMessageCount:', error));
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

