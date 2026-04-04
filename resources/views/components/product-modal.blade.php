<!-- Product Modal Component -->
<div id="productModal" class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 modal-overlay hidden">
    <!-- Modal Container -->
    <div class="bg-white w-full max-w-2xl lg:max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh] md:max-h-[90vh]">
        <!-- Modal Header -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-stone-100 flex flex-row justify-between items-center bg-stone-50/50 sticky top-0">
            <button onclick="closeProductModal()" class="text-stone-400 hover:text-stone-600 transition-colors p-1">
                <span class="material-symbols-outlined text-lg sm:text-2xl" data-icon="close">close</span>
            </button>
            <h3 id="modalTitle" class="text-base sm:text-lg lg:text-xl font-bold text-primary">إضافة منتج جديد</h3>
        </div>

        <!-- Modal Body / Form -->
        <div class="p-4 sm:p-6 lg:p-8 overflow-y-auto">
            <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6 sm:gap-8 text-right">
                @csrf
                <input id="methodInput" type="hidden" name="_method" value="POST">

                <!-- ===== SECTION 1: BASIC INFO ===== -->
                <div class="border-b border-stone-200 pb-6 sm:pb-8">
                    <div class="flex items-center gap-2 mb-4 sm:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-2xl">info</span>
                        <h4 class="text-base sm:text-lg font-bold text-primary">المعلومات الأساسية</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Product Name *Required -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-1">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">اسم المنتج</label>
                                <span class="text-error font-bold">*</span>
                            </div>
                            <input id="name" name="name" required class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all" placeholder="سماد سوبر نايترو - ABA Mektine 5%" type="text"/>
                            <p class="text-xs text-on-surface-variant mt-1">الاسم الكامل والدقيق للمنتج</p>
                        </div>

                        <!-- Category *Required -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-1">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">الفئة</label>
                                <span class="text-error font-bold">*</span>
                            </div>
                            <select id="category_id" name="category_id" required class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all">
                                <option value="">-- اختر الفئة --</option>
                                @forelse(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                    <option disabled>لا توجد فئات</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs sm:text-sm font-bold text-on-surface">حالة المنتج</label>
                            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                <label class="flex items-center gap-2 sm:gap-3 bg-primary-fixed/30 p-2 sm:p-3 rounded-lg cursor-pointer hover:bg-primary-fixed/50 transition-colors">
                                    <input id="status_active" type="radio" name="status" value="active" checked class="w-4 h-4 sm:w-5 sm:h-5 text-primary"/>
                                    <span class="text-xs sm:text-sm font-bold text-on-surface">✓ نشط</span>
                                </label>
                                <label class="flex items-center gap-2 sm:gap-3 bg-stone-100 p-2 sm:p-3 rounded-lg cursor-pointer hover:bg-stone-200 transition-colors">
                                    <input id="status_inactive" type="radio" name="status" value="inactive" class="w-4 h-4 sm:w-5 sm:h-5"/>
                                    <span class="text-xs sm:text-sm font-bold text-stone-600">غير نشط</span>
                                </label>
                            </div>
                        </div>

                        <!-- Is Certified Checkbox -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 sm:gap-3 bg-primary-fixed/30 p-3 sm:p-4 rounded-lg cursor-pointer hover:bg-primary-fixed/50 transition-colors">
                                <input id="is_certified" name="is_certified" type="checkbox" value="1" class="w-4 h-4 sm:w-5 sm:h-5 rounded accent-primary"/>
                                <span class="text-xs sm:text-sm font-bold text-primary">✓ منتج معتمد (معتمد من الجهات الرسمية)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 2: DESCRIPTION & CONTENT ===== -->
                <div class="border-b border-stone-200 pb-6 sm:pb-8">
                    <div class="flex items-center gap-2 mb-4 sm:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-2xl">description</span>
                        <h4 class="text-base sm:text-lg font-bold text-primary">الوصف والمحتوى التفصيلي</h4>
                    </div>
                    <div class="flex flex-col gap-4 sm:gap-6">
                        <!-- Description -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs sm:text-sm font-bold text-on-surface">وصف المنتج</label>
                            <textarea id="description" name="description" class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all resize-none" placeholder="وصف موجز وجذاب للمنتج..." rows="3"></textarea>
                            <p class="text-xs text-on-surface-variant mt-1">وصف قصير يظهر على صفحة المنتج</p>
                        </div>

                        <!-- Usage Instructions -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs sm:text-sm font-bold text-on-surface">طريقة الاستخدام</label>
                            <textarea id="usage_instructions" name="usage_instructions" class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all resize-none" placeholder="شرح مفصل لخطوات الاستخدام الصحيحة..." rows="3"></textarea>
                            <p class="text-xs text-on-surface-variant mt-1">يظهر في تبويب "طريقة الاستخدام"</p>
                        </div>

                        <!-- Safety Instructions -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs sm:text-sm font-bold text-on-surface">تعليمات السلامة والحذر</label>
                            <textarea id="safety_instructions" name="safety_instructions" class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all resize-none" placeholder="تحذيرات ومعلومات السلامة المهمة..." rows="3"></textarea>
                            <p class="text-xs text-on-surface-variant mt-1">يظهر في تبويب "إرشادات السلامة"</p>
                        </div>

                        <!-- Manufacturer Info -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs sm:text-sm font-bold text-on-surface">بيانات المصنع</label>
                            <textarea id="manufacturer_info" name="manufacturer_info" class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all resize-none" placeholder="معلومات عن المصنع والمورد الرئيسي..." rows="3"></textarea>
                            <p class="text-xs text-on-surface-variant mt-1">يظهر في تبويب "بيانات المصنع"</p>
                        </div>

                        <!-- Data Sheet URL -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs sm:text-sm font-bold text-on-surface">رابط ورقة البيانات (PDF/URL)</label>
                            <input id="data_sheet_url" name="data_sheet_url" class="w-full bg-surface-container-low border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all" placeholder="https://example.com/datasheet.pdf" type="url"/>
                            <p class="text-xs text-on-surface-variant mt-1">رابط لتحميل ورقة البيانات التقنية</p>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 3: EXPERT & SUPPLIER ===== -->
                <div class="border-b border-stone-200 pb-6 sm:pb-8">
                    <div class="flex items-center gap-2 mb-4 sm:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-2xl">person</span>
                        <h4 class="text-base sm:text-lg font-bold text-primary">معلومات الخبير والموردة</h4>
                    </div>
                    
                    <!-- Expert Info Subsection -->
                    <div class="bg-primary-fixed/20 p-3 sm:p-4 lg:p-5 rounded-lg mb-4 sm:mb-6 border border-primary-fixed">
                        <h5 class="text-xs sm:text-sm font-bold text-primary mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs sm:text-sm">verified_user</span>
                            معلومات الخبير
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Expert Tip -->
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">نصيحة الخبير</label>
                                <textarea id="expert_tip" name="expert_tip" class="w-full bg-white border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all resize-none" placeholder="نصة متخصصة لاستخدام المنتج بشكل فعال..." rows="2"></textarea>
                                <p class="text-xs text-on-surface-variant mt-1">توصية من خبير متخصص</p>
                            </div>

                            <!-- Expert Name -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">اسم الخبير</label>
                                <input id="expert_name" name="expert_name" class="w-full bg-white border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all" placeholder="م. محمود علام" type="text"/>
                            </div>

                            <!-- Expert Title -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">تخصص الخبير</label>
                                <input id="expert_title" name="expert_title" class="w-full bg-white border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all" placeholder="كبير استشاريي وقاية المحاصيل" type="text"/>
                            </div>

                            <!-- Expert Image URL -->
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">صورة الخبير (رابط الصورة)</label>
                                <input id="expert_image_url" name="expert_image_url" class="w-full bg-white border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all" placeholder="https://example.com/expert.jpg" type="url"/>
                                <p class="text-xs text-on-surface-variant mt-1">صورة دائرية 48x48 بكس لأفضل نتيجة</p>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Info Subsection -->
                    <div class="bg-tertiary-fixed/20 p-3 sm:p-4 lg:p-5 rounded-lg border border-tertiary-fixed">
                        <h5 class="text-xs sm:text-sm font-bold text-tertiary mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs sm:text-sm">storefront</span>
                            معلومات الموردة
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Supplier Name -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">اسم الموردة</label>
                                <input id="supplier_name" name="supplier_name" class="w-full bg-white border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all" placeholder="سنجنتا مصر / باير / كيميتريكا" type="text"/>
                                <p class="text-xs text-on-surface-variant mt-1">الشركة المصنعة الأصلية</p>
                            </div>

                            <!-- Supplier Code -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-on-surface">كود الموردة (SKU)</label>
                                <input id="supplier_code" name="supplier_code" class="w-full bg-white border border-stone-200 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-primary focus:border-transparent text-right transition-all uppercase" placeholder="SUPP-001-2024" type="text"/>
                                <p class="text-xs text-on-surface-variant mt-1">رمز المنتج من الموردة</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 4: PRODUCT IMAGE ===== -->
                <div>
                    <div class="flex items-center gap-2 mb-4 sm:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-2xl">image</span>
                        <h4 class="text-base sm:text-lg font-bold text-primary">صورة المنتج الرئيسية</h4>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="border-2 border-dashed border-primary-fixed rounded-lg p-4 sm:p-6 lg:p-8 flex flex-col items-center justify-center gap-3 sm:gap-4 bg-primary-fixed/5 hover:bg-primary-fixed/10 transition-colors cursor-pointer" onclick="document.getElementById('imageInput').click()">
                            <span class="material-symbols-outlined text-4xl sm:text-5xl text-primary" style="font-variation-settings: 'FILL' 1">cloud_upload</span>
                            <div class="text-center">
                                <p class="text-xs sm:text-sm font-bold text-on-surface">انقر للتحميل أو اسحب الصورة هنا</p>
                                <p class="text-xs text-on-surface-variant mt-2">الصيغ المقبولة: PNG, JPG, GIF (حتى 5 ميجابايت)</p>
                            </div>
                        </div>
                        <input id="imageInput" name="image" type="file" accept="image/*" class="hidden" onchange="previewImage(event)"/>
                        <div id="imagePreview" class="hidden">
                            <img id="previewImg" class="w-full max-h-48 object-contain rounded-lg border border-stone-200" alt="معاينة الصورة"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-t border-stone-100 flex flex-col-reverse sm:flex-row-reverse gap-3 sm:gap-4 bg-stone-50/30">
            <button id="submitBtn" class="bg-primary text-white px-4 sm:px-6 lg:px-8 py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex-1 sm:flex-none">
                حفظ المنتج
            </button>
            <button onclick="closeProductModal()" type="button" class="px-4 sm:px-6 lg:px-8 py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm text-stone-600 border border-stone-300 hover:bg-white transition-all flex-1 sm:flex-none">
                إلغاء
            </button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
    }
    
    .modal-overlay.hidden {
        display: none;
    }
