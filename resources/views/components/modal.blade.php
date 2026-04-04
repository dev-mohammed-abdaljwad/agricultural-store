<!-- Universal Reusable Modal Component -->
<div id="universalModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 animate-fadeIn">
    <!-- Modal Container -->
    <div class="bg-surface rounded-2xl w-full max-w-sm shadow-xl transform transition-all animate-scaleIn">
        <!-- Modal Header -->
        <div id="modalHeader" class="px-6 py-4 border-b border-outline-variant/20 flex items-center gap-3">
            <span id="modalIcon" class="material-symbols-outlined text-2xl text-primary"></span>
            <h2 id="modalTitle" class="text-xl font-bold text-on-surface">عنوان</h2>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-4">
            <p id="modalMessage" class="text-on-surface-variant leading-relaxed"></p>
            
            <!-- Dynamic Content Area (for custom content) -->
            <div id="modalContent" class="mt-4"></div>
        </div>

        <!-- Modal Footer with Buttons -->
        <div id="modalFooter" class="px-6 py-4 border-t border-outline-variant/20 flex gap-3 justify-end">
            <button id="modalSecondaryBtn" type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-surface-container text-on-surface rounded-lg font-bold hover:bg-surface-container-high transition-colors active:scale-95">
                <span id="secondaryBtnText">إلغاء</span>
            </button>
            <button id="modalPrimaryBtn" type="button" onclick="executeModalAction()" class="flex-1 px-4 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-colors active:scale-95">
                <span id="primaryBtnText">تأكيد</span>
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}

.animate-scaleIn {
    animation: scaleIn 0.2s ease-out;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    #universalModal {
        padding: 1rem;
    }
    
    #universalModal > div {
        max-width: 100%;
    }
}
</style>

<script>
// Global Modal State
let modalConfig = {
    icon: 'info',
    title: 'عنوان',
    message: 'الرسالة',
    primaryBtnText: 'تأكيد',
    secondaryBtnText: 'إلغاء',
    primaryBtnColor: 'primary',
    onConfirm: null,
    onCancel: null,
};

/**
 * Show notification modal (info/success/warning/error)
 * @param {string} message - The message to display
 * @param {string} type - 'info', 'success', 'warning', 'error'
 * @param {function} callback - Optional callback when closed
 */
function showNotification(message, type = 'info', callback = null) {
    const iconMap = {
        'info': 'info',
        'success': 'check_circle',
        'warning': 'warning',
        'error': 'error',
    };

    const colorMap = {
        'info': 'text-primary',
        'success': 'text-success',
        'warning': 'text-warning',
        'error': 'text-error',
    };

    resetModal();
    
    const modal = document.getElementById('universalModal');
    const icon = document.getElementById('modalIcon');
    const title = document.getElementById('modalTitle');
    const msgEl = document.getElementById('modalMessage');
    const footer = document.getElementById('modalFooter');
    const primaryBtn = document.getElementById('modalPrimaryBtn');

    icon.textContent = iconMap[type] || 'info';
    icon.className = `material-symbols-outlined text-2xl ${colorMap[type]}`;
    
    title.textContent = {
        'info': 'معلومة',
        'success': 'نجح',
        'warning': 'تنبيه',
        'error': 'خطأ',
    }[type];
    
    msgEl.textContent = message;
    footer.innerHTML = `<button type="button" onclick="closeModal()" class="w-full px-4 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-colors active:scale-95">حسناً</button>`;

    if (callback) {
        modalConfig.onConfirm = () => {
            closeModal();
            callback();
        };
    }

    modal.classList.remove('hidden');
}

/**
 * Show confirmation modal with delete styling (red)
 * @param {string} title - Modal title
 * @param {string} message - Modal message
 * @param {function} onConfirm - Callback when confirmed
 * @param {string} primaryBtnText - Primary button text (default: تأكيد)
 */
function showDeleteConfirm(title, message, onConfirm, primaryBtnText = 'حذف') {
    resetModal();
    
    const modal = document.getElementById('universalModal');
    const icon = document.getElementById('modalIcon');
    const titleEl = document.getElementById('modalTitle');
    const msgEl = document.getElementById('modalMessage');
    const primaryBtn = document.getElementById('modalPrimaryBtn');
    const primaryBtnTextEl = document.getElementById('primaryBtnText');
    const secondaryBtnTextEl = document.getElementById('secondaryBtnText');

    icon.textContent = 'delete';
    icon.className = 'material-symbols-outlined text-2xl text-error';
    titleEl.textContent = title;
    msgEl.textContent = message;
    primaryBtnTextEl.textContent = primaryBtnText;
    secondaryBtnTextEl.textContent = 'إلغاء';
    
    primaryBtn.className = 'flex-1 px-4 py-3 bg-error text-on-error rounded-lg font-bold hover:opacity-90 transition-colors active:scale-95';
    
    modalConfig.onConfirm = onConfirm;

    modal.classList.remove('hidden');
}

/**
 * Show warning/confirmation modal (yellow color)
 */
function showWarningConfirm(title, message, onConfirm, primaryBtnText = 'تأكيد') {
    resetModal();
    
    const modal = document.getElementById('universalModal');
    const icon = document.getElementById('modalIcon');
    const titleEl = document.getElementById('modalTitle');
    const msgEl = document.getElementById('modalMessage');
    const primaryBtn = document.getElementById('modalPrimaryBtn');
    const primaryBtnTextEl = document.getElementById('primaryBtnText');

    icon.textContent = 'warning';
    icon.className = 'material-symbols-outlined text-2xl text-warning';
    titleEl.textContent = title;
    msgEl.textContent = message;
    primaryBtnTextEl.textContent = primaryBtnText;
    
    primaryBtn.className = 'flex-1 px-4 py-3 bg-warning text-on-warning rounded-lg font-bold hover:opacity-90 transition-colors active:scale-95';
    
    modalConfig.onConfirm = onConfirm;

    modal.classList.remove('hidden');
}

/**
 * Show success modal
 */
function showSuccess(message, title = 'نجح', callback = null) {
    showNotification(message, 'success', callback);
    document.getElementById('modalTitle').textContent = title;
}

/**
 * Show error modal
 */
function showError(message, title = 'خطأ', callback = null) {
    showNotification(message, 'error', callback);
    document.getElementById('modalTitle').textContent = title;
}

/**
 * Close the modal
 */
function closeModal() {
    const modal = document.getElementById('universalModal');
    modal.classList.add('hidden');
    resetModal();
}

/**
 * Execute the modal's primary action
 */
function executeModalAction() {
    if (modalConfig.onConfirm && typeof modalConfig.onConfirm === 'function') {
        modalConfig.onConfirm();
    }
    closeModal();
}

/**
 * Reset modal to default state
 */
function resetModal() {
    modalConfig = {
        icon: 'info',
        title: 'عنوان',
        message: 'الرسالة',
        primaryBtnText: 'تأكيد',
        secondaryBtnText: 'إلغاء',
        primaryBtnColor: 'primary',
        onConfirm: null,
        onCancel: null,
    };
    document.getElementById('modalContent').innerHTML = '';
}

/**
 * Close modal on background click (outside the modal box)
 */
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('universalModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});

/**
 * Close modal on Escape key
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
