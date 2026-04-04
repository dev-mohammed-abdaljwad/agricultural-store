/**
 * Chat Real-time Pusher Integration
 * 
 * Handles:
 * - PopupManager initialization and management
 * - Global chat API (sendMessage, toggle, close, send)
 * - Unread badge updates
 * - Real-time message notifications via Pusher
 * - Auto-open popup for incoming messages
 */

(function () {
    'use strict';

    // Validate dependencies
    if (typeof PopupManager === 'undefined') {
        console.error('[ChatPusher] PopupManager not found. Make sure PopupManager.js is loaded first.');
        return;
    }

    // Get CSRF token from meta tag
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    const CSRF = csrfMetaTag ? csrfMetaTag.content : '';

    // Get current logged-in user ID from window variable
    const ME = window.CURRENT_USER_ID || null;

    if (!ME) {
        console.warn('[ChatPusher] No authenticated user found (CURRENT_USER_ID not set).');
        return;
    }

    // ════════════════════════════════════════════════════════════════
    // 1. INSTANTIATE POPUP MANAGER
    // ════════════════════════════════════════════════════════════════
    const _pm = new PopupManager({
        currentUserId: ME,
        csrf: CSRF,
        maxPopups: 3,
    });

    // ════════════════════════════════════════════════════════════════
    // 2. GLOBAL CHAT ENGINE API
    // Exposed as window.ChatPopupEngine for onclick handlers
    // ════════════════════════════════════════════════════════════════
    window.ChatPopupEngine = {
        toggle: (id) => _pm.toggle(id),
        close: (id) => _pm.close(id),
        send: (id) => _pm.send(id),
    };

    // ════════════════════════════════════════════════════════════════
    // 3. SEND MESSAGE - Called from profile/friends page
    // ════════════════════════════════════════════════════════════════
    window.sendMessage = function (userId) {
        const actionsDiv = document.getElementById(`user-actions-${userId}`);
        let userName = actionsDiv?.dataset.friendName || null;
        let userAvatar = actionsDiv?.dataset.friendAvatar || null;

        // Fallback: Try to extract user name from page title/heading
        if (!userName) {
            const h1 = document.querySelector('h1.text-2xl, h1.font-bold');
            userName = h1 ? h1.textContent.trim() : 'User';
        }

        // Fallback: Try to extract avatar from page
        if (!userAvatar) {
            const avatarEl = document.querySelector('img#profilePictureImage')
                || document.querySelector('.profile-picture img');
            userAvatar = avatarEl ? avatarEl.src : null;
        }

        _pm.openByUserId(userId, userName, userAvatar);
    };

    // ════════════════════════════════════════════════════════════════
    // 4. LEGACY ALIAS FOR BACKWARDS COMPATIBILITY
    // ════════════════════════════════════════════════════════════════
    window.openChatPopup = (userId, userName, userAvatar) =>
        _pm.openByUserId(userId, userName, userAvatar);

    // ════════════════════════════════════════════════════════════════
    // 5. UPDATE UNREAD BADGE HELPER
    // Updates both top navbar and sidebar badges
    // ════════════════════════════════════════════════════════════════
    function updateChatBadge(count) {
        ['chatUnreadBadge', 'sidebarChatBadge'].forEach(id => {
            const badge = document.getElementById(id);
            if (!badge) return;

            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            } else {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            }
        });
    }

    // ════════════════════════════════════════════════════════════════
    // 6. LOAD INITIAL UNREAD BADGE COUNT
    // ════════════════════════════════════════════════════════════════
    fetch('/chat/unread-count')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateChatBadge(data.count);
            }
        })
        .catch(error => {
            console.warn('[ChatPusher] Failed to load initial unread count:', error);
        });

    // ════════════════════════════════════════════════════════════════
    // 7. PUSHER EVENT LISTENERS
    // Real-time chat events via Pusher WebSockets
    // ════════════════════════════════════════════════════════════════

    if (typeof window.pusher === 'undefined') {
        console.warn('[ChatPusher] Pusher not initialized. Real-time features unavailable.');
        return;
    }

    // Subscribe to the user's private notification channel via WebSocket
    // Events broadcast here: message.sent (from MessageSent event)
    const userNotificationChannel = window.pusher.subscribe(`private-notifications.${ME}`);

    if (!userNotificationChannel) {
        console.error('[ChatPusher] Could not subscribe to notification channel.');
        return;
    }

    // ──────────────────────────────────────────────────────────────
    // Handle: New message notifications via Pusher WebSocket
    // 
    // When a message is sent, MessageSent event broadcasts:
    // - To conversation.{conversationId} for all participants
    // - To notifications.{receiverId} for the specific receiver
    // 
    // Payload from MessageSent::broadcastWith():
    // {
    //   message: { id, body, created_at, ... },
    //   sender: { id, name, avatar_url },
    //   conversation_id: number
    // }
    // ──────────────────────────────────────────────────────────────
    userNotificationChannel.bind('message.sent', (payload) => {
        try {
            console.log('[ChatPusher] Received message.sent event via WebSocket:', payload);

            const messageData = payload.message || {};
            const senderData = payload.sender;
            const conversationId = payload.conversation_id;

            // Validate required fields
            if (!conversationId || !senderData) {
                console.warn('[ChatPusher] Invalid message payload:', payload);
                return;
            }

            // Don't auto-open popup if user is already viewing this conversation
            if (window.CURRENT_CONVERSATION_ID == conversationId) {
                console.log('[ChatPusher] User already viewing conversation', conversationId);
                return;
            }

            // Don't show own messages in notifications
            if (senderData.id === ME) {
                console.log('[ChatPusher] Skipping own message');
                return;
            }

            console.log('[ChatPusher] Opening popup for incoming message:', {
                conversationId,
                fromUserId: senderData.id,
                message: messageData,
            });

            // Auto-open popup with incoming message
            _pm.onIncomingMessage(
                conversationId,
                messageData,
                senderData.name,
                senderData.avatar_url
            );

        } catch (error) {
            console.error('[ChatPusher] Error handling message.sent event:', error, payload);
        }
    });

    // ──────────────────────────────────────────────────────────────
    // Log successful subscription
    // ──────────────────────────────────────────────────────────────
    userNotificationChannel.bind('pusher:subscription_succeeded', () => {
        console.log('[ChatPusher] ✅ Successfully subscribed to notifications channel');
    });

    userNotificationChannel.bind('pusher:subscription_error', (error) => {
        console.error('[ChatPusher] ❌ Subscription error:', error);
    });

    console.log('[ChatPusher] ✅ Chat real-time Pusher initialized for user', ME);

})();