</style>

<script>
    function openProductModal() {
        // Reset to create mode
        document.getElementById('modalTitle').textContent = 'إضافة منتج جديد';
        document.getElementById('productForm').action = '{{ route("admin.products.store") }}';
        document.getElementById('methodInput').value = 'POST';
        document.getElementById('productForm').reset();
        document.getElementById('imagePreview').classList.add('hidden');
        // Open modal
        document.getElementById('productModal').classList.remove('hidden');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.getElementById('productForm').reset();
        document.getElementById('imagePreview').classList.add('hidden');
    }

    function editProduct(productId, productData) {
        console.log('editProduct called with:', productId, productData);
        
        // Update modal title
        document.getElementById('modalTitle').textContent = 'تعديل المنتج';
        
        // Set form action to update route
        document.getElementById('productForm').action = `/admin/products/${productId}`;
        document.getElementById('methodInput').value = 'PUT';
        
        console.log('Form action set to:', document.getElementById('productForm').action);
        
        // Populate all form fields with product data
        document.getElementById('name').value = productData.name || '';
        document.getElementById('category_id').value = productData.category_id || '';
        
        // Handle status radio buttons
        if (productData.status === 'active') {
            document.getElementById('status_active').checked = true;
        } else if (productData.status === 'inactive') {
            document.getElementById('status_inactive').checked = true;
        }
        
        document.getElementById('is_certified').checked = productData.is_certified === 1 || productData.is_certified === true;
        document.getElementById('description').value = productData.description || '';
        document.getElementById('usage_instructions').value = productData.usage_instructions || '';
        document.getElementById('safety_instructions').value = productData.safety_instructions || '';
        document.getElementById('manufacturer_info').value = productData.manufacturer_info || '';
        document.getElementById('data_sheet_url').value = productData.data_sheet_url || '';
        document.getElementById('expert_tip').value = productData.expert_tip || '';
        document.getElementById('expert_name').value = productData.expert_name || '';
        document.getElementById('expert_title').value = productData.expert_title || '';
        document.getElementById('expert_image_url').value = productData.expert_image_url || '';
        document.getElementById('supplier_name').value = productData.supplier_name || '';
        document.getElementById('supplier_code').value = productData.supplier_code || '';
        
        // Load existing product image if available
        document.getElementById('imageInput').value = '';
        if (productData.images && productData.images.length > 0) {
            const primaryImage = productData.images.find(img => img.is_primary === 1 || img.is_primary === true) || productData.images[0];
            if (primaryImage && primaryImage.url) {
                // Get the full URL for the image
                const imageUrl = primaryImage.url.startsWith('http') ? primaryImage.url : '/storage/' + primaryImage.url;
                document.getElementById('previewImg').src = imageUrl;
                document.getElementById('imagePreview').classList.remove('hidden');
                console.log('Image loaded:', imageUrl);
            }
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
        
        // Just open modal without resetting (avoid calling openProductModal which resets to create mode)
        document.getElementById('productModal').classList.remove('hidden');
        
        console.log('Modal opened in edit mode');
    }

    function deleteProduct(event, productId) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        console.log('deleteProduct called with ID:', productId);
        
        if (!productId) {
            showError('لم يتم العثور على معرف المنتج', 'خطأ');
            return;
        }
        
        showDeleteConfirm(
            'حذف المنتج',
            'هل أنت متأكد من حذف هذا المنتج؟ لا يمكن التراجع عن هذا الإجراء.',
            () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const token = csrfToken ? csrfToken.content : '';
            
            if (!token) {
                console.error('CSRF token not found');
                showError('لم يتم العثور على رمز الأمان. حاول تحديث الصفحة', 'خطأ');
                return;
            }
            
            const deleteUrl = `/admin/products/${productId}`;
            console.log('Sending DELETE request to:', deleteUrl);
            console.log('ProductId type:', typeof productId, 'Value:', productId);
            
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                console.log('Response status:', response.status);
                if (response.ok) {
                    location.reload();
                } else {
                    response.text().then(text => console.log('Error response:', text));
                    showError('خطأ في حذف المنتج. حاول مرة أخرى');
                }
            }).catch(error => {
                console.error('Error:', error);
                showError('خطأ في الاتصال. حاول مرة أخرى');
            });
        },
        'حذف'
        );
    }

    // Image preview functionality
    function previewImage(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    // Close modal when clicking outside
    document.getElementById('productModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeProductModal();
        }
    });

    // Drag and drop file upload
    const imageInput = document.getElementById('imageInput');
    const uploadArea = document.querySelector('.border-dashed');
    
    uploadArea?.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-primary-fixed/20');
    });

    uploadArea?.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-primary-fixed/20');
    });

    uploadArea?.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-primary-fixed/20');
        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            previewImage({ target: { files: e.dataTransfer.files } });
        }
    });

    // Handle form submission via AJAX - Deferred to ensure DOM is ready
    function initializeFormHandlers() {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.addEventListener('click', submitProductForm);
        }
        
        const productForm = document.getElementById('productForm');
        if (productForm) {
            productForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitProductForm(e);
            });
        }
    }

    // Initialize on DOM ready or immediately if DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFormHandlers);
    } else {
        initializeFormHandlers();
    }

    async function submitProductForm(e) {
        if (e) {
            e.preventDefault();
        }
        
        const form = document.getElementById('productForm');
        if (!form) {
            alert('خطأ: لم يتم العثور على النموذج');
            return;
        }

        const name = form.querySelector('[name="name"]')?.value?.trim();
        const categoryId = form.querySelector('[name="category_id"]')?.value?.trim();
        
        if (!name || !categoryId) {
            alert('يرجى ملء الحقول المطلوبة (اسم المنتج والفئة)');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        if (!submitBtn) {
            console.error('Submit button not found');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'جاري الحفظ...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                const data = await response.json();
                showSuccess(data.message || 'حفظ المنتج بنجاح', 'نجح');
                
                closeProductModal();
                
                // Clear form
                form.reset();
                const imagePreview = document.getElementById('imagePreview');
                if (imagePreview) {
                    imagePreview.innerHTML = '';
                }
                const imageName = document.getElementById('imageName');
                if (imageName) {
                    imageName.textContent = 'لا يوجد ملف مختار';
                }
                
                // Reload dashboard
                setTimeout(() => {
                    location.reload();
                }, 500);
                
            } else {
                const error = await response.json();
                showError(error.message || 'فشل حفظ المنتج', 'خطأ');
                console.error('Error:', error);
            }
        } catch (error) {
            showError(error.message, 'خطأ');
            console.error('Error:', error);
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'حفظ المنتج';
            }
        }
    }
</script>
