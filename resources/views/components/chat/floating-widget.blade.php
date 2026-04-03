<!-- Floating Chat Widget (Only for authenticated users) -->
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
            <!-- Chat Messages -->
            <div id="popupMessages" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="text-center text-on-surface-variant py-8">
                    <p class="text-sm">جاري التحميل...</p>
                </div>
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-outline-variant/15 bg-surface-container-low shrink-0">
                <form id="popupMessageForm" class="flex items-end gap-2">
                    @csrf
                    <input type="hidden" id="popupConversationId" value="" />
                    <input type="hidden" id="popupUserId" value="{{ auth()->id() }}" />
                    
                    <textarea id="popupMessageInput" 
                              class="flex-1 bg-surface-container-highest border border-outline-variant rounded-lg px-4 py-2 text-sm font-body text-on-surface placeholder:text-on-surface-variant/60 resize-none focus:outline-none focus:ring-2 focus:ring-primary/20"
                              placeholder="اكتب رسالتك..."
                              rows="1"
                              style="max-height: 100px;"></textarea>
                    
                    <button type="submit" 
                            class="w-10 h-10 flex items-center justify-center bg-primary text-on-primary rounded-lg transition-transform active:scale-90 hover:bg-primary-container flex-shrink-0">
                        <span class="material-symbols-outlined text-lg">send</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden flex-1 flex flex-col items-center justify-center text-center p-6">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 mb-4">chat_bubble_outline</span>
            <p class="text-on-surface-variant font-headline text-sm mb-4">لا توجد محادثات حالياً</p>
            <button class="px-4 py-2 bg-primary text-on-primary rounded-lg text-xs font-bold hover:bg-primary-container transition-all"
                    onclick="startNewChat()">
                ابدأ محادثة جديدة
            </button>
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
</style>

