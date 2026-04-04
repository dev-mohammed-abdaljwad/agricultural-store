@auth
<div id="chatWidget" class="fixed bottom-6 right-6 z-50 select-none">
    <!-- Chat Bubble Button (Minimized State) -->
    <div id="chatBubble" class="cursor-pointer transform transition-all duration-300 hover:scale-110">
        <button id="toggleChat" 
                class="w-16 h-16 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-full shadow-lg flex items-center justify-center hover:shadow-xl transition-all active:scale-95"
                title="فتح المحادثة">
            <span class="material-symbols-outlined text-2xl">forum</span>
        </button>
        <!-- Unread Badge -->
        <div id="unreadBadge" 
             class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center hidden">
            <span id="unreadCount">0</span>
        </div>
    </div>

    <!-- Chat Popup Window (Hidden by default) -->
    <div id="chatPopup" 
         class="absolute bottom-24 right-0 w-96 h-96 bg-surface-container-lowest rounded-2xl shadow-2xl flex flex-col overflow-hidden hidden transition-all duration-300 transform origin-bottom-right">
        
        <!-- Header -->
        <header class="h-16 bg-gradient-to-r from-primary to-primary-container text-on-primary px-6 flex items-center justify-between shrink-0">
            <div>
                <h3 class="font-headline font-bold text-base">الدعم الفني</h3>
                <p class="text-xs opacity-90">عادة ما نرد في دقائق</p>
            </div>
            <div class="flex items-center gap-2">
                <button id="minimizeChat" 
                        class="p-2 hover:bg-white/20 rounded-full transition-all"
                        title="تصغير">
                    <span class="material-symbols-outlined text-lg">remove</span>
                </button>
                <button id="closeChat" 
                        class="p-2 hover:bg-white/20 rounded-full transition-all"
                        title="إغلاق">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        </header>

        <!-- Conversations List (Initial State) -->
        <div id="conversationsList" class="flex-1 overflow-y-auto p-4 space-y-2">
            <div class="text-center text-on-surface-variant py-8">
                <span class="material-symbols-outlined text-4xl opacity-50 block mb-2">chat_bubble_outline</span>
                <p class="text-sm font-headline">جاري التحميل...</p>
            </div>
        </div>

        <!-- Chat View (Hidden by default) -->
        <div id="chatView" class="hidden flex-1 flex flex-col bg-surface-container-lowest">
            <!-- Back Button Header -->
            <div class="h-12 flex items-center px-4 border-b border-outline-variant/15 shrink-0">
                <button onclick="chatWidget.backToList()" 
                        class="p-2 -ml-2 hover:bg-surface-container rounded-full transition-all"
                        title="رجوع">
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </div>
            
            <!-- Chat Messages -->
            <div id="popupMessages" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="text-center text-on-surface-variant py-8">
                    <p class="text-sm">جاري التحميل...</p>
                </div>
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-outline-variant/15 bg-surface-container-low shrink-0">
                <form id="popupMessageForm" class="flex flex-col gap-2">
                    @csrf
                    <input type="hidden" id="popupConversationId" value="" />
                    <input type="hidden" id="popupUserId" value="{{ auth()->id() }}" />
                    <input type="hidden" id="popupCsrfToken" value="{{ csrf_token() }}" />
                    
                    <!-- File input (hidden) -->
                    <input type="file" id="popupFileInput" class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" />
                    
                    <!-- File Preview -->
                    <div id="popupFilePreview" class="hidden flex items-center gap-2 p-2 bg-surface-container rounded-lg text-sm">
                        <span class="material-symbols-outlined text-base text-primary">attach_file</span>
                        <span id="popupFileName" class="flex-1 truncate text-on-surface-variant"></span>
                        <button type="button" onclick="chatWidget.clearFile()" class="p-1 hover:bg-surface-container-highest rounded transition">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>
                    
                    <!-- Message Input Row -->
                    <div class="flex items-end gap-2">
                        <button type="button" 
                                onclick="document.getElementById('popupFileInput').click()"
                                id="popupAttachButton"
                                class="w-10 h-10 flex items-center justify-center bg-surface-container text-on-surface-variant rounded-lg hover:bg-surface-container-highest transition-colors"
                                title="إضافة ملف صورة">
                            <span class="material-symbols-outlined text-lg">attach_file</span>
                        </button>
                        
                        <textarea id="popupMessageInput" 
                                  class="flex-1 bg-surface-container-highest border border-outline-variant rounded-lg px-4 py-2 text-sm font-body text-on-surface placeholder:text-on-surface-variant/60 resize-none focus:outline-none focus:ring-2 focus:ring-primary/20"
                                  placeholder="اكتب رسالتك..."
                                  rows="1"
                                  autocomplete="off"></textarea>
                        
                        <button type="submit" 
                                id="popupSendButton"
                                class="w-10 h-10 flex items-center justify-center bg-primary text-on-primary rounded-lg transition-transform active:scale-90 hover:bg-primary-container flex-shrink-0"
                                title="إرسال">
                            <span class="material-symbols-outlined text-lg">send</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #chatPopup:not(.hidden) {
        display: flex;
        animation: slideIn 0.3s ease-out;
    }

    #chatPopup.hidden {
        display: none;
    }

    @keyframes slideIn {
        from {
            transform: scale(0.95) translateY(20px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    #popupMessageInput {
        overflow: hidden;
        line-height: 1.5;
        min-height: 2.5em;
        max-height: 100px;
    }

    #popupMessageInput:focus {
        border-color: var(--primary);
    }

    .message-bubble {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .animate-bounce {
        animation: bounce 1s infinite;
    }
</style>

<script>
const chatWidget = {
    conversationId: null,
    userId: {{ auth()->id() ?? 0 }},
    userRole: '{{ auth()->user()?->role ?? "" }}',
    isOpen: false,
    conversations: [],
    messages: [],
    echoListeners: {},
    isOnConversationPage: false,

    init() {
        try {
            console.log('[ChatWidget] Initializing chat widget');
            this.isOnConversationPage = document.getElementById('messages-container') !== null;
            this.setupEventListeners();
            this.setupFileUpload();
            this.loadConversations();
            this.setupEchoListeners();
            this.setupPollingFallback();
            console.log('[ChatWidget] Chat widget initialized successfully');
        } catch (err) {
            console.error('[ChatWidget] Initialization error:', err);
        }
    },

    setupEchoListeners() {
        if (typeof Echo === 'undefined') {
            console.warn('[ChatWidget] Laravel Echo not loaded, using polling only');
            return;
        }

        this.loadConversations().then(() => {
            this.conversations.forEach(conv => {
                this.subscribeToConversation(conv.id);
            });
        }).catch(err => {
            console.warn('[ChatWidget] Echo setup failed:', err);
        });
    },

    subscribeToConversation(conversationId) {
        if (typeof Echo === 'undefined') return;
        if (this.echoListeners[conversationId]) return;

        try {
            const channel = Echo.private(`conversation.${conversationId}`);

            channel.listen('.message.sent', (data) => {
                this.handleIncomingMessage(data, conversationId);
            });

            this.echoListeners[conversationId] = channel;
        } catch (err) {
            console.warn('[ChatWidget] Echo subscription failed:', err);
        }
    },

    unsubscribeFromConversation(conversationId) {
        if (typeof Echo === 'undefined') return;
        if (!this.echoListeners[conversationId]) return;

        try {
            Echo.leaveChannel(`conversation.${conversationId}`);
            delete this.echoListeners[conversationId];
            console.log('[ChatWidget] Unsubscribed from conversation:', conversationId);
        } catch (err) {
            console.warn('[ChatWidget] Echo unsubscription failed:', err);
        }
    },

    handleIncomingMessage(data, conversationId) {
        const isFromOther = data.sender_id !== this.userId;

        if (isFromOther) {
            this.incrementUnreadBadge();
        }

        // If on conversation page, don't auto-open
        if (this.isOnConversationPage) {
            console.log('[ChatWidget] Not auto-opening: user is on conversation page');
            return;
        }

        console.log('[ChatWidget] Incoming message from other:', isFromOther, 'current conv:', this.conversationId);

        if (isFromOther) {
            // Auto-open if not already viewing this conversation
            if (!this.isOpen || this.conversationId !== conversationId) {
                console.log('[ChatWidget] Auto-opening widget for conversation', conversationId);
                this.open();
                this.openConversation(conversationId);
            } else if (this.conversationId === conversationId) {
                // Already viewing this conversation, just append
                this.appendMessageToWidget(data);
                this.scrollToBottom();
                this.markRead(conversationId);
            }
            this.pulseButton();
        } else if (this.conversationId === conversationId && this.isOpen) {
            // Own message while viewing this conversation
            this.appendMessageToWidget(data);
            this.scrollToBottom();
        }
    },

    appendMessageToWidget(data) {
        const container = document.getElementById('popupMessages');
        if (!container) {
            console.warn('[ChatWidget] appendMessageToWidget: Messages container not found');
            return;
        }

        if (!data) {
            console.warn('[ChatWidget] appendMessageToWidget: Invalid message data', data);
            return;
        }

        const isOwn = data.sender_id === this.userId || data.user_id === this.userId;
        const timestamp = new Date(data.created_at || new Date()).toLocaleTimeString('ar-EG', {
            hour: '2-digit', minute: '2-digit'
        });

        const div = document.createElement('div');
        div.className = `message-bubble flex ${isOwn ? 'justify-start' : 'justify-end'} mb-2`;
        
        // Build message content
        let messageContent = '';
        
        // Add text if present
        if (data.body) {
            messageContent += `<div class="text-sm leading-relaxed break-words">${this.escapeHtml(data.body)}</div>`;
        }
        
        // Add attachment if present
        if (data.attachment_url) {
            if (data.attachment_type === 'image') {
                messageContent += `<img src="${data.attachment_url}" alt="Attachment" class="max-w-[200px] rounded-lg mt-1">`;
            } else {
                messageContent += `<a href="${data.attachment_url}" target="_blank" class="inline-flex items-center gap-1 mt-1 p-2 bg-white/20 hover:bg-white/30 rounded-lg text-xs">
                    <span class="material-symbols-outlined text-sm">attach_file</span>
                    ${data.attachment_name || 'ملف'}
                </a>`;
            }
        }
        
        // Build the full message bubble
        div.innerHTML = `
            <div class="max-w-[75%]">
                <div class="${isOwn ? 'bg-primary text-on-primary rounded-tr-2xl rounded-tl-sm rounded-b-2xl' : 'bg-surface-container text-on-surface rounded-tl-2xl rounded-tr-sm rounded-b-2xl'} px-3 py-2">
                    ${messageContent}
                </div>
                <p class="text-[10px] text-on-surface-variant mt-1 px-1 ${isOwn ? 'text-left' : 'text-right'}">${timestamp}</p>
            </div>
        `;
        
        try {
            container.appendChild(div);
            console.log('[ChatWidget] Message appended successfully');
        } catch (err) {
            console.error('[ChatWidget] appendMessageToWidget error:', err);
        }
    },

    pulseButton() {
        const btn = document.getElementById('toggleChat');
        btn.classList.add('animate-bounce');
        setTimeout(() => btn.classList.remove('animate-bounce'), 3000);
    },

    incrementUnreadBadge() {
        const badge = document.getElementById('unreadBadge');
        const countEl = document.getElementById('unreadCount');
        const current = parseInt(countEl.textContent) || 0;
        const newCount = current + 1;

        countEl.textContent = newCount > 99 ? '99+' : newCount;
        badge.classList.remove('hidden');
    },

    setupPollingFallback() {
        // Always set up polling as fallback (Echo may not be available)
        setInterval(() => {
            if (this.isOpen && this.conversationId) {
                this.loadMessages();
            } else if (this.isOpen && !this.conversationId) {
                // Only reload conversations if widget is open but not in a conversation
                this.loadConversations();
            }
        }, 10000);
    },

    async loadConversations() {
        try {
            const res = await fetch('/chat', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();

            if (!data.success) throw new Error(data.message);

            this.conversations = data.conversations || [];
            this.updateUnreadCount(data.unread_count || 0);
            
            // Only update conversations list if not currently in a conversation
            if (!this.conversationId) {
                this.renderConversationsList();
            }

            if (typeof Echo !== 'undefined') {
                this.conversations.forEach(c => this.subscribeToConversation(c.id));
            }

            return this.conversations;

        } catch (err) {
            console.error('[ChatWidget] loadConversations error:', err);
            const list = document.getElementById('conversationsList');
            if (list) {
                list.innerHTML = `
                    <div class="text-center py-8 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl opacity-50 block mb-2">error_outline</span>
                        <p class="text-sm">خطأ في التحميل</p>
                        <button onclick="chatWidget.loadConversations()" class="text-xs text-primary mt-2 font-bold hover:underline">إعادة المحاولة</button>
                    </div>`;
            }
        }
    },

    async loadMessages() {
        if (!this.conversationId) {
            console.warn('[ChatWidget] loadMessages called without conversationId');
            return;
        }

        const currentConvId = this.conversationId;
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout

            console.log('[ChatWidget] Loading messages for conversation:', currentConvId);
            const res = await fetch(`/chat/${currentConvId}/messages?per_page=20`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();
            console.log('[ChatWidget] Messages loaded:', data);

            // Check if we're still viewing the same conversation
            if (this.conversationId !== currentConvId) {
                console.log('[ChatWidget] Conversation changed while loading, ignoring response');
                return;
            }

            if (data.success && data.messages && data.messages.data) {
                this.messages = Array.isArray(data.messages.data) ? data.messages.data : [];
                console.log('[ChatWidget] Rendering', this.messages.length, 'messages');
                this.renderMessages();
                this.scrollToBottom();
            } else {
                console.warn('[ChatWidget] Invalid messages response:', data);
            }
        } catch (err) {
            if (err.name === 'AbortError') {
                console.error('[ChatWidget] loadMessages timeout');
            } else {
                console.error('[ChatWidget] loadMessages error:', err);
            }
        }
    },

    async openConversation(conversationId) {
        console.log('[ChatWidget] Opening conversation:', conversationId);
        
        this.conversationId = conversationId;
        const convIdInput = document.getElementById('popupConversationId');
        if (convIdInput) {
            convIdInput.value = conversationId;
        }
        
        // Hide conversations list, show chat view
        const convList = document.getElementById('conversationsList');
        const chatView = document.getElementById('chatView');
        if (convList) convList.classList.add('hidden');
        if (chatView) chatView.classList.remove('hidden');

        // Load and display messages
        try {
            await this.loadMessages();
            this.markRead(conversationId);
            this.scrollToBottom();
            this.subscribeToConversation(conversationId);
            console.log('[ChatWidget] Conversation opened successfully');
        } catch (err) {
            console.error('[ChatWidget] Error opening conversation:', err);
        }
    },

    renderConversationsList() {
        const list = document.getElementById('conversationsList');
        if (!list) return;

        if (!this.conversations.length) {
            list.innerHTML = `
                <div class="text-center py-8 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl opacity-50 block mb-2">chat_bubble_outline</span>
                    <p class="text-sm font-headline">لا توجد محادثات</p>
                </div>`;
            return;
        }

        list.innerHTML = this.conversations.map(conv => `
            <button onclick="chatWidget.openConversation(${conv.id})"
                    class="w-full text-right p-3 rounded-xl transition-all text-sm mb-2 ${conv.unread_count > 0 ? 'bg-primary-fixed/20 border border-primary-fixed/40' : 'bg-surface-container hover:bg-surface-container-high'}">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-on-surface text-sm font-headline">${this.escapeHtml(conv.other_user.name)}</span>
                    ${conv.unread_count > 0 ? `<span class="bg-primary text-on-primary text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">${conv.unread_count}</span>` : '<span class="material-symbols-outlined text-sm text-primary" style="font-variation-settings: \'FILL\' 1">done_all</span>'}
                </div>
                ${conv.order_number ? `<span class="text-[10px] bg-surface-container text-on-surface-variant px-2 py-0.5 rounded-full inline-block mb-1">${conv.order_number}</span>` : ''}
                <p class="text-xs text-on-surface-variant truncate">
                    ${conv.last_message ? (conv.last_message.sender_type === this.userRole ? 'أنت: ' : '') + this.escapeHtml(conv.last_message.body || 'صورة') : 'لا توجد رسائل'}
                </p>
            </button>
        `).join('');
    },

    renderMessages() {
        const container = document.getElementById('popupMessages');
        if (!container) return;

        if (!this.messages.length) {
            container.innerHTML = `<p class="text-center text-on-surface-variant text-sm py-8">لا توجد رسائل — ابدأ المحادثة</p>`;
            return;
        }

        container.innerHTML = this.messages.map(msg => {
            const isOwn = msg.user_id === this.userId || msg.sender_id === this.userId;
            const time = new Date(msg.created_at).toLocaleTimeString('ar-EG', {
                hour: '2-digit', minute: '2-digit'
            });

            // Build message content with optional attachment
            let messageBody = `<div class="text-sm leading-relaxed break-words">${this.escapeHtml(msg.body || '')}</div>`;
            
            if (msg.attachment_url) {
                if (msg.attachment_type === 'image') {
                    messageBody += `<img src="${msg.attachment_url}" alt="Attachment" class="max-w-[200px] rounded-lg mt-1">`;
                } else {
                    const fileName = msg.attachment_name || 'ملف';
                    messageBody += `<a href="${msg.attachment_url}" target="_blank" class="inline-flex items-center gap-1 mt-1 p-2 bg-white/20 hover:bg-white/30 rounded-lg text-xs">
                        <span class="material-symbols-outlined text-sm">attach_file</span>
                        ${this.escapeHtml(fileName)}
                    </a>`;
                }
            }

            return `
                <div class="message-bubble flex ${isOwn ? 'justify-start' : 'justify-end'} mb-2">
                    <div class="max-w-[75%]">
                        <div class="${isOwn ? 'bg-primary text-on-primary rounded-tr-2xl rounded-tl-sm rounded-b-2xl' : 'bg-surface-container text-on-surface rounded-tl-2xl rounded-tr-sm rounded-b-2xl'} px-3 py-2">
                            ${messageBody}
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1 px-1 ${isOwn ? 'text-left' : 'text-right'}">${time}</p>
                    </div>
                </div>`;
        }).join('');
    },

    async sendMessage(e) {
        e.preventDefault();
        
        // Get all needed elements
        const button = document.getElementById('popupSendButton');
        const input = document.getElementById('popupMessageInput');
        const fileInput = document.getElementById('popupFileInput');
        
        if (!button) {
            console.error('[ChatWidget] sendMessage: Submit button not found in DOM');
            return;
        }
        
        if (!input) {
            console.error('[ChatWidget] sendMessage: Input field not found in DOM');
            return;
        }
        
        // Disable button to prevent duplicate submissions
        button.disabled = true;
        
        const body = input.value.trim();
        const hasFile = fileInput && fileInput.files.length > 0;

        // Validate message content or file
        if (!body && !hasFile) {
            console.warn('[ChatWidget] sendMessage: Empty message and no file');
            button.disabled = false;
            return;
        }

        // Validate conversation is selected
        if (!this.conversationId) {
            console.error('[ChatWidget] sendMessage: No conversation selected');
            button.disabled = false;
            input.value = body;
            showError('الرجاء اختيار محادثة أولاً');
            return;
        }

        const originalValue = body;
        input.value = '';
        input.style.height = 'auto';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            console.log('[ChatWidget] Sending message to conversation:', this.conversationId, 'body:', body, 'with file:', hasFile);
            
            // Use FormData to support file upload
            const formData = new FormData();
            
            // Append body only if present (backend uses required_without:attachment)
            if (body) {
                formData.append('body', originalValue);
            }
            
            if (hasFile) {
                formData.append('attachment', fileInput.files[0]);
                console.log('[ChatWidget] Attaching file:', fileInput.files[0].name);
            }
            
            // When using FormData, let browser set multipart/form-data header automatically
            const res = await fetch(`/chat/${this.conversationId}/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            console.log('[ChatWidget] Response status:', res.status);

            // Log error response for debugging
            if (!res.ok) {
                const errorData = await res.json().catch(() => ({}));
                console.error('[ChatWidget] Server error response:', errorData);
                throw new Error(errorData.message || `Server error: HTTP ${res.status}`);
            }

            const data = await res.json();
            console.log('[ChatWidget] Response data:', data);

            if (data.success && data.message) {
                console.log('[ChatWidget] Message sent successfully');
                this.appendMessageToWidget({
                    ...data.message,
                    sender_id: data.message.user_id || this.userId,
                    user_id: data.message.user_id || this.userId,
                });
                this.scrollToBottom();
                input.focus();
                
                // Clear file input and preview
                this.clearFile();
            } else {
                throw new Error(data.message || 'Failed to send message');
            }
        } catch (err) {
            console.error('[ChatWidget] sendMessage error:', err.message || err);
            input.value = originalValue;
            const errorMsg = err.message ? `خطأ: ${err.message}` : 'خطأ في إرسال الرسالة. يرجى المحاولة مرة أخرى.';
            showError(errorMsg);
        } finally {
            // Always re-enable button
            if (button) {
                button.disabled = false;
                console.log('[ChatWidget] Send button re-enabled');
            }
        }
    },

    // Handle file selection
    setupFileUpload() {
        const fileInput = document.getElementById('popupFileInput');
        if (!fileInput) return;

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const preview = document.getElementById('popupFilePreview');
                const fileName = document.getElementById('popupFileName');
                
                // Check file size (max 10MB)
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    showError('حجم الملف أكبر من 10 MB');
                    fileInput.value = '';
                    return;
                }
                
                fileName.textContent = file.name;
                preview.classList.remove('hidden');
                console.log('[ChatWidget] File selected:', file.name);
            }
        });
    },

    // Clear selected file
    clearFile() {
        const fileInput = document.getElementById('popupFileInput');
        const preview = document.getElementById('popupFilePreview');
        
        if (fileInput) {
            fileInput.value = '';
        }
        if (preview) {
            preview.classList.add('hidden');
        }
        console.log('[ChatWidget] File cleared');
    },

    async markRead(conversationId) {
        try {
            await fetch(`/chat/${conversationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
        } catch (err) {
            // Silent fail
        }
    },

    toggle() { this.isOpen ? this.close() : this.open(); },

    open() {
        document.getElementById('chatPopup').classList.remove('hidden');
        this.isOpen = true;
        if (!this.conversationId) this.loadConversations();
    },

    close() {
        document.getElementById('chatPopup').classList.add('hidden');
        this.isOpen = false;
        this.conversationId = null;
        document.getElementById('conversationsList').classList.remove('hidden');
        document.getElementById('chatView').classList.add('hidden');
    },

    minimize() {
        const popup = document.getElementById('chatPopup');
        popup.classList.toggle('hidden');
        this.isOpen = !popup.classList.contains('hidden');
    },

    backToList() {
        console.log('[ChatWidget] Going back to conversations list');
        
        // Clear current conversation and unsubscribe
        if (this.conversationId) {
            this.unsubscribeFromConversation(this.conversationId);
            this.conversationId = null;
        }
        
        // Show list, hide chat
        const convList = document.getElementById('conversationsList');
        const chatView = document.getElementById('chatView');
        if (convList) convList.classList.remove('hidden');
        if (chatView) chatView.classList.add('hidden');
        
        // Reload conversations
        this.loadConversations();
        console.log('[ChatWidget] Back to conversations list');
    },

    updateUnreadCount(count) {
        const badge = document.getElementById('unreadBadge');
        const countEl = document.getElementById('unreadCount');
        countEl.textContent = count > 99 ? '99+' : count;
        badge.classList.toggle('hidden', count === 0);
    },

    scrollToBottom() {
        const c = document.getElementById('popupMessages');
        if (c) setTimeout(() => c.scrollTop = c.scrollHeight, 50);
    },

    escapeHtml(text) {
        const map = { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    },

    setupEventListeners() {
        try {
            document.getElementById('toggleChat')?.addEventListener('click', () => this.toggle());
            document.getElementById('closeChat')?.addEventListener('click', () => this.close());
            document.getElementById('minimizeChat')?.addEventListener('click', () => this.minimize());
            document.getElementById('popupMessageForm')?.addEventListener('submit', (e) => this.sendMessage(e));

            const input = document.getElementById('popupMessageInput');
            input?.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });
            
            console.log('[ChatWidget] Event listeners set up successfully');
        } catch (err) {
            console.error('[ChatWidget] Error setting up event listeners:', err);
        }
    },

    // Emergency debug methods - accessible from console
    debug: {
        unlockButton: () => {
            const btn = document.getElementById('popupSendButton');
            if (btn) {
                btn.disabled = false;
                console.log('[ChatWidget.debug] Button unlocked');
            } else {
                console.error('[ChatWidget.debug] Button not found');
            }
        },
        
        getConversationId: () => {
            console.log('[ChatWidget.debug] Current conversationId:', chatWidget.conversationId);
            return chatWidget.conversationId;
        },
        
        setConversationId: (id) => {
            chatWidget.conversationId = id;
            const input = document.getElementById('popupConversationId');
            if (input) input.value = id;
            console.log('[ChatWidget.debug] ConversationId set to:', id);
        },
        
        checkFormState: () => {
            const btn = document.getElementById('popupSendButton');
            const input = document.getElementById('popupMessageInput');
            const form = document.getElementById('popupMessageForm');
            console.log('[ChatWidget.debug] Button exists:', !!btn, 'disabled:', btn?.disabled);
            console.log('[ChatWidget.debug] Input exists:', !!input, 'value:', input?.value);
            console.log('[ChatWidget.debug] Form exists:', !!form);
            console.log('[ChatWidget.debug] ConversationId:', chatWidget.conversationId);
        },
        
        resetForm: () => {
            const btn = document.getElementById('popupSendButton');
            const input = document.getElementById('popupMessageInput');
            if (btn) btn.disabled = false;
            if (input) input.value = '';
            console.log('[ChatWidget.debug] Form reset');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    try {
        chatWidget.init();
    } catch (err) {
        console.error('[ChatWidget] Error during DOMContentLoaded initialization:', err);
    }
});
</script>
@endauth

