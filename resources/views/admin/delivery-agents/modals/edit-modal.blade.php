{{-- Modal for editing delivery agent --}}
<div id="editAgentModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4" onclick="closeEditModal(event)">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">تعديل عامل التوصيل</h2>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">×</button>
        </div>

        {{-- Form --}}
        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')

            {{-- Personal Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">person</span>
                    البيانات الشخصية
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">الاسم الكامل <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="editPhone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="editEmail" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم الهوية</label>
                        <input type="text" name="id_number" id="editIdNumber" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">location_on</span>
                    البيانات الجغرافية
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">المحافظة <span class="text-red-500">*</span></label>
                        <select name="governorate" id="editGovernorate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                            @foreach($governorates as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">العنوان التفصيلي <span class="text-red-500">*</span></label>
                        <textarea name="address" id="editAddress" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900"></textarea>
                    </div>
                </div>
            </div>

            {{-- Vehicle Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">local_shipping</span>
                    بيانات المركبة
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع المركبة <span class="text-red-500">*</span></label>
                        <select name="vehicle_type" id="editVehicleType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                            <option value="car">سيارة</option>
                            <option value="motorcycle">دراجة نارية</option>
                            <option value="bicycle">دراجة</option>
                            <option value="van">فان</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم اللوحة</label>
                        <input type="text" name="license_plate" id="editLicensePlate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">payment</span>
                    بيانات الدفع
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع الراتب <span class="text-red-500">*</span></label>
                        <select name="salary_type" id="editSalaryType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                            <option value="fixed">راتب ثابت</option>
                            <option value="commission">عمولة</option>
                            <option value="hybrid">راتب + عمولة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نسبة العمولة (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="commission_rate" id="editCommissionRate" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم الحساب البنكي</label>
                        <input type="text" name="bank_account" id="editBankAccount" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">verified_user</span>
                    الحالة
                </h3>
                <select name="status" id="editStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                    <option value="on_leave">في إجازة</option>
                </select>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">note</span>
                    ملاحظات
                </h3>
                <textarea name="notes" id="editNotes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900"></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2 border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50">
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-2 bg-green-700 text-white rounded-lg font-bold hover:bg-green-800">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentAgentId = null;

function openEditModal(agentId, agentData) {
    currentAgentId = agentId;
    
    // Fill form with agent data
    document.getElementById('editName').value = agentData.name || '';
    document.getElementById('editPhone').value = agentData.phone || '';
    document.getElementById('editEmail').value = agentData.email || '';
    document.getElementById('editIdNumber').value = agentData.id_number || '';
    document.getElementById('editGovernorate').value = agentData.governorate || '';
    document.getElementById('editAddress').value = agentData.address || '';
    document.getElementById('editVehicleType').value = agentData.vehicle_type || 'car';
    document.getElementById('editLicensePlate').value = agentData.license_plate || '';
    document.getElementById('editSalaryType').value = agentData.salary_type || 'fixed';
    document.getElementById('editCommissionRate').value = agentData.commission_rate || 0;
    document.getElementById('editBankAccount').value = agentData.bank_account || '';
    document.getElementById('editStatus').value = agentData.status || 'active';
    document.getElementById('editNotes').value = agentData.notes || '';
    
    // Set form action
    const form = document.getElementById('editForm');
    form.action = `/admin/delivery-agents/${agentId}`;
    
    document.getElementById('editAgentModal').classList.remove('hidden');
}

function closeEditModal(event) {
    if (event && event.target.id !== 'editAgentModal') return;
    document.getElementById('editAgentModal').classList.add('hidden');
    currentAgentId = null;
}
</script>