<script>
    // Chat Widget Configuration
    const chatWidget = {
        conversationId: null,
        userId: {{ auth()->id() }},
        isOpen: false,
        isMinimized: false,
        conversations: [],
        messages: [],
        autoRefresh: null,

        init() {
            this.setupEventListeners();
            this.loadConversations();
            this.setupAutoRefresh();
        },

        setupEventListeners() {
            document.getElementById('toggleChat').addEventListener('click', () => this.toggle());
            document.getElementById('closeChat').addEventListener('click', () => this.close());
            document.getElementById('minimizeChat').addEventListener('click', () => this.minimize());
            document.getElementById('popupMessageForm').addEventListener('submit', (e) => this.sendMessage(e));

            // Auto resize textarea
            const input = document.getElementById('popupMessageInput');
            input?.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });
        },

        async loadConversations() {
            try {
                const response = await fetch('/chat', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    console.error('Chat API Error:', response.status);
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success === false) {
                    console.error('Chat Error:', data.message);
                    return;
                }

                this.conversations = data.conversations || [];
                this.renderConversationsList();
                this.updateUnreadCount(data.unread_count || 0);
            } catch (error) {
                console.error('Error loading conversations:', error);
                const list = document.getElementById('conversationsList');
                list.innerHTML = `
                    <div class="text-center text-error py-8">
                        <span class="material-symbols-outlined text-4xl opacity-50 block mb-2">error_outline</span>
                        <p class="text-sm font-headline">خطأ في التحميل</p>
                        <button onclick="chatWidget.loadConversations()" class="text-xs text-primary mt-2 font-bold hover:underline">إعادة محاولة</button>
                    </div>
                `;
            }
        },

        renderConversationsList() {
            const list = document.getElementById('conversationsList');
            
            if (this.conversations.length === 0) {
                list.innerHTML = `
                    <div class="text-center text-on-surface-variant py-8">
                        <span class="material-symbols-outlined text-4xl opacity-50 block mb-2">chat_bubble_outline</span>
                        <p class="text-sm font-headline">لا توجد محادثات</p>
                    </div>
                `;
                return;
            }

            list.innerHTML = this.conversations.map(conv => `
                <button onclick="chatWidget.openConversation(${conv.id})"
                        class="w-full text-right p-3 bg-surface-container-high hover:bg-surface-container rounded-lg transition-all text-sm">
                    <div class="flex justify-between items-start">
                        <span class="font-headline font-bold text-on-surface">${conv.other_user.name}</span>
                        ${conv.unread_count > 0 ? `<span class="text-[10px] text-white bg-primary px-2 py-1 rounded-full">${conv.unread_count}</span>` : ''}
                    </div>
                    <p class="text-xs text-on-surface-variant truncate mt-1">${conv.last_message?.body || 'لا توجد رسائل'}</p>
                </button>
            `).join('');
        },

        async openConversation(conversationId) {
            this.conversationId = conversationId;
            document.getElementById('popupConversationId').value = conversationId;
            document.getElementById('conversationsList').classList.add('hidden');
            document.getElementById('chatView').classList.remove('hidden');

            await this.loadMessages();
            this.scrollToBottom();
        },

        async loadMessages() {
            try {
                const response = await fetch(`/chat/${this.conversationId}/messages?per_page=20`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    console.error('Messages API Error:', response.status);
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                if (data.success) {
                    this.messages = data.messages.data || [];
                    this.renderMessages();
                } else {
                    console.error('Messages API Error:', data.message);
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        },

        renderMessages() {
            const container = document.getElementById('popupMessages');
            
            if (this.messages.length === 0) {
                container.innerHTML = '<p class="text-center text-on-surface-variant text-sm py-6">لا توجد رسائل حتى الآن</p>';
                return;
            }

            container.innerHTML = this.messages.map(msg => {
                const isOwn = msg.user_id === this.userId;
                const timestamp = new Date(msg.created_at).toLocaleTimeString('ar-EG', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });

                return `
                    <div class="message-bubble ${isOwn ? 'flex justify-end' : 'flex justify-start'}">
                        <div class="max-w-[70%]">
                            <div class="bg-${isOwn ? 'primary' : 'surface-container'} ${isOwn ? 'text-on-primary' : 'text-on-surface'} px-3 py-2 rounded-lg rounded-${isOwn ? 'tr' : 'tl'}-none text-sm leading-relaxed break-words">
                                ${this.escapeHtml(msg.body)}
                            </div>
                            <p class="text-[10px] text-on-surface-variant mt-1 px-1">${timestamp}</p>
                        </div>
                    </div>
                `;
            }).join('');
        },

        async sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('popupMessageInput');
            const button = e.target.querySelector('button[type="submit"]');
            
            if (!input.value.trim() || !this.conversationId) return;

            const body = input.value.trim();
            button.disabled = true;

            try {
                const response = await fetch(`/chat/${this.conversationId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ body })
                });

                if (response.ok) {
                    input.value = '';
                    input.style.height = 'auto';
                    await this.loadMessages();
                    this.scrollToBottom();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            } finally {
                button.disabled = false;
            }
        },

        scrollToBottom() {
            const container = document.getElementById('popupMessages');
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 100);
            }
        },

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        open() {
            const popup = document.getElementById('chatPopup');
            popup.classList.remove('hidden');
            this.isOpen = true;
            this.isMinimized = false;
            
            // Reset view to conversations list
            if (!this.conversationId) {
                this.renderConversationsList();
            }
        },

        close() {
            const popup = document.getElementById('chatPopup');
            popup.classList.add('hidden');
            this.isOpen = false;
            this.conversationId = null;
            document.getElementById('conversationsList').classList.remove('hidden');
            document.getElementById('chatView').classList.add('hidden');
        },

        minimize() {
            const popup = document.getElementById('chatPopup');
            if (this.isMinimized) {
                popup.classList.remove('hidden');
                this.isMinimized = false;
            } else {
                popup.classList.add('hidden');
                this.isMinimized = true;
            }
        },

        backToList() {
            this.conversationId = null;
            document.getElementById('conversationsList').classList.remove('hidden');
            document.getElementById('chatView').classList.add('hidden');
        },

        updateUnreadCount(count) {
            const badge = document.getElementById('unreadBadge');
            const countEl = document.getElementById('unreadCount');
            
            if (count > 0) {
                countEl.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        },

        setupAutoRefresh() {
            // Refresh conversations every 10 seconds if open
            this.autoRefresh = setInterval(() => {
                if (this.isOpen) {
                    if (this.conversationId) {
                        this.loadMessages();
                    } else {
                        this.loadConversations();
                    }
                }
            }, 10000);
        },

        escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    };

    // Helper functions
    function startNewChat() {
        // This would open a dialog to select a user or admin
        alert('سيتم إضافة ميزة بدء محادثة جديدة قريباً');
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        chatWidget.init();
    });
</script>
@endauth
